<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\RegionRepositoryInterface;
use DataTables;

class RegionController extends Controller
{
    private RegionRepositoryInterface $regionRepository;

    public function __construct(RegionRepositoryInterface $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->regionRepository->selectAllSubdistrict();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('postal_code', function($row) {
                return rand('10000','99999');
            })
            ->addColumn('subdistrict', function($row) {
                return $row->name;
            })
            ->addColumn('district', function($row) {
                return $row->district->name;
            })
            ->addColumn('city', function($row) {
                return $row->district->city->name;
            })
            ->addColumn('province', function($row) {
                return $row->district->city->province->name;
            })
            ->addColumn('action', function($row){
                $btn = '<button type="button" class="btn btn-warning waves-effect waves-light">
                <i class="ti ti-eye cursor-pointer"></i>
                View
                </button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('content.configuration.region.index');
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
