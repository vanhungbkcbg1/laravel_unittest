<?php

namespace App\Services;

use App\Repositories\IProcessHistoryRepository;
use App\Repositories\ISymbolPriceRepository;

class ProcessHistoryService
{
    private $repo;

    public function __construct(IProcessHistoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getDateLastFiftyDay()
    {
        return $this->repo->getLastFiftyDay();
    }
}
