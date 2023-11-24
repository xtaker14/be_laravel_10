<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property integer $organization_id
 * @property string $code
 * @property string $name
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Client[] $clients
 * @property Hub[] $hubs
 * @property Partner[] $partners
 * @property Servicetype[] $servicetypes
 */
class OrganizationDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'organizationdetail';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'organization_detail_id';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified_date'; 

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Organization::class, 'organization_id', 'organization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id', 'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Province::class, 'province_id', 'province_id');
    }

    protected function assetCompanyLogo(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (isset($attributes['company_logo'])) {
                    return file_exists( public_path().'/storage/'.$attributes['company_logo']) ? asset('storage/'.$attributes['company_logo']) : '';
                }
            }
        );
    }

    protected function assetBackgroundLogin(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (isset($attributes['background_login'])) {
                    return file_exists( public_path().'/storage/'.$attributes['background_login']) ? asset('storage/'.$attributes['background_login']) : '';
                }
            }
        );
    }

    protected function assetDokumenLogo(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (isset($attributes['dokumen_logo'])) {
                    return file_exists( public_path().'/storage/'.$attributes['dokumen_logo']) ? asset('storage/'.$attributes['dokumen_logo']) : '';
                }
            }
        );
    }
}
