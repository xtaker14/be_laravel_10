<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\CourierRepositoryInterface;
use App\Interfaces\ReconcileRepositoryInterface;
use App\Interfaces\RoutingRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use App\Interfaces\HubRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class CodCollectionController extends Controller
{
    private CourierRepositoryInterface $courierRepository;
    private RoutingRepositoryInterface $routingRepository;
    private ReconcileRepositoryInterface $reconcileRepository;
    private PackageRepositoryInterface $packageRepository;
    private HubRepositoryInterface $hubRepository;

    public function __construct(CourierRepositoryInterface $courierRepository, RoutingRepositoryInterface $routingRepository, ReconcileRepositoryInterface $reconcileRepository, PackageRepositoryInterface $packageRepository, HubRepositoryInterface $hubRepository)
    {
        $this->courierRepository = $courierRepository;
        $this->routingRepository = $routingRepository;
        $this->reconcileRepository = $reconcileRepository;
        $this->packageRepository = $packageRepository;
        $this->hubRepository = $hubRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $routing = [];
        $couriers = [];
        
        $hubs = $this->hubRepository->getAllHubByRole();

        try {
            $delivery_record = $request->get('delivery_record');

            if ($delivery_record != "") {
                $routing = $this->routingRepository->getRoutingByCode($delivery_record);

                if (!$routing) {
                    return redirect()->route('cod-collection.index')->with('error','Delivery Record Code Not Exist');
                }
            }

            $couriers = $this->courierRepository->getAllCourier();

            $date = $request->input('date');
            if ($request->has('origin_filter')) {
                $hub = $request->input('origin_filter');
            } else {
                foreach ($hubs as $key => $value) {
                    $hub[$key] = $key;
                }
            }
            

            $record = $this->reconcileRepository->getAllReconcileByDate($date, $hub);
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }

        return view('content.cod-collection.index', compact('couriers','routing','record', 'date', 'hubs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "";

        try {
            $delivery_record = $request->input('deliveryRecord');
            $deposit_amount = $request->input('depositAmount');

            //check delivery record exist by code
            $routing = $this->routingRepository->getRoutingByCode($delivery_record);
            if (count($routing)) {
                $routing_data = $routing['data'];

                //check routing never create reconcile
                $collection_check = $this->reconcileRepository->getReconcileByRouting($routing_data->routing_id);
                if (!$collection_check) {
                    // Remove non-numeric characters
                    $deposit_input = preg_replace("/[^0-9]/", "", $deposit_amount);
                    
                    $total_cod_delivered = $routing['value_cod_delivered'];
                    $total_cod_uncollected = $routing['value_cod_uncollected'];
                    $total_cod_actual = $routing['value_cod_total'];
                    $remaining_deposit = $this->reconcileRepository->getRemainingDeposit($routing_data->routing_id, $total_cod_actual) - $deposit_input;

                    //check deposit match with data
                    if ($deposit_input == $total_cod_uncollected) {
                        //save reconcile
                        $reconcileDetails = [
                            'routing_id' => $routing_data->routing_id,
                            'code' => $this->reconcileRepository->generateCode(),
                            'unique_number' => $this->generateUniqueNumber(30),
                            'total_deposit' => $total_cod_actual,
                            'actual_deposit' => $deposit_input,
                            'remaining_deposit' => $remaining_deposit
                        ];
                        $reconcile = $this->reconcileRepository->createOrUpdateReconcile($reconcileDetails);

                        if ($reconcile) {
                            //set routing to status collected
                            $update_routing = $this->routingRepository->updateStatusRouting($routing_data->routing_id, 'COLLECTED');
                        }
                    } else {
                        $message = 'Deposit amount not match!';
                    }
                } else {
                    $message = 'Delivery record have done cod collection, please refresh page and try again.';
                }
            } else {
                $message = 'Delivery record not found';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        if ($message == "") {
            $response['success'] = true; 
            $response['data'] = $reconcile;
            $response['error'] = "";
        } else {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createPdf(Request $request, $reconcileId, $type)
    {
        $data = $this->reconcileRepository->getReconcileById($reconcileId);
        $routing = $this->routingRepository->getRoutingByCode($data->routing->code);

        $download = false;
        if ($request->get('download') == 1) {
            $download = true;
        }

        if ($type == 'print') {
            // Set the paper size to A4
            PDF::setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true, 'isRemoteEnabled' => true]);
            PDF::setPaper('a4', 'portrait'); // 'a4' for A4 size

            $pdf = PDF::loadview('content._pdf.cod_collection_print',['data'=>$data, 'routing' => $routing]);

            $pdf->getDomPDF()->set_option('isPhpEnabled', true);
            $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);

            $name_pdf = 'print-cod-collection-'.$data->routing->code.'.pdf';
            if ($download) {
                return $pdf->download($name_pdf);
            } else {
                return $pdf->stream($name_pdf);
            }
        } elseif ($type == 'struct') {
            // Set the paper size to A4
            PDF::setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true, 'isRemoteEnabled' => true]);

            $pdf = PDF::loadview('content._pdf.cod_collection_struct',['data'=>$data, 'routing' => $routing]);

            $pdf->setPaper([0, 0, 226.772, 793.701], 'portrait');

            $pdf->getDomPDF()->set_option('isPhpEnabled', true);
            $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);

            $name_pdf = 'print-cod-collection-'.$data->routing->code.'.pdf';

            if ($download) {
                return $pdf->download($name_pdf);
            } else {
                return $pdf->stream($name_pdf);
            }
        } else {
            abort(404);
        }
    }

    /**
     * generate unique number for value reconcile.
     */
    function generateUniqueNumber($length = 30) {
        $prefix = uniqid(); // Generate a unique prefix
        $remainingLength = $length - strlen($prefix); // Calculate the remaining length
    
        // Generate a random number with the remaining length
        $randomNumber = '';
        for ($i = 0; $i < $remainingLength; $i++) {
            $randomNumber .= mt_rand(0, 9); // Generate a random digit
        }
    
        return $prefix . $randomNumber;
    }
}
