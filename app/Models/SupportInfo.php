<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportInfo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'bank_info',
        'contact_phone',
        'contact_email',
        'contact_address',
        'thank_you_message',
        'is_active',
        
        // Coordonnées bancaires
        'bank_name',
        'bank_account',
        'bank_iban',
        'bank_swift',
        
        // Mobile Money
        'mtn_money_number',
        'mtn_money_name',
        'orange_money_number',
        'orange_money_name',
        
        // Cryptomonnaies
        'btc_address',
        'eth_address',
        'usdt_address',
        'usdt_ton_address',
        'usdt_bnb_address',
        'pi_address',
    ];
}
