<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InboundDetailExport;
use App\Exports\TransferExport;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\InboundTypeRepositoryInterface;
use App\Interfaces\InboundRepositoryInterface;
use App\Interfaces\TransferRepositoryInterface;

class ReportingController extends Controller
{
    private HubRepositoryInterface $hubRepository;
    private InboundTypeRepositoryInterface $inboundTypeRepository;
    private InboundRepositoryInterface $inboundRepository;
    private TransferRepositoryInterface $transferRepository;

    public function __construct(HubRepositoryInterface $hubRepository, InboundTypeRepositoryInterface $inboundTypeRepository, InboundRepositoryInterface $inboundRepository, TransferRepositoryInterface $transferRepository)
    {
        $this->hubRepository = $hubRepository;
        $this->inboundTypeRepository = $inboundTypeRepository;
        $this->inboundRepository = $inboundRepository;
        $this->transferRepository = $transferRepository;
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

    public function transfer()
    {
        $hubs = $this->hubRepository->getAllHub();
        $types = $this->inboundTypeRepository->getAllInboundType();

        return view('content.report.transfer', compact('hubs','types'));
    }

    public function report_transfer(Request $request)
    {
        $date    = $request->input('date');
        $fromhub = $request->input('fromhub');
        $tohub   = $request->input('tohub');

        $filter = [
            'date'    => $date,
            'fromhub' => $fromhub,
            'tohub'   => $tohub
        ];

        $data = $this->transferRepository->reportTransfer($filter);

        $export = new TransferExport($data);

        $name = 'reporting_transfer_'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }
}
