<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model {
    
    protected $table = 'agentravel';
    public $description = 'Objek Wisata';

    public const PERMISSIONS = [
        'create'        => 'agentravel create',
        'read'          => 'agentravel read',
        'update'        => 'agentravel update',
        'delete'        => 'agentravel delete',
    ];

    protected $fillable = [
        'destinasi_id',
        'alamat', 
        'nama',
        'contact_person',
        'rating'
    ];   
}
