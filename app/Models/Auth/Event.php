<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    
    protected $table = 'event';
    public $description = 'Objek Wisata';

    public const PERMISSIONS = [
        'create'        => 'event create',
        'read'          => 'event read',
        'update'        => 'event update',
        'delete'        => 'event delete',
    ];

    protected $fillable = [
        'destinasi_id', 
        'nama',
        'tanggal_pelaksanaan',
        'jam_mulai',
        'jam_berakhir',
        'tanggal_selesai',
        'contact_person',
        'rating'
    ];   
}