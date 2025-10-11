<?php

namespace App\Http\Controllers;

use App\Models\JenisData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JenisDataController extends Controller
{
    public function index()
    {
        $data = JenisData::with('seksi')->get();
        return Inertia::render('admin/JenisData');
    }
}
