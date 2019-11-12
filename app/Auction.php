<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'cathegory', 'title', 'year', 'width', 'height', 'depth', 'description', 'condition', 'origin', 'company', 'product_image_path', 'min_price', 'max_price', 'buyout_price', 'end_date',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function bids() {
        return $this->hasMany('App\Bid');
    }
    public function purchaseDetail() {
        return $this->hasMany('App\purchaseDetail');
    }
}
