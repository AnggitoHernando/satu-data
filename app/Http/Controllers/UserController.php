<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $this->authorize('viewAny');
        $base = DB::table('users as a')
            ->leftJoin('role_user_seksi as b', 'b.user_id', '=', 'a.id')
            ->leftJoin('seksi as c', 'c.id', '=', 'b.seksi_id')
            ->where('a.role', '<>', 'super-admin')
            ->groupBy('a.id')
            ->select(
                'a.id',
                DB::raw('MAX(a.name) as name'),
                DB::raw('MAX(a.username) as username'),
                DB::raw('MAX(a.role) as role'),
                DB::raw("IFNULL(CONCAT('[', GROUP_CONCAT(JSON_QUOTE(c.nama_seksi)), ']'), '[]') as list_seksi")
            );

        // bungkus jadi subquery agar pagination-nya gak error
        $query = DB::query()->fromSub($base, 'user_list');

        // filter pencarian
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        // sorting
        if ($request->sortBy && $request->sortDir) {
            $query->orderBy($request->sortBy, $request->sortDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // pagination
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
        $this->authorize('create');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);
        $validated["password"] = Hash::make($validated['password']);
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
    public function apiFormRole(Request $request)
    {
        $role =  Auth::user()->role;
        $id = $request->id;
        if ($role === "admin") {
            $formRole = ["operator", "user"];
        } else {
            $formRole = ["admin", "operator", "user"];
        }
        $query = DB::table('seksi as a')
            ->select([
                'a.id',
                'a.nama_seksi',
                DB::raw('IF(b.user_id IS NOT NULL, 1, 0) as checked'),
            ])
            ->leftJoin('role_user_seksi as b', function ($join) use ($id) {
                $join->on('b.seksi_id', '=', 'a.id')
                    ->where('b.user_id', '=', $id);
            });

        $results = $query->get();
        $user = User::findOrFail($id);
        return response()->json([
            "formRole" => $formRole,
            "listSeksi" => $results,
            "user" => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function storeRole(Request $request)
    {
        $this->authorize('create');
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:admin,operator,user',
            'seksi_id' => 'required_if:role,operator|array',
            'seksi_id.*' => 'required_if:role,operator|exists:seksi,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->role = $validated['role'];
        $user->save();

        if ($validated['role'] === 'operator') {
            $user->seksi()->sync($validated['seksi_id'] ?? []);
        } else {
            $user->seksi()->detach();
        }

        return back()->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);


        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
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
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
