<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\AuthResquest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
        public function Auth(AuthResquest $request): JsonResponse
        {
            $user = User::where('email',$request->email)->first();
            if (!$user || !Hash::check($request->password,$user->password)) {
                throw ValidationException::withMessages([
                    'Validation Error' => 'Invalid Credentials'
                ]);
            }

            $token = $user->createToken($user->name.'_'.Carbon::now());

            return response()->json([
                'token' => $token->plainTextToken
            ]);
        }
}
