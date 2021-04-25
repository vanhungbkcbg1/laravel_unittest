<?php

namespace App\Services;

use App\Repositories\ISymbolAnalyzedRepository;

class AnalyzedService
{
    private $repo;
    /** @var ProcessHistoryService */
    private $processService;

    public function __construct(ISymbolAnalyzedRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAllData(){
        $data = $this->repo->getAll();
        return array_map(function($item){
            return $item->symbol;
        },$data->all());
    }

}
