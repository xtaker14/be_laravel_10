<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use App\Interfaces\AdjustmentRepositoryInterface;
use App\Interfaces\StatusRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdjustmentController extends Controller
{
    private HubRepositoryInterface $hubRepository;
    private PackageRepositoryInterface $packageRepository;
    private AdjustmentRepositoryInterface $adjustmentRepository;
    private StatusRepositoryInterface $statusRepository;

    public function __construct(HubRepositoryInterface $hubRepository, PackageRepositoryInterface $packageRepository, AdjustmentRepositoryInterface $adjustmentRepository, StatusRepositoryInterface $statusRepository)
    {
        $this->hubRepository = $hubRepository;
        $this->packageRepository = $packageRepository;
        $this->adjustmentRepository = $adjustmentRepository;
        $this->statusRepository = $statusRepository;
    }

    public function masterWaybill(Request $request)
    {
        $hubs = $this->hubRepository->getAllHub();

        $date = $request->input('date');
        $hub = $request->input('origin_filter');

        $adjustments = $this->adjustmentRepository->getAdjustmentByType('REJECT_MASTER_WAYBILL', ['date' => $date, 'hub' => $hub]);

        return view('content.adjustment.master-waybill', compact('date','hubs','adjustments'));
    }

    public function masterWaybillInfo(Request $request)
    {
        $response = [];
        $code = $request->input('code');

        $check = $this->adjustmentRepository->getAdjustmentByCode($code);
        if ($check) {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = "Master Waybill sudah di reject";
        } else {
            $master = $this->packageRepository->getMasterPackageInformation($code);

            if ($master) {
                $data['master_waybill_id'] = $master->master_waybill_id;
                $data['code'] = $master->code;
                $data['created_date'] = Carbon::parse($master->created_date)->format('d/m/Y H:i');
                $data['created_by'] = $master->created_by;
                $data['total_waybill'] = $master->package()->count();

                $response['success'] = true; 
                $response['data'] = $data;
                $response['error'] = "";
            } else {
                $response['success'] = false; 
                $response['data'] = [];
                $response['error'] = "Master Waybill tidak di temukan";
            }
        }

        return response()->json($response);
    }

    public function masterWaybillStore(Request $request)
    {
        $master_waybill_id = $request->input('id');
        $reason = $request->input('reason');
        $remark = $request->input('remark');

        $status_reject = $this->statusRepository->getStatusByCode('REJECTED');
        if ($status_reject) {
            $master = $this->packageRepository->getMasterPackageById($master_waybill_id);
            $update_status = $this->packageRepository->rejectMasterPackage($master_waybill_id);

            if ($update_status) {
                $data['code'] = $master->code;
                $data['type'] = 'REJECT_MASTER_WAYBILL';
                $data['status_from'] = $status_reject->status_id;
                $data['status_to'] = $status_reject->status_id;
                $data['reason'] = $reason;
                $data['remark'] = $remark;
                $data['created_by'] = Auth::user()->full_name;
                $data['modified_by'] = Auth::user()->full_name;

                $adjustment = $this->adjustmentRepository->createAdjustment($data);
                
                return redirect()->route('adjustment.master-waybill')->with('success','Success reject master waybill');
            } else {
                return redirect()->route('adjustment.master-waybill')->with('failed','Failed reject master waybill');
            }
        } else {
            return redirect()->route('adjustment.master-waybill')->with('failed','Status Reject not found');
        }
    }

    public function singleWaybill(Request $request)
    {
        $hubs = $this->hubRepository->getAllHub();

        $date = $request->input('date');
        $hub = $request->input('origin_filter');

        $adjustments = $this->adjustmentRepository->getAdjustmentByType('REJECT_SINGLE_WAYBILL', ['date' => $date, 'hub' => $hub]);

        return view('content.adjustment.single-waybill', compact('hubs', 'date', 'adjustments'));
    }

    public function singleWaybillInfo(Request $request)
    {
        $response = [];
        $code = $request->input('code');

        $package = $this->packageRepository->getPackageInformation($code);

        if ($package) {
            if (!in_array(strtoupper($package['status_code']), ['ENTRY'])) {
                $response['success'] = false; 
                $response['data'] = [];
                $response['error'] = "Only Reject Waybil Entry Status. Current waybill status is ".$package['status_name'];
            } else {
                $response['success'] = true; 
                $response['data'] = $package;
                $response['error'] = "";
            }
        } else {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = "Waybill tidak di temukan";
        }

        return response()->json($response);
    }

    public function singleWaybillStore(Request $request)
    {
        $package_id = $request->input('id');
        $reason = $request->input('reason');
        $remark = $request->input('remark');

        $status_reject = $this->statusRepository->getStatusByCode('REJECTED');
        if ($status_reject) {
            $package = $this->packageRepository->getPackageById($package_id);
            if (in_array($package->status->code, ['ENTRY'])) {
                $update_status = $this->packageRepository->updateStatusPackage($package_id, 'REJECTED');

                if ($update_status) {
                    $data['code'] = $package->tracking_number;
                    $data['type'] = 'REJECT_SINGLE_WAYBILL';
                    $data['status_from'] = $package->status_id;
                    $data['status_to'] = $status_reject->status_id;
                    $data['reason'] = $reason;
                    $data['remark'] = $remark;
                    $data['created_by'] = Auth::user()->full_name;
                    $data['modified_by'] = Auth::user()->full_name;
    
                    $adjustment = $this->adjustmentRepository->createAdjustment($data);
                    
                    return redirect()->route('adjustment.single-waybill')->with('success','Success reject waybill');
                } else {
                    return redirect()->route('adjustment.single-waybill')->with('failed','Failed reject waybill');
                }
            } else {
                return redirect()->route('adjustment.single-waybill')->with('failed','Only can reject waybill entry status');
            }
        } else {
            return redirect()->route('adjustment.single-waybill')->with('failed','Status Reject not found');
        }
    }

    public function deliveryProcess(Request $request)
    {
        $hubs = $this->hubRepository->getAllHub();
        $statuses = $this->statusRepository->getStatusByGroup('package');

        $date = $request->input('date');
        $hub = $request->input('origin_filter');

        $adjustments = $this->adjustmentRepository->getAdjustmentByType('DELIVERY_PROCESS', ['date' => $date, 'hub' => $hub]);

        return view('content.adjustment.delivery-process', compact('hubs', 'statuses', 'date', 'adjustments'));
    }

    public function deliveryProcessInfo(Request $request)
    {
        $response = [];
        $code = $request->input('code');

        $package = $this->packageRepository->getPackageInformation($code);

        if ($package) {
            if (in_array(strtoupper($package['status_code']), ['REJECTED'])) {
                $response['success'] = false; 
                $response['data'] = [];
                $response['error'] = "Waybill berstatus ".$package['status_name'];
            } else {
                $response['success'] = true; 
                $response['data'] = $package;
                $response['error'] = "";
            }
        } else {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = "Waybill tidak di temukan";
        }

        return response()->json($response);
    }

    public function deliveryProcessStore(Request $request)
    {
        $package_id = $request->input('id');
        $information = $request->input('information');
        $note = $request->input('note');
        $esignature = $request->file('e-signature');
        $photo = $request->file('photo');
        $status_from = $request->input('status_from');
        $status_change = $request->input('status_change');

        if ($status_from == $status_change) {
            return redirect()->route('adjustment.delivery-process')->with('failed','Failed update waybill to same status');
        } elseif (in_array($status_change,['ROUTING','ONDELIVERY'])) {
            return redirect()->route('adjustment.delivery-process')->with('failed','Can not update waybill to status '.$status_change);
        } else {
            $package = $this->packageRepository->getPackageById($package_id);

            $delivered = [];
            if ($status_change == 'DELIVERED') {
                $delivered['information'] = $information; 
                $delivered['notes'] = $note;
                $delivered['accept_cod'] = $package->cod_amount > 0 ? 'yes' : 'no'; 
                $delivered['esignature'] = $esignature;
                $delivered['photo'] = $photo;
            }

            $update_status = $this->packageRepository->updateStatusPackage($package_id, $status_change, $delivered);

            if ($update_status) {
                $status_change_get = $this->statusRepository->getStatusByCode($status_change);

                $data['code'] = $package->tracking_number;
                $data['type'] = 'DELIVERY_PROCESS';
                $data['status_from'] = $package->status_id;
                $data['status_to'] = $status_change_get->status_id;
                $data['reason'] = '-';
                $data['remark'] = '-';
                $data['created_by'] = Auth::user()->full_name;
                $data['modified_by'] = Auth::user()->full_name;

                $adjustment = $this->adjustmentRepository->createAdjustment($data);
                
                return redirect()->route('adjustment.delivery-process')->with('success','Success update delivery process');
            } else {
                return redirect()->route('adjustment.delivery-process')->with('failed','Failed update delivery process');
            }
        }
    }
}
