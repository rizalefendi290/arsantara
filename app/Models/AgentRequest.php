<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentRequest extends Model
{
        protected $fillable = [
        'user_id',
        'role',
        'nama',
        'nama_agen',
        'nama_pemilik_agen',
        'no_hp',
        'email',
        'alamat',
        'status'
    ];
}
