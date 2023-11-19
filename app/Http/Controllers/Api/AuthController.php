<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GeneralException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\ResponseUtil;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function loginWithEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);
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
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new GeneralException('Invalid email');
        }

        if (!Hash::check($password, $user->password)) {
            throw new GeneralException('Invalid password');
        }

        // This token will be used for authorization afterwards
        $token = $user->createToken('apptoken')->plainTextToken;
        $data = [
            'user' => $user,
            'token' => $token,
        ];
        $response = ResponseUtil::getResponseArray($data, 'Logged In successfully');

        return response($response, 200);
    }

    public function registor(Request $request)
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

        $token = $user->createToken('apptoken')->plainTextToken;
        $data = [
            'user' => $user,
            'token' => $token,
        ];
        $response = ResponseUtil::getResponseArray($data, 'Create successfully');
        return response($response, 200);
    }

    public function user(Request $request)
    {
        $user = auth()->user();
        $response = ResponseUtil::getResponseArray($user);
        return response($response);
    }

    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();
        $message = 'User successfully logged out';
        $response = ResponseUtil::getResponseArray(null, $message, true);
        return response($response, 200);
    }

    public function forgot(Request $request)
    {
        $passcode = random_int(100000, 999999);

        $user = User::where('email', $request->email)->first();
        $logo  =  URL::to('/') . '/uploads/email/Logo-machinan.png';
        $reset_image  =  URL::to('/') . '/uploads/email/reset-pwd.png';

        if ($user) {
            $user->passcode = $passcode;
            $user->save();

            $details = [
                'subject' => 'Forgot Password',
                'email' => $request->email,
                'token' => $passcode,
                'logo' =>  $logo,
                'reset_image' =>  $reset_image,
            ];

            // \App\Jobs\ResetPasswordJob::dispatch($details);


            $response = ResponseUtil::getResponseArray(null, 101, 'We have sent you a password reset OPT on your email.');
            return response()->json($response);
        } else {
            throw new GeneralException('Account not found');
        }
    }

    public function newPassword(Request $request)
    {

        $user = User::where([['passcode', $request->token], ['email', $request->email]])->first();

        if ($user) {
            $passcode = random_int(100000, 999999);

            $updated = $user->update([
                'password' => Hash::make($request->password),
                'passcode' => $passcode
            ]);

            if ($updated) {

                $response = ResponseUtil::getResponseArray(null, 101, 'Password has been updated successfully');
                return response()->json($response);
            }

            throw new GeneralException('Sorry! we could not update your password.');
        }

        throw new GeneralException('Invalid OTP provided');
    }
}
