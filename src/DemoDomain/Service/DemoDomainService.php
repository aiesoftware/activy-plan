<?php declare(strict_types=1);

namespace App\DemoDomain\Service;

use App\DemoDomain\Repository\DemoDomainRepositoryInterface;

class DemoDomainService
{
    private DemoDomainRepositoryInterface $demoRepository;

    public function __construct(DemoDomainRepositoryInterface $demoRepository)
    {
        $this->demoRepository = $demoRepository;
    }
}
