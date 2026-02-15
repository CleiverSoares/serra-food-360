<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'plano',
        'telefone',
        'whatsapp',
        'cidade',
        'aprovado_por',
        'aprovado_em',
        'motivo_rejeicao',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aprovado_em' => 'datetime',
        ];
    }

    /**
     * Verifica se o usuário está aprovado
     */
    public function estaAprovado(): bool
    {
        return $this->status === 'aprovado';
    }

    /**
     * Verifica se o usuário é admin
     */
    public function ehAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica se o usuário é restaurante
     */
    public function ehRestaurante(): bool
    {
        return $this->role === 'restaurante';
    }

    /**
     * Verifica se o usuário é fornecedor
     */
    public function ehFornecedor(): bool
    {
        return $this->role === 'fornecedor';
    }

    /**
     * Verifica se o usuário é VIP
     */
    public function ehVip(): bool
    {
        return $this->plano === 'vip';
    }

    /**
     * Scope: Apenas aprovados
     */
    public function scopeAprovados($query)
    {
        return $query->where('status', 'aprovado');
    }

    /**
     * Scope: Apenas pendentes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    /**
     * Scope: Por role
     */
    public function scopePorRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Relacionamento: usuário que aprovou este usuário
     */
    public function aprovador()
    {
        return $this->belongsTo(UserModel::class, 'aprovado_por');
    }

    /**
     * Relacionamento: usuários aprovados por este usuário
     */
    public function usuariosAprovados()
    {
        return $this->hasMany(UserModel::class, 'aprovado_por');
    }

    /**
     * Relacionamento: Perfil de restaurante
     */
    public function restaurante()
    {
        return $this->hasOne(RestauranteModel::class, 'user_id');
    }

    /**
     * Relacionamento: Perfil de fornecedor
     */
    public function fornecedor()
    {
        return $this->hasOne(FornecedorModel::class, 'user_id');
    }
}
