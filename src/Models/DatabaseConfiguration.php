<?php

namespace Ikechukwukalu\Dynamicdatabaseconfig\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DatabaseConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'name',
        'database',
        'configuration'
    ];

    public function scopeConfiguredDatabase($query, mixed $ref = null)
    {
        if (!$ref) {
            $ref = session(config('dynamicdatabaseconfig.session_ref', '_db_ref'));
        }

        return $query->where('ref', $ref);
    }

    public function setConfigurationAttribute($value)
    {
        if (!is_array($value)) {
            $value = [];
        }

        $value = json_encode($value);

        if (config('dynamicdatabaseconfig.hash', true)) {
            return $this->attributes['configuration'] = Crypt::encryptString($value);
        }

        return $value;
    }

    public function getConfigurationAttribute($value)
    {
        if (config('dynamicdatabaseconfig.hash', true)) {
            return json_decode(Crypt::decryptString($value), true);
        }

        return json_decode($value, true);
    }
}
