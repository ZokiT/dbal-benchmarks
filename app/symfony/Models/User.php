<?php

namespace App\symfony\Models;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Exception;
use Doctrine\ORM\Mapping as ORM;


#[Entity]
#[Table(name: 'users')]
class User
{
    use \App\User;

    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(name: 'user_id', type: 'bigint', nullable: false)]
    protected int|null $id = null;

    #[Column(type: 'string', length: 255, nullable: false)]
    protected string $username;

    #[Column(type: 'string', length: 255, unique: true, nullable: false)]
    protected string $email;

    #[Column(name: 'registration_date', type: 'datetime', nullable: false)]
    protected DateTimeInterface $registrationDate;

    #[Column(name: 'is_active', type: 'boolean', nullable: false, options: ['default' => true])]
    protected bool $isActive;

    #[Column(name: 'birth_date', type: 'date', nullable: false)]
    protected DateTimeInterface $birthDate;

    #[Column(name: 'created_at', type: 'datetime')]
    protected DateTimeInterface $createdAt;

    #[Column(name: 'updated_at', type: 'datetime')]
    protected DateTimeInterface $updatedAt;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    protected Collection $orders;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class)]
    protected Collection $addresses;

    /**
     * @param string $username
     * @param string $email
     * @param string $birth_date
     * @param string $registration_date
     * @param bool $is_active
     * @param string $created_at
     * @param string $updated_at
     * @param int|null $id
     * @throws Exception
     *
     */
    public function __construct(
        string $username,
        string $email,
        string $birth_date,
        string $registration_date,
        bool $is_active,
        string $created_at,
        string $updated_at,
        ?int $id
    )
    {
        $this->username         = $username;
        $this->email            = $email;
        $this->birthDate        = new \DateTime($birth_date);
        $this->registrationDate = new \DateTime($registration_date);
        $this->isActive         = $is_active;
        $this->createdAt        = new \DateTime($created_at);
        $this->updatedAt        = new \DateTime($updated_at);
        $this->id               = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return DateTimeInterface
     */
    public function getBirthDate(): DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @param DateTimeInterface $birth_date
     */
    public function setBirthDate(DateTimeInterface $birth_date): void
    {
        $this->birthDate = $birth_date;
    }

    /**
     * @return DateTimeInterface
     */
    public function getRegistrationDate(): DateTimeInterface
    {
        return $this->registrationDate;
    }

    /**
     * @param DateTimeInterface $registration_date
     */
    public function setRegistrationDate(DateTimeInterface $registration_date): void
    {
        $this->registrationDate = $registration_date;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $is_active
     */
    public function setIsActive(bool $is_active): void
    {
        $this->isActive = $is_active;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $created_at
     */
    public function setCreatedAt(DateTimeInterface $created_at): void
    {
        $this->createdAt = $created_at;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updated_at
     */
    public function setUpdatedAt(DateTimeInterface $updated_at): void
    {
        $this->updatedAt = $updated_at;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Collection $orders
     */
    public function setOrders(Collection $orders): void
    {
        $this->orders = $orders;
    }

    /**
     * @return Collection
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * @param Collection $addresses
     */
    public function setAddresses(Collection $addresses): void
    {
        $this->addresses = $addresses;
    }


}