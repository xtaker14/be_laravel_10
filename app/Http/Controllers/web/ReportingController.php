<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InboundDetailExport;
use App\Exports\TransferExport;
use App\Interfaces\TransferRepositoryInterface;
use App\Exports\WaybillTransactionExport;
use App\Exports\WaybillHistoryExport;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\InboundTypeRepositoryInterface;
use App\Interfaces\InboundRepositoryInterface;
use App\Interfaces\StatusRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;

class ReportingController extends Controller
{
    private HubRepositoryInterface $hubRepository;
    private InboundTypeRepositoryInterface $inboundTypeRepository;
    private InboundRepositoryInterface $inboundRepository;
    private TransferRepositoryInterface $transferRepository;
    private StatusRepositoryInterface $statusRepository;
    private PackageRepositoryInterface $packageRepository;

    public function __construct(HubRepositoryInterface $hubRepository, InboundTypeRepositoryInterface $inboundTypeRepository, InboundRepositoryInterface $inboundRepository, StatusRepositoryInterface $statusRepository, PackageRepositoryInterface $packageRepository, TransferRepositoryInterface $transferRepository)
    {
        $this->hubRepository = $hubRepository;
        $this->inboundTypeRepository = $inboundTypeRepository;
        $this->inboundRepository = $inboundRepository;
        $this->transferRepository = $transferRepository;
        $this->statusRepository = $statusRepository;
        $this->packageRepository = $packageRepository;
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
    }
  
    public function waybill()
    {
        $hubs = $this->hubRepository->getAllHub();
        $status = $this->statusRepository->getStatusByGroup('package');

        return view('content.report.waybill', compact('hubs','status'));
    }

    public function waybillTransaction(Request $request)
    {
        $date = $request->input('date'); 
        $hub = $request->input('hub'); 
        $status = $request->input('status'); 
        $payment = $request->input('payment'); 

        $filter = [
            'date' => $date,
            'hub' => $hub,
            'status' => $status,
            'payment' => $payment
        ];

        $data = $this->packageRepository->reportWaybillTransaction($filter);

        $export = new WaybillTransactionExport($data);

        $name = 'reporting_waybill_transaction_'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }

    public function waybillHistory(Request $request)
    {
        $date = $request->input('date'); 
        $hub = $request->input('hub'); 
        $status = $request->input('status'); 
        $payment = $request->input('payment'); 

        $filter = [
            'date' => $date,
            'hub' => $hub,
            'status' => $status,
            'payment' => $payment
        ];

        $data = $this->packageRepository->reportWaybillHistory($filter);

        $export = new WaybillHistoryExport($data);

        $name = 'reporting_waybill_history_'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }
}
