<?php

namespace App\Http\Controllers\web;

use App\Exports\CodReportExport;
use App\Exports\CourierperformanceReportExport;
use App\Exports\DeliveryrecordReportExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InboundDetailExport;
use App\Exports\TransferExport;
use App\Interfaces\TransferRepositoryInterface;
use App\Exports\WaybillTransactionExport;
use App\Exports\WaybillHistoryExport;
use App\Interfaces\CourierRepositoryInterface;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\InboundTypeRepositoryInterface;
use App\Interfaces\InboundRepositoryInterface;
use App\Interfaces\StatusRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use App\Interfaces\ReconcileRepositoryInterface;
use App\Interfaces\RoutingRepositoryInterface;
use App\Repositories\CourierRepository;

class ReportingController extends Controller
{
    private HubRepositoryInterface $hubRepository;
    private InboundTypeRepositoryInterface $inboundTypeRepository;
    private InboundRepositoryInterface $inboundRepository;
    private TransferRepositoryInterface $transferRepository;
    private StatusRepositoryInterface $statusRepository;
    private PackageRepositoryInterface $packageRepository;
    private CourierRepositoryInterface $courierRepository;
    private ReconcileRepositoryInterface $reconcileRepository;
    private RoutingRepositoryInterface $routingRepository;

    public function __construct(HubRepositoryInterface $hubRepository, InboundTypeRepositoryInterface $inboundTypeRepository, InboundRepositoryInterface $inboundRepository, StatusRepositoryInterface $statusRepository, PackageRepositoryInterface $packageRepository, TransferRepositoryInterface $transferRepository, CourierRepositoryInterface $courierRepository, ReconcileRepositoryInterface $reconcileRepository, RoutingRepositoryInterface $routingRepository)
    {
        $this->hubRepository = $hubRepository;
        $this->inboundTypeRepository = $inboundTypeRepository;
        $this->inboundRepository = $inboundRepository;
        $this->transferRepository = $transferRepository;
        $this->statusRepository = $statusRepository;
        $this->packageRepository = $packageRepository;
        $this->courierRepository = $courierRepository;
        $this->reconcileRepository = $reconcileRepository;
        $this->routingRepository = $routingRepository;
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

    public function deliveryrecordModule(Request $request)
    {
        $hubs = $this->hubRepository->getUsersHub();
        $status = $this->statusRepository->getStatusByGroup('routing');
        $courier = $this->courierRepository->getCouriers();

        return view('content.report.delivery-record', compact('hubs', 'status', 'courier'));
    }

    public function detailrecordReport(Request $request)
    {
        $date    = $request->input('date');
        $hub     = $request->input('hub');
        $status  = $request->input('status');
        $courier = $request->input('courier');

        $filter = [
            'date'    => $date,
            'hub'     => $hub,
            'status'  => $status,
            'courier' => $courier
        ];

        $data = $this->routingRepository->reportingdetailRecord($filter);
        $export = new DeliveryrecordReportExport($data);

        $name = 'reporting_delivery_record_'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }

    public function courierperfReport(Request $request)
    {
        $date = $request->input('date');
        $hub  = $request->input('hub');

        $filter = [
            'date' => $date,
            'hub'  => $hub
        ];

        $data = $this->courierRepository->courierPerformance($filter);

        $export = new CourierperformanceReportExport($data);

        $name = 'reporting_courier_performance_'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }

    public function codReport(Request $request)
    {
        $hubs = $this->hubRepository->getUsersHub();
        $courier = $this->courierRepository->getCourierHub($hubs[0]->hub_id);

        return view('content.report.cod-collection', compact('hubs', 'courier'));
    }

    public function coddetailReport(Request $request)
    {
        $date    = $request->input('date');
        $hub     = $request->input('hub');
        $courier = $request->input('courier');

        $filter = [
            'date'    => $date,
            'hub'     => $hub,
            'courier' => $courier
        ];

        $data = $this->reconcileRepository->reportingCod($filter);
        $export = new CodReportExport($data);

        $name = 'reporting_cod_detail_'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }

    public function codreportSummary()
    {}

    public function codreportDetail(Request $request)
    {
        $date    = $request->input('date');
        $hub     = $request->input('hub');
        $courier = $request->input('courier');

        $filter = [
            'date'    => $date,
            'hub'     => $hub,
            'courier' => $courier
        ];

        $data = $this->reconcileRepository->reportingCod($filter);

        $export = new CodReportExport($data);

        $name = 'reporting_cod_detail'.time().'_'.Auth::user()->users_id.'.xlsx';

        return Excel::download($export, $name);
    }
}
