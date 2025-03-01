<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class SocialLoginController extends Controller
{
    public function RedirectToProvider($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

     public function HandleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        dd($socialUser);
    }
    public function SocialLogin(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate the request parameters
        $request->validate([
            'token'    => 'required',
            'role'     => 'required|in:teacher,student',
            'provider' => 'required|in:google,facebook,apple',
        ]);

        try {
            $provider   = $request->provider;
            $role       = $request->role;
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);

            // Check if the social user data is retrieved successfully
            if ($socialUser) {
                // Check if the user exists in the database (either by email or social provider)
                $user = User::withTrashed()
                    ->where('email', $socialUser->getEmail())
                    ->orWhere(function ($query) use ($provider, $socialUser) {
                        $query->where('provider', $provider)
                            ->where('provider_id', $socialUser->getId());
                    })
                    ->first();

                // Check if the account is deleted (soft delete check)
                if (!empty($user->deleted_at)) {
                    return Helper::jsonResponse(false, 'Your account has been deleted.', 410);
                }

                // If user is found, proceed with login logic
                if ($user) {
                    // If the role is 'teacher', log the user in
                    if ($role === 'teacher') {
                        // If the email already exists and the role is not 'teacher', return an error
                        if ($user->role !== 'teacher') {
                            return Helper::jsonResponse(false, 'Email already taken with different role', 400);
                        }

                        Auth::login($user);
                        // Generate API token using 'api' guard
                        $token = auth('api')->login($user);
                        $tokenType = 'Bearer';
                        // Pass token and token_type inside the user object
                        return Helper::jsonResponse(true, 'Login successful', 200, [
                            'user' => [
                                'id'    => $user->id,
                                'name'  => $user->name,
                                'email' => $user->email,
                                'role'  => $user->role,
                                'avatar' => $user->avatar,
                                'token' => $token,
                                'token_type' => $tokenType,
                            ]
                        ]);
                    }

                    // If the email is already taken and role does not match, return an error
                    return Helper::jsonResponse(false, 'Email already taken with different role', 400);
                }

                // If the user is not found, check if it's a new user
                $isNewUser = false;
                if (!$user) {
                    // Generate a random password for the new user (because social login does not require password)
                    $password = Str::random(16);
                    // Create a new user in the database
                    $user = User::create([
                        'name'              => $socialUser->getName(),
                        'email'             => $socialUser->getEmail(),
                        'password'          => bcrypt($password),
                        'avatar'            => $socialUser->getAvatar(),
                        'provider'          => $provider,
                        'provider_id'       => $socialUser->getId(),
                        'role'              => $role,
                        'email_verified_at' => now(),
                    ]);
                    $isNewUser = true;
                }

                // If the user is new
                if ($isNewUser) {
                    // For 'student', send a verification email
                    if ($role === 'student') {
                        // Send verification email code here
                        return Helper::jsonResponse(true, 'Verification email sent', 200, [
                            'user' => [
                                'id'    => $user->id,
                                'name'  => $user->name,
                                'email' => $user->email,
                                'role'  => $user->role,
                                'avatar' => $user->avatar,
                                'token' => null,
                                'token_type' => null,
                            ]
                        ]);
                    }

                    // For 'teacher', create a new teacher and log them in
                    if ($role === 'teacher') {
                        $newUser = User::create([
                            'email' => $socialUser->getEmail(),
                            'name' => $socialUser->getName(),
                            'role' => 'teacher',
                            'password' => bcrypt(Str::random(16)),
                        ]);

                        // Log in the newly created teacher
                        Auth::login($newUser);
                        $token = auth('api')->login($newUser);
                        $tokenType = 'Bearer';
                        return Helper::jsonResponse(true, 'Teacher created and logged in', 200, [
                            'user' => [
                                'id'    => $newUser->id,
                                'name'  => $newUser->name,
                                'email' => $newUser->email,
                                'role'  => $newUser->role,
                                'avatar' => $newUser->avatar,
                                'token' => $token,
                                'token_type' => $tokenType,
                            ]
                        ]);
                    }
                }

                // Log in the user and return their data along with token in the response
                $token = auth('api')->login($user);
                $tokenType = 'Bearer';
                return Helper::jsonResponse(true, 'Login successful', 200, [
                    'user' => [
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                        'role'  => $user->role,
                        'avatar' => $user->avatar,
                        'token' => $token,
                        'token_type' => $tokenType,
                    ]
                ]);
            } else {
                return Helper::jsonResponse(false, 'Unauthorized', 401);
            }
        } catch (Exception $e) {
            // Catch any exceptions and return error response
            return Helper::jsonResponse(false, 'Something went wrong', 500, ['error' => $e->getMessage()]);
        }
    }


}
