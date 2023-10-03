<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Academic;
use App\Models\Disciplinary;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'birthdate',
        'address',
        'parent_name',
        'parent_contact',
        'payable_status',
        'account_status',
        'batch_year',
        'joined',
        'status',
    ];

    public function academics()
    {
        return $this->hasMany(Academic::class);
    }

    public function disciplinary()
    {
        return $this->hasMany(Disciplinary::class);
    }
}