<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPapers extends Model {
    
    protected $table = 'penginapan';

    public const PERMISSIONS = [
        'create'        => 'penginapan create',
        'read'          => 'penginapan read',
        'update'        => 'penginapan update',
        'delete'        => 'penginapan delete',
    ];

    protected $fillable = [
        'id', 
        'nama',
        'alamat',
        'deskripsi',
        'tanggal_selesai',
        'contact_person',
        'rating'
    ];
    
}