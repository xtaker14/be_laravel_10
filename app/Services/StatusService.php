<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;

use App\Helpers\Main;

use App\Repositories\StatusRepository;

class StatusService
{
    private StatusRepository $statusRepository;

    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }  

    public function list()
    {
        $data = $this->statusRepository->dataTableStatus();

        return $data;
    } 
}
