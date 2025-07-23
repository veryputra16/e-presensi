<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = ['nama_kegiatan', 'slug', 'tgl_kegiatan'];

    public function presenceDetails()
    {
        return $this->hasMany(PresenceDetail::class, 'presence_id');
    }
}

