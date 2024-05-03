<?php
declare(strict_types=1);

namespace App\Model;
class User
{
    public function __construct( //создает объект класс
        private ?int               $id,
        private ?string             $firstName,
        private ?string             $lastName,
        private ?string            $middleName,
        private ?string             $gender,
        private ?\DateTimeImmutable $birthDate,
        private ?string             $email,
        private ?string            $phone,
        private ?string            $avatarPath,
    )
    {
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }
}