<?php

namespace App\Http\Controllers;

use App\Models\KritikSaran;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KritikSaranController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/KritikSaran', [
            'items' => KritikSaran::latest()->paginate(10)
        ]);
    }

    public function destroy($id)
    {
        $data = KritikSaran::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Kritik & Saran berhasil dihapus.');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:100',
            'pesan' => 'required|string|max:5000',
        ]);

        KritikSaran::create([
            'nama'       => $validated['nama'] ?? null,
            'pesan'      => $validated['pesan'],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Kritik & Saran berhasil dikirim.');
    }
}
