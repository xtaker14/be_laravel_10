<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InboundDetailExport;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\InboundTypeRepositoryInterface;
use App\Interfaces\InboundRepositoryInterface;

class ReportingController extends Controller
{
    private HubRepositoryInterface $hubRepository;
    private InboundTypeRepositoryInterface $inboundTypeRepository;
    private InboundRepositoryInterface $inboundRepository;

    public function __construct(HubRepositoryInterface $hubRepository, InboundTypeRepositoryInterface $inboundTypeRepository, InboundRepositoryInterface $inboundRepository)
    {
        $this->hubRepository = $hubRepository;
        $this->inboundTypeRepository = $inboundTypeRepository;
        $this->inboundRepository = $inboundRepository;
    }

    public function inbound()
    {
        $hubs = $this->hubRepository->getAllHub();
        $types = $this->inboundTypeRepository->getAllInboundType();

        return view('content.report.inbound', compact('hubs','types'));
    }

    public function inboundDetail(Request $request)
    {
        $date = $request->input('date'); 
        $hub = $request->input('hub'); 
        $type = $request->input('type'); 

        $filter = [
            'date' => $date,
            'hub' => $hub,
            'type' => $type
        ];

        $data = $this->inboundRepository->reportInboundDetail($filter);

        $export = new InboundDetailExport($data);

        $name = 'reporting_inbound_detail_'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }
}
