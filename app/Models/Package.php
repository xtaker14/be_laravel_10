<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $package_id
 * @property integer $client_id
 * @property integer $service_type_id
 * @property integer $hub_id
 * @property integer $status_id
 * @property integer $position_number
 * @property string $tracking_number
 * @property string $reference_number
 * @property string $request_pickup_date
 * @property string $merchant_name
 * @property string $pickup_name
 * @property string $pickup_phone
 * @property string $pickup_email
 * @property string $pickup_address
 * @property string $pickup_country
 * @property string $pickup_province
 * @property string $pickup_city
 * @property string $pickup_district
 * @property string $pickup_subdistrict
 * @property string $pickup_postal_code
 * @property string $pickup_notes
 * @property string $pickup_coordinate
 * @property string $recipient_name
 * @property string $recipient_phone
 * @property string $recipient_email
 * @property string $recipient_address
 * @property string $recipient_country
 * @property string $recipient_province
 * @property string $recipient_city
 * @property string $recipient_district
 * @property string $recipient_postal_code
 * @property string $recipient_notes
 * @property string $recipient_coordinate
 * @property float $package_price
 * @property float $is_insurance
 * @property float $shipping_price
 * @property float $cod_price
 * @property float $total_weight
 * @property integer $total_koli
 * @property string $volumetric
 * @property string $notes
 * @property string $created_via
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Inbounddetail[] $inbounddetails
 * @property Moving[] $movings
 * @property Outbound[] $outbounds
 * @property Hub $hub
 * @property Client $client
 * @property Servicetype $servicetype
 * @property PackageHistory[] $packagehistories
 * @property PackageApi[] $packageapies
 * @property PackageDelivery[] $packagedelivery
 * @property RoutingDetail[] $routingdetails
 * @property TransferDetail[] $transferdetails
 */
class Package extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'package';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'package_id';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_date';

    /**
     * @var array
     */
    protected $fillable = [
        'client_id', 
        'service_type_id', 
        'hub_id', 
        'status_id', 
        'master_waybill_id', 
        'position_number', 
        'tracking_number', 
        'reference_number', 
        'request_pickup_date', 
        'merchant_name', 
        'pickup_name', 
        'pickup_phone', 
        'pickup_email', 
        'pickup_address', 
        'pickup_country', 
        'pickup_province', 
        'pickup_city', 
        'pickup_district', 
        'pickup_subdistrict', 
        'pickup_postal_code', 
        'pickup_notes', 
        'pickup_coordinate', 
        'recipient_name', 
        'recipient_phone', 
        'recipient_email', 
        'recipient_address', 
        'recipient_country', 
        'recipient_province', 
        'recipient_city', 
        'recipient_district', 
        'recipient_postal_code', 
        'recipient_notes', 
        'recipient_coordinate', 
        'package_price', 
        'is_insurance', 
        'shipping_price', 
        'cod_price', 
        'total_weight', 
        'total_koli', 
        'volumetric', 
        'notes', 
        'created_via', 
        'created_date', 
        'modified_date', 
        'created_by', 
        'modified_by',
    ];

    public $create_history_after_save = false;
    public $id_history_after_save = 0;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($package) {
            if ($package->create_history_after_save) {
                $params = [
                    'package_id' => $package->package_id,
                    'status_id' => $package->status_id,
                ];
                \App\Helpers\Main::setCreatedModifiedVal(false, $params);
                $ins_packagehistory = PackageHistory::create($params);
                $package->id_history_after_save = $ins_packagehistory->package_history_id;
            }
        });

        static::updated(function ($package) {
            if ($package->create_history_after_save) {
                $params = [
                    'package_id' => $package->package_id,
                    'status_id' => $package->status_id,
                ];
                \App\Helpers\Main::setCreatedModifiedVal(false, $params);
                $ins_packagehistory = PackageHistory::create($params);
                $package->id_history_after_save = $ins_packagehistory->package_history_id;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inbounddetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InboundDetail::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Moving::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outbounds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Outbound::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hub(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(\App\Models\Hub::class, 'hub_id', 'hub_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id', 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicetype(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\ServiceType::class, 'service_type_id', 'service_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packagehistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\PackageHistory::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packageapies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\PackageApi::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function packagedelivery(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\PackageDelivery::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routingdetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RoutingDetail::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferdetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\TransferDetail::class, 'package_id', 'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id', 'status_id')
            ->where([
                'is_active' => Status::ACTIVE,
                'status_group' => Status::STATUS_GROUP['package'],
            ]);
    }
}
