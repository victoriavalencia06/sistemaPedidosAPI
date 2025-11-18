<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'idUsuario';

    protected $fillable = [
        'idRol',
        'nombre',
        'email',
        'password',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idUsuario', 'idUsuario');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'idUsuario', 'idUsuario');
    }
}
