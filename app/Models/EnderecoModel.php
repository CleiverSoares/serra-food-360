<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecoModel extends Model
{
    protected $table = 'enderecos';
    
    protected $fillable = [
        'user_id',
        'tipo',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'latitude',
        'longitude',
        'is_padrao'
    ];

    protected $casts = [
        'is_padrao' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Relacionamento com usuário
     */
    public function usuario()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Retorna endereço completo formatado
     */
    public function enderecoCompleto(): string
    {
        $partes = array_filter([
            $this->logradouro,
            $this->numero,
            $this->complemento,
            $this->bairro,
            $this->cidade,
            $this->estado,
            $this->cep
        ]);

        return implode(', ', $partes);
    }

    /**
     * Calcula distância entre este endereço e coordenadas fornecidas
     * @return float|null Distância em quilômetros
     */
    public function calcularDistancia(float $lat, float $lng): ?float
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Fórmula de Haversine para calcular distância entre dois pontos
        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }

    /**
     * Retorna cidade e estado formatados
     */
    public function cidadeEstado(): string
    {
        return "{$this->cidade} - {$this->estado}";
    }
}
