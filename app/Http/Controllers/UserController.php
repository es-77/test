<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users_table', ['users' => $users]);
    }

    public function userDestory(User $user, $id)
    {
        try {
            $userId = $id;
            DB::beginTransaction();
            $user = User::with('votes', 'comments')->find($userId);

            if (!$user) {
                DB::rollBack();
                return response()->json(['error' => 'User not found'], 404);
            }
            $user->votes()->delete();
            $user->comments()->delete();
            $user->delete();
            DB::commit();

            return response()->json(['message' => 'User and associated data deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Unable to delete user and associated data'], 500);
        }
    }
}
