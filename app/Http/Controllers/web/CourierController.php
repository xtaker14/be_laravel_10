<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CourierRepositoryInterface;
use DataTables;

class CourierController extends Controller
{
    private CourierRepositoryInterface $courierRepository;

    public function __construct(CourierRepositoryInterface $courierRepository)
    {
        $this->courierRepository = $courierRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->courierRepository->dataTableCourier();

            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function($row){
                $label = $row->is_active == 1 ? 'success' : 'danger';
                return '<span class="badge bg-label-'.$label.'">'.ucwords($row->status).'</span>';
            })
            ->addColumn('action', function($row){
                $btn = '<button type="button" class="btn btn-warning waves-effect waves-light">
                <i class="ti ti-eye cursor-pointer"></i>
                View
                </button>';
                return $btn;
            })
            ->rawColumns(['status','action'])
            ->make(true);
        }

        return view('content.configuration.courier.index');
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

    /**
     * Get routing by courier id
     */
    public function getRouting(Request $request, string $id)
    {
        $filter = [];
        $response = [];

        try {
            $routing = $this->courierRepository->getRoutingById($id, $filter);

            if ($routing) {
                $response['success'] = true; 
                $response['data'] = $routing;
                $response['error'] = "";
            } else {
                $response['success'] = false; 
                $response['data'] = [];
                $response['error'] = "Delivery Record Not Found";
            }
        } catch (\Exception $e) {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
