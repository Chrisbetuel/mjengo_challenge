<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Challenge;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Penalty;
use App\Models\DirectPurchase;
use App\Models\LipaKidogo;
use App\Models\ActivityLog;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_challenges' => Challenge::count(),
            'active_challenges' => Challenge::where('challenges.status', 'active')->count(),
            'total_payments' => Payment::where('payments.status', 'paid')->sum('amount'),
            'pending_payments' => Payment::where('payments.status', 'pending')->count(),
            'total_materials' => Material::count(),
            'total_direct_purchases' => DirectPurchase::count(),
            'total_lipa_kidogo' => LipaKidogo::count(),
        ];

        $recent_activity = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_activity'));
    }

    public function users()
    {
        $users = User::withCount(['participants', 'payments', 'directPurchases'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function challenges()
    {
        $challenges = Challenge::with(['creator', 'participants'])
            ->withCount(['participants as active_participants_count' => function ($query) {
                $query->where('participants.status', 'active');
            }, 'payments'])
            ->latest()
            ->paginate(15);

        return view('admin.challenges.index', compact('challenges'));
    }

    public function createChallenge()
    {
        return view('admin.challenges.create');
    }

    public function storeChallenge(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'daily_amount' => 'required|numeric|min:1000',
            'max_participants' => 'required|integer|min:1|max:90',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        Challenge::create([
            'name' => $request->name,
            'description' => $request->description,
            'daily_amount' => $request->daily_amount,
            'max_participants' => $request->max_participants,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.challenges')
            ->with('success', 'Challenge created successfully!');
    }

    public function materials()
    {
        $materials = Material::with(['creator'])
            ->latest()
            ->paginate(15);

        return view('admin.materials.index', compact('materials'));
    }

    public function createMaterial()
    {
        return view('admin.materials.create');
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sw_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'sw_description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('materials', 'public');
        }

        Material::create([
            'name' => $request->name,
            'sw_name' => $request->sw_name,
            'description' => $request->description,
            'sw_description' => $request->sw_description,
            'price' => $request->price,
            'image' => $imagePath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.materials')
            ->with('success', 'Material added successfully!');
    }

    public function editMaterial($id)
    {
        $material = Material::findOrFail($id);
        return view('admin.materials.edit', compact('material'));
    }

    public function updateMaterial(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sw_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'sw_description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $imagePath = $material->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('materials', 'public');
        }

        $material->update([
            'name' => $request->name,
            'sw_name' => $request->sw_name,
            'description' => $request->description,
            'sw_description' => $request->sw_description,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.materials')
            ->with('success', 'Material updated successfully!');
    }

    public function deleteMaterial($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('admin.materials')
            ->with('success', 'Material deleted successfully!');
    }

    public function toggleMaterialStatus($id)
    {
        $material = Material::findOrFail($id);
        $material->status = $material->status == 'active' ? 'inactive' : 'active';
        $material->save();

        return redirect()->back()
            ->with('success', 'Material status updated successfully!');
    }

    public function penalties()
    {
        $penalties = Penalty::with(['user', 'challenge'])
            ->latest()
            ->paginate(20);

        return view('admin.penalties.index', compact('penalties'));
    }

    public function resolvePenalty(Request $request, $id)
    {
        $penalty = Penalty::findOrFail($id);
        $penalty->markAsResolved();

        return redirect()->back()->with('success', 'Penalty resolved successfully.');
    }

    public function payments()
    {
        $payments = Payment::with(['participant.user', 'participant.challenge'])
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function reports()
    {
        $monthlyRevenue = Payment::where('status', 'paid')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $challengePerformance = Challenge::withCount(['participants as active_participants_count' => function ($query) {
                $query->where('participants.status', 'active');
            }, 'payments'])
            ->withSum('payments', 'amount')
            ->where('status', 'active')
            ->get();

        $userActivity = User::withCount(['payments', 'directPurchases', 'lipaKidogoPlans'])
            ->orderBy('payments_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact('monthlyRevenue', 'challengePerformance', 'userActivity'));
    }

    public function groups()
    {
        $groups = Group::with(['creator', 'challenge', 'members'])
            ->withCount(['members as active_members_count' => function ($query) {
                $query->where('group_members.status', 'approved');
            }])
            ->latest()
            ->paginate(15);

        $pendingGroups = Group::with(['creator', 'challenge', 'members'])
            ->where('status', 'pending')
            ->withCount(['members as active_members_count' => function ($query) {
                $query->where('group_members.status', 'approved');
            }])
            ->latest()
            ->paginate(15);

        $activeGroups = Group::with(['creator', 'challenge', 'members'])
            ->where('status', 'active')
            ->withCount(['members as active_members_count' => function ($query) {
                $query->where('group_members.status', 'approved');
            }])
            ->latest()
            ->paginate(15);

        return view('admin.groups.index', compact('groups', 'pendingGroups', 'activeGroups'));
    }

    public function approveGroup(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $group->status = 'active';
        $group->save();

        return redirect()->back()->with('success', 'Group approved successfully.');
    }

    public function rejectGroup(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $group->status = 'rejected';
        $group->save();

        return redirect()->back()->with('success', 'Group rejected successfully.');
    }

    public function editGroup($id)
    {
        $group = Group::findOrFail($id);
        return view('admin.groups.edit', compact('group'));
    }

    public function updateGroup(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.groups')
            ->with('success', 'Group updated successfully!');
    }
}
