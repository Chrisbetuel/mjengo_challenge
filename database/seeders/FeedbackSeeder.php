<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\User;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the cassian user
        $cassian = User::where('username', 'cassian')->first();

        if ($cassian) {
            Feedback::create([
                'user_id' => $cassian->id,
                'subject' => 'Testimonial',
                'message' => 'Great community and excellent materials',
                'type' => 'testimonial',
                'status' => 'resolved',
            ]);
        }
    }
}
