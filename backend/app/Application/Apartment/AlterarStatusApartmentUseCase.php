<?php

namespace App\Application\Apartment;

use App\Domain\Apartment\ApartmentRepositoryInterface;

class AlterarStatusApartmentUseCase
{
    public function __construct(
        private ApartmentRepositoryInterface $repository
    ) {
    }

    public function execute(int $id, string $status): bool
    {
        return $this->repository->atualizarStatus($id, $status);
    }
}