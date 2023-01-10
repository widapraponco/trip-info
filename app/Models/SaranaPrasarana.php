<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaranaPrasarana extends Model {
    
    protected $table = 'SaranaPrasarana';
    public $description = 'sarana_prasarana';

    public const PERMISSIONS = [
        'create'        => 'sarana_prasarana create',
        'read'          => 'sarana_prasarana read',
        'update'        => 'sarana_prasarana update',
        'delete'        => 'sarana_prasarana delete',
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