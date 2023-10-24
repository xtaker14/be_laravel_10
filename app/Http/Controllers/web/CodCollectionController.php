<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\CourierRepositoryInterface;
use App\Interfaces\RoutingRepositoryInterface;
use Illuminate\Http\Request;

class CodCollectionController extends Controller
{
    private CourierRepositoryInterface $courierRepository;
    private RoutingRepositoryInterface $routingRepository;

    public function __construct(CourierRepositoryInterface $courierRepository, RoutingRepositoryInterface $routingRepository)
    {
        $this->courierRepository = $courierRepository;
        $this->routingRepository = $routingRepository;
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
        } catch (\Exception $e) {
            return redirect()->route('cod-collection.index')->with('error',$e->getMessage());
        }

        return view('content.cod-collection.index', compact('couriers','routing'));
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
        //
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
}
