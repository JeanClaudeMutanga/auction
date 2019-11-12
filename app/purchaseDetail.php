<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchaseDetail extends Model
{
    protected $fillable = [
        'user_id', 'auction_id', 'price',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function auction() {
        return $this->belongsTo('App\Auction');
    }
}
