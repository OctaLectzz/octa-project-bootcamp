<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Tag extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description'
    ];


    protected $attributes =[
        'created_by' => ''
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function scopeFilter($query, array $filters)
    {
        // User Search //
        $query->when($filters['user'] ?? false, fn($query, $user) =>
            $query->whereHas('user', fn($query)=>
                $query->where('username', $user)
            )
        );
    }


    public function users()
    {
        return $this->hasMany(User::class, 'created_by');
    }

}
