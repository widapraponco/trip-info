<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourses extends Model {
    
    protected $table = 'souvenir';
    public $description = 'Kursus';

    public const PERMISSIONS = [
        'create'        => 'souvenir create',
        'read'          => 'souvenir read',
        'update'        => 'souvenir update',
        'delete'        => 'souvenir delete'
    ];

    protected $fillable = [
        'nip', 
        'type',
        'category',
        'organized_by',
        'year',
        'certificate',
        'other_info',
    ];
}