<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Package;
use App\Models\PackageApi;

class PushOrderToWMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // retry if error
    public $tries = 3;

    // maximum time allowed for 1x job (milliseconds)
    public $timeout = 120;

    protected $package;

    /**
     * Create a new job instance.
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // sleep(5); // test delay 5 seconds

        // $res = new \App\Helpers\ResponseFormatter;
        $PackageService = new \App\Services\PackageService(false);

        $params_insert = [
            'package_id' => $this->package->package_id,
            'action' => PackageApi::ACTION_WMS['post_tracking'],
        ];
        $params_where = $params_insert;
        $params_insert['status'] = PackageApi::PROCESSED;

        \App\Helpers\Main::setCreatedModifiedVal(false, $params_insert);
        PackageApi::insertOrIgnore([$params_insert]); 
        
        // $package_status = $this->package->status;
        // $package_status_code = $package_status->code;
        // $package_status_name = $package_status->name;

        // $is_cod = false;
        // if ($this->package->cod_price > 0) {
        //     $is_cod = true;
        // }

        $package_api = PackageApi::where($params_where)
            ->where(function ($q) {
                $q->where('status', PackageApi::PROCESSED)
                    ->orWhere('status', PackageApi::FAILED);
            })->first();

        $post_order_to_wms = $PackageService->postOrderToWMS([
            'reference_number' => $this->package->reference_number,
            'tracking_number' => $this->package->tracking_number,
        ]);

        if ($post_order_to_wms['res'] == 'error') {
            // $res_failed = [
            //     'tracking_number' => $this->package->tracking_number,
            //     'reference_number' => $this->package->reference_number,
            //     'name' => $this->package->recipient_name,
            //     'is_cod' => $is_cod,
            //     'status_code' => $package_status_code,
            //     'status_name' => $package_status_name,
            //     'date' => $this->package->created_date,
            //     'errors' => [
            //         'status_code' => $post_order_to_wms['status_code'],
            //         'trace_code' => $res::traceCode($post_order_to_wms['trace_code']),
            //         'message' => $post_order_to_wms['msg'],
            //     ],
            // ];

            $package_api->status = PackageApi::FAILED;
            $package_api->message = $post_order_to_wms['trace_code'] . ' : ' . $post_order_to_wms['msg'];
            \App\Helpers\Main::setCreatedModifiedVal(true, $routing, 'modified');
            $package_api->save();
        }else {
            // $res_success = [
            //     'tracking_number' => $this->package->tracking_number,
            //     'reference_number' => $this->package->reference_number,
            //     'name' => $this->package->recipient_name,
            //     'is_cod' => $is_cod,
            //     'status_code' => $package_status_code,
            //     'status_name' => $package_status_name,
            //     'date' => $this->package->created_date,
            // ];

            $package_api->status = PackageApi::COMPLETED;
            $package_api->message = null;
            \App\Helpers\Main::setCreatedModifiedVal(true, $routing, 'modified');
            $package_api->save();
        }
    }

    public function failed(\Exception $exception)
    {
        \Illuminate\Support\Facades\Log::error('Job failed', ['job' => $this->job->getName(), 'exception' => $exception->getMessage()]);
    }

    public function retryUntil()
    {
        // Job akan diulang sampai pukul 2 pagi pada hari yang sama
        // return now()->setTime(2, 0)->endOfDay();

        // Job akan diulang sampai 30 menit sesudah pembuatan order
        // return \Carbon\Carbon::parse($this->order->created_at)->addMinutes(30);

        // Job akan diulang sampai 10 menit sesudah job di jalankan
        return now()->addMinutes(10);
    }
}
