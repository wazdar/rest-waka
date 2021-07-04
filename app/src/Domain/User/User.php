<?php
declare(strict_types=1);

namespace App\Domain\User;

use Cassandra\Date;
use DateTime;
use JsonSerializable;

class User implements JsonSerializable
{

    public const USER_CONFIRMED = 'confirmed';
    public const USER_CONFIRMED_INT = 1;
    public const USER_PENDING = 'pending';
    public const USER_PENDING_INT = 0;

    /**
     * @var int|null
     */
    private ?int $id;


    /**
     * @var string
     */
    private string $firstname;

    /**
     * @var string
     */
    private string $lastname;

    /**
     * @var string
     */
    private string $pesel;


    /**
     * @var DateTime
     */
    private $created_at;

    /**
     * @var int
     */
    private int $status;

    /**
     * @param int|null $id
     * @param string $firstname
     * @param string $lastname
     * @param string $pesel
     * @param string|null $created_at
     * @param int $status
     */
    public function __construct(?int $id, string $firstname, string $lastname, string $pesel, ?string $created_at, int $status)
    {
        $this->id = $id;
        $this->lastname = ucfirst($lastname);
        $this->firstname = ucfirst($firstname);
        $this->pesel = $pesel;
        $this->created_at = $created_at;
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstName(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getPesel(): string
    {
        return $this->pesel;
    }

    /**
     * @param string $pesel
     */
    public function setPesel(string $pesel): void
    {
        $this->pesel = $pesel;
    }

    /**
     * @return DateTime|false|string
     */
    public function getCreatedAt()
    {
        return \date('d-m-Y', strtotime($this->created_at));
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        if ($this->status === self::USER_CONFIRMED_INT) {
            return self::USER_CONFIRMED;
        }
        return self::USER_PENDING;

    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        if ($status === self::USER_CONFIRMED) {
            $this->status = self::USER_CONFIRMED_INT;
        }
    }

    /**
     * @return int
     */
    public function getStatusInt(): int
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'pesel' => $this->pesel,
            'created_at' => $this->getCreatedAt(),
            'status' => $this->getStatus()
        ];
    }
}
