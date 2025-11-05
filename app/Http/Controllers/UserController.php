<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seksi = Seksi::all();
        return Inertia::render(
            'Admin/Users',
            [
                'list_seksi' => $seksi

            ]
        );
    }

    public function apiIndex(Request $request)
    {
        $query = User::query()
            ->select('id', 'name', 'username', 'role')
            ->where('role', '<>', 'super-admin');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('username', 'like', '%' . $request->search . '%');
        }

        // Sorting
        if ($request->sortBy && $request->sortDir) {
            $query->orderBy($request->sortBy, $request->sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $perPage = $request->perPage ?? 10;
        $users = $query->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);
        $validated["password"] = md5($validated["password"]);
        $data = User::create($validated);
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Data berhasil disimpan!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = md5($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Data berhasil disimpan!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
