<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;

use App\Helpers\Main;

use App\Repositories\RoleRepository;

class RoleService
{ 
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    } 

    public function list()
    {
        $data = $this->roleRepository->dataTableRole();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_date', function ($row) {
                return Carbon::parse($row->created_date)->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-warning waves-effect waves-light">
                <i class="ti ti-eye cursor-pointer"></i>
                View
                </button>';
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    } 
}
