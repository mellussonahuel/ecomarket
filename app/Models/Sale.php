<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
{

    protected $with = [
        'user',
        'items'
    ];

    // protected $casts = [
    //     'tags' => 'array',
    // ];

    protected $appends = [
        'sale_date'
    ];

    public function items()
    {
        return $this->hasMany(SaleItems::class);
    }

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function location(){
        return $this->hasOne(\App\Location::class);
    }


    public function payment(){
        return $this->hasOne(Payment::class);
    }

    public function getSaleDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('d-m-Y');
    }
  
}
