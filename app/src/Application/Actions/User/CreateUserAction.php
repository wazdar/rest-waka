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

class CreateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    protected function action(): Response
    {
        $post_data = $this->getPostRequestBodyAsArray();
        $validator = new UserValidator();
        $validator->validate($post_data, [
            'firstname' => v::noWhitespace()->notEmpty()->alpha(),
            'lastname' => v::noWhitespace()->notEmpty()->alpha(),
            'pesel' => v::notEmpty()->pesel()->uniqueEmail($this->userRepository),
        ]);
        if ($validator->failed()) {
            return $this->respondWithData($validator->getErrors(), 400);
        }

        $user = new User(null,$post_data['firstname'], $post_data['lastname'], $post_data['pesel'], null, 0);

        $this->userRepository->createUser($user);

        $this->logger->info("User has benn Created");

        return $this->respondWithData($post_data, 201);
    }
}
