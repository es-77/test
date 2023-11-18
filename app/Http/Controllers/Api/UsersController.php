<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        $response = ResponseUtil::getResponseArray($users);
        return response($response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        $name = $request->firstName;
        $email = $request->email;
        $password = $request->password;
        if ($validator->fails()) {
            $errors = array_map(function ($err) {
                return $err[0];
            }, $validator->errors()->toArray());

            $errors = implode(',', $errors);
            $response = ResponseUtil::getResponseArray(null, 102, $errors);
            return response()->json($response, 422);
        }
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        $response = ResponseUtil::getResponseArray(null, 101, 'User create successfully');
        return response()->json($response);
    }

    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,

        ]);
        $name = $request->firstName;
        $email = $request->email;
        $password = $request->password;
        if ($validator->fails()) {
            $errors = array_map(function ($err) {
                return $err[0];
            }, $validator->errors()->toArray());

            $errors = implode(',', $errors);
            $response = ResponseUtil::getResponseArray(null, 102, $errors);
            return response()->json($response, 422);
        }
        $user->name = $name;
        $user->email = $email;
        if ($password) {
            $user->password = Hash::make($password);
        }
        $user->save();

        $response = ResponseUtil::getResponseArray(null, 101, 'User Update successfully');
        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            $userId = $id;
            DB::beginTransaction();
            $user = User::with('votes', 'comments')->find($userId);

            if (!$user) {
                DB::rollBack();
                throw new GeneralException('User not found');
            }
            $user->votes()->delete();
            $user->comments()->delete();
            $user->delete();
            DB::commit();

            $response = ResponseUtil::getResponseArray(null, 101, 'User and associated data deleted successfully');
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new GeneralException('Unable to delete user and associated data');
        }
    }
}
