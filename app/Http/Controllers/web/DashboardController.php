<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\HubRepositoryInterface;
use App\Interfaces\PackageRepositoryInterface;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private HubRepositoryInterface $hubRepository;
    private PackageRepositoryInterface $packageRepository;

    public function __construct(HubRepositoryInterface $hubRepository, PackageRepositoryInterface $packageRepository)
    {
        $this->hubRepository = $hubRepository;
        $this->packageRepository = $packageRepository;
    }

    public function index(Request $request)
    {
        $hubs = $this->hubRepository->getAllHub();

        return view('content.dashboard', compact('hubs'));
    }

    public function summary(Request $request)
    {
        try {
            $origin = $request->input('originFilter');
            $created = $request->input('createdFilter');

            if ($created != "All") {
                $explode_created = explode(" - ",$created);
                $created_start = Carbon::createFromFormat('m/d/Y', $explode_created[0])->toDateString();
                $created_end = Carbon::createFromFormat('m/d/Y', $explode_created[1])->toDateString();
            } else {
                $created_start = "All";
                $created_end = "All";
            }

            $createdFilter = [
                'created_start' => $created_start,
                'created_end' => $created_end,
            ];

            $summary = $this->packageRepository->summaryStatus($origin, $createdFilter);

            $response['success'] = true; 
            $response['data'] = $summary;
            $response['error'] = "";
        } catch (\Exception $e) {
            $response['success'] = false; 
            $response['data'] = [];
            $response['error'] = $e->getMessage();
        }

        return response()->json($response);
    }
}