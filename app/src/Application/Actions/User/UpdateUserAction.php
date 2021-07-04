<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\UserValidator;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Factory;
use Respect\Validation\Validator as v;

Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace('App\\Domain\\User\\Validators\\Rules')
        ->withExceptionNamespace('App\\Domain\\User\\Validators\\Exceptions')
);

class UpdateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $post_data = $this->getPostRequestBodyAsArray();
        $user = $this->userRepository->findUserOfId($userId);

        $validator = new UserValidator();
        $validator->validate($post_data, [
            'firstname' => v::noWhitespace()->notEmpty()->alpha(),
            'lastname' => v::noWhitespace()->notEmpty()->alpha(),
            'pesel' => v::pesel()->uniqueEmail($this->userRepository),
            'status' => v::in([User::USER_CONFIRMED, User::USER_PENDING])
        ]);
        if ($validator->failed()) {
            return $this->respondWithData($validator->getErrors(), 400);
        }


        $user->setFirstName($post_data['firstname']);
        $user->setLastName($post_data['lastname']);
        $user->setPesel($post_data['pesel']);
        if ($user->getStatus() !== User::USER_CONFIRMED_INT && isset($post_data['status'])) {
            $user->setStatus($post_data['status']);
        }

        $this->userRepository->updateUser($user);

        $this->logger->info("User has benn updated - User::ID -> " . (string)$user->getId());

        return $this->respondWithData($user);
    }
}
