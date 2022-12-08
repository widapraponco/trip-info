<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Models\Employee;

class Image extends Model {
    
    protected $table = 'image';
    public $desciption = 'Gambar';

    public const PERMISSIONS = [
        'create'        => 'image create',
        'read'          => 'image read',
        'update'        => 'image update',
        'delete'        => 'image delete',
    ];


    //public function employee()
    //{
    //    return $this->belongsTo(Employee::class, 'nip');
    //}
}