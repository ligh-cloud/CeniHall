<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function register(Request $request)
    {
        try {
            $result = $this->authService->register($request->all());

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $result['user'],
                'token' => $result['token']
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $result = $this->authService->login($request->only('email', 'password'));

            if (!$result) {
                return response()->json([
                    'error' => 'Invalid credentials'
                ], 401);
            }

            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $result['user'],
                'token' => $result['token']
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed'], 500);
        }
    }

    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $success = $this->authService->logout();

        if ($success) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['error' => 'Failed to logout'], 500);
    }

    /**
     * Get the authenticated user's information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $user = $this->authService->getAuthenticatedUser();

        if ($user) {
            return response()->json(['user' => $user]);
        }

        return response()->json(['error' => 'Token invalid or expired'], 401);
    }

    /**
     * Update user's profile information.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $this->authService->getAuthenticatedUser();

            if (!$user) {
                return response()->json(['error' => 'Token invalid or expired'], 401);
            }

            $success = $this->authService->updateProfile($user, $request->all());

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Profile update failed'], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = $this->authService->getAuthenticatedUser();

            if (!$user) {
                return response()->json(['error' => 'Token invalid or expired'], 401);
            }

            $success = $this->authService->changePassword($user, $request->all());

            return response()->json(['message' => 'Password changed successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Password change failed'], 500);
        }
    }
}
