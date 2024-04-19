<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class InstantCheckoutItem extends Authenticatable

{
	protected $table = 'instant_checkout_items';
    
    protected $appends = [
        "download_url",
    ];

	
    public function stripeCheckout()
    {

        return $this->belongsTo(StripeCheckout::class);

    }

    public function music()
    {
        return $this->belongsTo(UserMusic::class, 'source_table_id');
    }

    public function checkoutDetails()
    {

        return $this->hasMany(InstantCheckoutItemDetail::class);

    }

    public function getDownloadUrlAttribute()
    {
        $downloads = null;
        
        if($this->type == 'music' && $this->music != null) {
            $downloads = $this->music->downloads;

            foreach($downloads as $download) {
                if($download['source'] == 'firebase') {
                    $download_url = $download['path'];
                }
            }
        }
        
        return $downloads;
    }
}