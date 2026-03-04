<?php namespace Samvol\Inventory\Models;

use Model;

class ApiRefreshToken extends Model
{
    public $table = 'samvol_inventory_api_refresh_tokens';

    protected $fillable = [
        'user_id',
        'token_id',
        'token_hash',
        'expires_at',
        'revoked_at',
        'replaced_by_token_id',
        'user_agent',
        'ip_address',
    ];

    protected $dates = [
        'expires_at',
        'revoked_at',
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'user' => [\Winter\User\Models\User::class, 'key' => 'user_id'],
    ];

    public function scopeActive($query)
    {
        return $query
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now());
    }
}
