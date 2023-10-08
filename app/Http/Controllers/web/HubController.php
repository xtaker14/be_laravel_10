<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\HubRepositoryInterface;
use DataTables;

class HubController extends Controller
{
    private HubRepositoryInterface $hubRepository;

    public function __construct(HubRepositoryInterface $hubRepository)
    {
        $this->hubRepository = $hubRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->hubRepository->selectAllHub();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('city', function($row) {
                return $row->subdistrict->district->city->name;
            })
            ->addColumn('province', function($row) {
                return $row->subdistrict->district->city->province->name;
            })
            ->addColumn('total_district', function($row) {
                return rand(1,5);
            })
            ->addColumn('status', function($row){
                if ($row->is_active == 1) {
                    $btn = '<span class="badge bg-label-success">Active</span>';
                } else {
                    $btn = '<span class="badge bg-label-danger">Inactive</span>';
                }
                return $btn;
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

        return view('content.configuration.hub.index');
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
