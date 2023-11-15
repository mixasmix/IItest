<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Manager;
use App\Repository\ManagerRepository;
use DateTimeImmutable;

class ManagerService
{
    public function __construct(private readonly ManagerRepository $managerRepository)
    {
    }

    public function create(
        string $firstName,
        string $lastName,
        DateTimeImmutable $birthdate
    ): Manager {
        $manager = new Manager();
        $manager->setFirstName($firstName);
        $manager->setLastName($lastName);
        $manager->setBirthdate($birthdate);

        return $this->managerRepository->add($manager);
    }
}
