<?php

namespace App\laminas\Models;

class User
{
    use \App\User;

    public ?int $userId;

    protected string $username;

    protected string $email;

    protected string $registrationDate;

    protected bool $isActive;

    protected string $birthDate;

    protected string $createdAt;

    protected string $updatedAt;

    protected array $orders;

    protected array $addresses;

    /**
     * @param string $userId
     */
    public function setUserId(string $userId): void
    {
        $this->userId = (int)$userId;
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
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    /**
     * @param string $birth_date
     */
    public function setBirthDate(string $birth_date): void
    {
        $this->birthDate = $birth_date;
    }

    /**
     * @return string
     */
    public function getRegistrationDate(): string
    {
        return $this->registrationDate;
    }

    /**
     * @param string $registration_date
     */
    public function setRegistrationDate(string $registration_date): void
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
     * @param string|bool $is_active
     */
    public function setIsActive(string|bool $is_active): void
    {
        $this->isActive = $is_active === 't' || $is_active === true;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->createdAt = $created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt(string $updated_at): void
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
}