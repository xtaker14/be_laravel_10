<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\CourierRepositoryInterface;
use App\Interfaces\ReconcileRepositoryInterface;
use App\Interfaces\RoutingRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CodCollectionController extends Controller
{
    private CourierRepositoryInterface $courierRepository;
    private RoutingRepositoryInterface $routingRepository;
    private ReconcileRepositoryInterface $reconcileRepository;
    private PackageRepositoryInterface $packageRepository;

    public function __construct(CourierRepositoryInterface $courierRepository, RoutingRepositoryInterface $routingRepository, ReconcileRepositoryInterface $reconcileRepository, PackageRepositoryInterface $packageRepository)
    {
        $this->courierRepository = $courierRepository;
        $this->routingRepository = $routingRepository;
        $this->reconcileRepository = $reconcileRepository;
        $this->packageRepository = $packageRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $routing = [];
        $couriers = [];

        try {
            $delivery_record = $request->get('delivery_record');

            if ($delivery_record != "") {
                $routing = $this->routingRepository->getRoutingByCode($delivery_record);

                if (!$routing) {
                    return redirect()->route('cod-collection.index')->with('error','Delivery Record Code Not Exist');
                }
            }

            $couriers = $this->courierRepository->getAllCourier();

            $date = "";
            if(isset($request->date))
            {
                $date = $request->date;
            }

            $record = $this->reconcileRepository->getAllReconcile($date);
            
        } catch (\Exception $e) {
            return redirect()->route('cod-collection.index')->with('error',$e->getMessage());
        }

        return view('content.cod-collection.index', compact('couriers','routing','record'));
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
                // Remove non-numeric characters
                $deposit_input = preg_replace("/[^0-9]/", "", $deposit_amount);

                $routing_data = $routing['data'];
                $total_cod_undelivered = $routing['value_cod_undelivered'];
                $total_cod_actual = $routing['value_cod_total'];
                $remaining_deposit = $this->reconcileRepository->getRemainingDeposit($routing_data->routing_id, $total_cod_actual) - $deposit_input;

                //check deposit match with data
                if ($deposit_input == $total_cod_undelivered) {
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
                        //set package to status collected
                        foreach ($routing['list_waybill'] as $waybill) {
                            $update_package = $this->packageRepository->updateStatusPackage($waybill->package_id, 'COLLECTED');

                            if (!$update_package) {
                                $message .= 'Failed Update Package '.$waybill->tracking_number.';';
                            }
                        }
                    }
                } else {
                    $message = 'Deposit amount not match!';
                }
            } else {
                $message = 'Delivery record not found';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        if ($message == "") {
            $response['success'] = true; 
            $response['data'] = [];
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
