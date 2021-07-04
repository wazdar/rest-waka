<?php

namespace App\Domain\User\Validators\Rules;



use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\PostgresqlUserRepository;
use DI\Container;
use Respect\Validation\Rules\AbstractRule;

class UniqueEmail extends AbstractRule
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($input): bool
    {
        $users = $this->userRepository->findUserOfPesel($input);

        return count($users) === 0;
    }
}