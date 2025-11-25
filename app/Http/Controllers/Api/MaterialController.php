<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials
     */
    public function index()
    {
        $materials = Material::all();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }
}
