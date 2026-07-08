<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SsoAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'password_changed',
        'employee_id',
        'name',
        'department',
        'account_type',
        'transferred_from',
        'position',
        'email',
        'status',
    ];

    protected $casts = [
        'password' => 'encrypted',
        'password_changed' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
