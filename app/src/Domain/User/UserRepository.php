<?php
declare(strict_types=1);

namespace App\Domain\User;

use phpDocumentor\Reflection\Types\Null_;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User|null
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): ?User;

    /**
     * @param string $pesel
     * @return User[]
     */
    public function findUserOfPesel(string $pesel): array;

    /**
     * @param User $user
     * @return User
     */
    public function createUser(User $user): User;

    /**
     * @param User $user
     * @return User
     */
    public function updateUser(User $user): User;

    /**
     * @param int $id
     */
    public function deleteUser(int $id): void;
}
