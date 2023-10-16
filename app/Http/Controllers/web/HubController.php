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
            $data = $this->hubRepository->dataTableHub();

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
