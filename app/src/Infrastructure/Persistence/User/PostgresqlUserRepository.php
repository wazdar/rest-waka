<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use PDO;
use PDOException;

class PostgresqlUserRepository implements UserRepository
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param Container $container
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->db = $container->get(PDO::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM users");
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, User::class, [NULL, '', '', '', '', 0]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id= :id::int');
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, User::class, [NULL, '', '', '', '', 0]);
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();
            if (!$user instanceof User) {
                throw new UserNotFoundException();
            }
            return $user;
        } catch (PDOException | Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfPesel(string $pesel): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE pesel= :pesel');
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, User::class, [NULL, '', '', '', '', 0]);
            $stmt->execute([':pesel' => $pesel]);
            return $stmt->fetchAll();
        } catch (PDOException | Exception $e) {
            die($e->getMessage());
        }
    }

    public function createUser(User $user): User
    {
        try {
            $stmt = $this->db->prepare("
            INSERT INTO 
                users (firstname, lastname, pesel)
            VALUES 
            (
            :firstname,
            :lastname,
            :pesel
            )             
            ");
            $stmt->execute([
                ':firstname' => $user->getFirstName(),
                ':lastname' => $user->getLastName(),
                ':pesel' => $user->getPesel()
            ]);
            return $user;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @return User
     * @throws UserNotFoundException
     */
    public function updateUser(User $user): User
    {
        // Checking current user status
        try {
            $stmt = $this->db->prepare("
            UPDATE
                users
            SET 
                firstname = :firstName,
                lastname = :lastName,
                pesel = :pesel,
                status = :status
            WHERE 
                id = :id
            ");
            $stmt->execute([
                ':id' => $user->getId(),
                ':firstName' => $user->getFirstName(),
                ':lastName' => $user->getLastName(),
                ':pesel' => $user->getPesel(),
                ':status' => $user->getStatusInt()
            ]);
            return $user;

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param int $id
     */
    public function deleteUser(int $id): void
    {
        try {
            $stmt = $this->db->prepare("
            DELETE FROM
                users
            WHERE 
                id = :id
            ");
            $stmt->execute([
                ':id' => $id,
            ]);

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
