<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserService extends Service
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser($data)
    {
        // Hash the password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Create the user
        $user = User::create($data);

        // Assign role if provided
        if (isset($data['role'])) {
            $role = Role::where('name', $data['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

        return $user;
    }

    /**
     * Update an existing user.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateUser(User $user, $data)
    {
        // Hash the password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Update the user
        $user->update($data);

        // Update role if provided
        if (isset($data['role'])) {
            // Remove all current roles
            $user->syncRoles([]);
            
            // Assign new role
            $role = Role::where('name', $data['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

        return $user;
    }

    /**
     * Delete a user and associated data.
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        // Delete user's profile photo if exists
        if ($user->photo_url) {
            Storage::disk('public')->delete($user->photo_url);
        }

        // Delete the user
        return $user->delete();
    }

    /**
     * Get users by role.
     *
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersByRole($role)
    {
        return User::role($role)->get();
    }

    /**
     * Get users with specific permissions.
     *
     * @param string $permission
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersWithPermission($permission)
    {
        return User::permission($permission)->get();
    }

    /**
     * Toggle user status (active/inactive).
     *
     * @param User $user
     * @return User
     */
    public function toggleUserStatus(User $user)
    {
        $user->update(['status' => !$user->status]);
        return $user;
    }
}