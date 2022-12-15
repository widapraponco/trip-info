<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model {
    
    protected $table = 'destinasi';
    public $description = 'Objek Wisata';

    public const PERMISSIONS = [
        'create'        => 'destinasi create',
        'read'          => 'destinasi read',
        'update'        => 'destinasi update',
        'delete'        => 'destinasi delete',
    ];

    protected $fillable = [
        'nama', 
        'alamat',
        'deskripsi',
        'kota_id'
    ];

    public function FunctionName(Type $var = null)
    {
        # code...
    }
}
