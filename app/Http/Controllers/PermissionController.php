<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        Permission::create([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }

    public function assign(Permission $permission)
    {
        $users = User::all();
        return view('permissions.assign', compact('permission', 'users'));
    }

    public function assignStore(Request $request, Permission $permission)
    {
        $request->validate([
            'users' => 'array',
            'users.*' => 'exists:users,id',
        ]);

        $users = User::find($request->users);
        foreach ($users as $user) {
            $user->givePermissionTo($permission);
        }

        return redirect()->route('permissions.index')->with('success', 'Permissions assigned successfully.');
    }
}
