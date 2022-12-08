<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPapers extends Model {
    
    protected $table = 'restoran';

    public const PERMISSIONS = [
        'create'        => 'restoran create',
        'read'          => 'restoran read',
        'update'        => 'restoran update',
        'delete'        => 'restoran delete',
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