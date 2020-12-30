<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'packets';

    protected $fillable = [
        'tgl_dtg',
        'tgl_ambl',
        'status',

        'user_id',
        'customer_id'
    ];
}
