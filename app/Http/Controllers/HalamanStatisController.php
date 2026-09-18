<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HalamanStatis;

class HalamanStatisController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Ppid/HalamanStatis', []);
    }
}
