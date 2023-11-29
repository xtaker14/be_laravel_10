<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CourierRepositoryInterface;
use App\Imports\CourierImport;
use App\Exports\CourierResultExport;
use DataTables;
use Excel;
use Auth;

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

    public function templateImport()
    {
        $filename = 'template_import_courier.xlsx';
        $file = public_path('web-resource/files-upload/'. $filename);
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->download($file, $filename, $headers);
    }

    public function storeUpload(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'file' => 'required|mimes:xlsx|max:10240', // Adjust max file size as needed
            ]);

            $file = $request->file('file');

            $import = new CourierImport;

            $import->import($file, null, \Maatwebsite\Excel\Excel::XLSX);

            $result = $import->result();

            foreach ($import->failures() as $failure) {
                $row = $failure->row(); // row that went wrong
                $attribute = $failure->attribute(); // either heading key (if using heading row concern) or column index
                $errors = $failure->errors(); // Actual error messages from Laravel validator
                $values = $failure->values(); // The values of the row that has failed.

                if (!isset($result[$row])) {
                    unset($values['result']);
                    $result[$row] = $values;
                }

                if (isset($result[$row]['result'])) {
                    $result[$row]['result'] .= ';'.implode(';',$errors);
                } else {
                    $result[$row]['result'] = implode(';',$errors);
                }
            }

            $export = new CourierResultExport($result);
        
            $name_result = 'import_courier_'.time().'_'.Auth::user()->users_id.'_result.xlsx';

            return Excel::download($export, $name_result);
        } catch (\Exception $e) {
            report($e);

            return response()->json($e->getMessage(), 500);
        }
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
