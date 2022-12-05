<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaranaPrasarana extends Model {
    
    protected $table = 'SaranaPrasarana';
    public $description = 'Fasilitas';

    public const PERMISSIONS = [
        'create'        => 'participant create',
        'read'          => 'participant read',
        'update'        => 'participant update',
        'delete'        => 'participant delete',
    ];

    protected $fillable = [
        'destinasi',
        'nama',
        'kategori',
        'deskripsi',
        'contact',
        'rating'
    ];
}