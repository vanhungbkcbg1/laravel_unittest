<?php

namespace App\Services;

use App\Repositories\ISymbolPriceRepository;

class SymbolPriceService
{
    private $repo;
    /** @var ProcessHistoryService */
    private $processService;

    public function __construct(ISymbolPriceRepository $repo, ProcessHistoryService $processHistoryService)
    {
        $this->repo = $repo;
        $this->processService = $processHistoryService;
    }

    public function sendMail()
    {
        return true;
    }

    public function deleteOldData()
    {
        $date = $this->processService->getDateLastFiftyDay();
        if($date){
            $this->repo->deleteOldData($date);
        }
    }
}
