<?php
namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $userData): User;
    public function findByEmail(string $email): ?User;
    public function update(User $user, array $data): bool;
    public function authenticate(array $credentials): ?string;
    public function invalidateToken(): bool;
    public function getUserFromToken(): ?User;
}
