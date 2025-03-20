<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
protected $userRepository;

/**
* AuthService constructor.
*
* @param UserRepositoryInterface $userRepository
*/
public function __construct(UserRepositoryInterface $userRepository)
{
$this->userRepository = $userRepository;
}

/**
* Register a new user.
*
* @param array $userData
* @return array
* @throws ValidationException
*/
public function register(array $userData): array
{
$validator = Validator::make($userData, [
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8',
]);

if ($validator->fails()) {
throw new ValidationException($validator);
}

$user = $this->userRepository->create($userData);
$token = JWTAuth::fromUser($user);

return [
'user' => $user,
'token' => $token
];
}

/**
* Log the user in.
*
* @param array $credentials
* @return array|null
* @throws ValidationException
*/
public function login(array $credentials): ?array
{
$validator = Validator::make($credentials, [
'email' => 'required|string|email',
'password' => 'required|string',
]);

if ($validator->fails()) {
throw new ValidationException($validator);
}

$token = $this->userRepository->authenticate($credentials);

if (!$token) {
return null;
}

$user = $this->userRepository->getUserFromToken();

return [
'user' => $user,
'token' => $token
];
}

/**
* Log the user out.
*
* @return bool
*/
public function logout(): bool
{
return $this->userRepository->invalidateToken();
}

/**
* Get authenticated user.
*
* @return User|null
*/
public function getAuthenticatedUser(): ?User
{
return $this->userRepository->getUserFromToken();
}

/**
* Update user profile.
*
* @param User $user
* @param array $data
* @return bool
* @throws ValidationException
*/
public function updateProfile(User $user, array $data): bool
{
$validator = Validator::make($data, [
'name' => 'string|max:255',
'email' => 'string|email|max:255|unique:users,email,' . $user->id,
]);

if ($validator->fails()) {
throw new ValidationException($validator);
}

return $this->userRepository->update($user, $data);
}

/**
* Change user password.
*
* @param User $user
* @param array $data
* @return bool
* @throws ValidationException
*/
public function changePassword(User $user, array $data): bool
{
$validator = Validator::make($data, [
'current_password' => 'required|string',
'password' => 'required|string|min:8|confirmed',
]);

if ($validator->fails()) {
throw new ValidationException($validator);
}

if (!Hash::check($data['current_password'], $user->password)) {
throw ValidationException::withMessages([
'current_password' => ['The provided password does not match your current password.'],
]);
}

return $this->userRepository->update($user, [
'password' => Hash::make($data['password']),
]);
}
}
