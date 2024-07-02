<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CouponGenerator;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email',
        'code',
        'amount',
        'type',
        'valid_from',
        'valid_to',
        'used'
    ];

    
    protected $casts = [
        'all_users' => 'boolean',
        'valid_to' => 'date',
        'valid_from' => 'date' 
    ];

    

    public static function getNewCouponCode(array $options = []): string
    {
        $code = CouponGenerator::generate($options);

        return self::whereCode($code)->first() ? self::getNewCouponCode($options) : $code;
    }

    /**
     * Checks if the coupon is a total discount.
     *
     * @return bool
     */
    public function isTotalDiscount()
    {
        return $this->amount === 100;
    }


    public function incrementUses()
    {
        $this->increment('uses');
    }

   
}
