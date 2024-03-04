<?php

namespace App\Models\QuickBooks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickbooksCredential extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quickbooks_credentials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'client_secret',
        'redirect_uri',
        'access_token',
        'refresh_token',
        'realm_id',
        'base_url',
        'api_url',
        'others',
        'status',
        'access_token_expires_at',
        'access_token_validation_period',
        'refresh_token_expires_at',
        'refresh_token_validation_period',
    ];
}
