<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->model = User::class;
        $this->viewPath = 'admin.components.auth';
        $this->route = 'admin.auth';
        parent::__construct();
    }

    public function create()
    {
        $fields = [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'email' => ['label' => 'Email', 'type' => 'email'],
            'phone' => ['label' => 'Phone', 'type' => 'text'],
            'role_id' => ['label' => 'Role', 'type' => 'select', 'options' => $this->getRoleOptions()],
            'password' => ['label' => 'Password', 'type' => 'password'],
        ];

        return view($this->viewPath . '.form', [
            'fields' => $fields,
            'route' => $this->route
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'role_id' => 'required|exists:user_roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $item = $this->model::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role_id' => $validated['role_id'],
                'password' => Hash::make($validated['password']),
            ]);

            // Create attribute values


            DB::commit();
            return redirect()->route($this->route . '.index')
                ->with('success', 'User đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $fields = [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'email' => ['label' => 'Email', 'type' => 'email'],
            'phone' => ['label' => 'Phone', 'type' => 'text'],
            'role_id' => ['label' => 'Role', 'type' => 'select', 'options' => $this->getRoleOptions()],
            'password' => ['label' => 'Password', 'type' => 'password'],
        ];

        return view($this->viewPath . '.form', [
            'item' => $item,
            'fields' => $fields,
            'route' => $this->route
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
            'role_id' => 'required|exists:user_roles,id',
        ];

        // Add password validation only if provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role_id' => $validated['role_id'],
            ];

            // Update password only if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $item->update($updateData);

            DB::commit();
            return redirect()->route($this->route . '.index')
                ->with('success', 'User đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get role options for the dropdown
     *
     * @return array
     */
    private function getRoleOptions()
    {
        // Get roles from the database
        return \App\Models\UserRole::pluck('name', 'id')->toArray();
    }
}
