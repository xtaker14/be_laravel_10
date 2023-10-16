<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\VendorRepositoryInterface;
use DataTables;

class VendorController extends Controller
{

    private VendorRepositoryInterface $vendorRepository;

    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->vendorRepository->dataTableVendor();

            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function($row){
                $label = $row->is_active == 1 ? 'success' : 'danger';
                return '<span class="badge bg-label-'.$label.'">'.ucwords($row->status).'</span>';
            })
            ->editColumn('total_courier', function($row){
                return $row->total_courier == "" ? 0 : $row->total_courier;
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

        return view('content.configuration.vendor.index');
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
