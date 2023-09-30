<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $client_rates_id
 * @property integer $client_id
 * @property integer $rates_id
 * @property integer $selling_price
 * @property string $created_date
 * @property string $modified_date
 * @property string $created_by
 * @property string $modified_by
 * @property Client $client
 * @property Rates $rate
 */
class ClientRates extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clientrates';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'client_rates_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['client_id', 'rates_id', 'selling_price', 'created_date', 'modified_date', 'created_by', 'modified_by'];

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
    public function rate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Rates::class, 'rates_id', 'rates_id');
    }
}
