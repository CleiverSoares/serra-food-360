<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ConfiguracaoModel extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = [
        'chave',
        'valor',
        'tipo',
        'grupo',
        'label',
        'descricao',
        'ordem',
    ];

    /**
     * Obter valor de configuração (com cache)
     */
    public static function obter(string $chave, $padrao = null)
    {
        return Cache::remember("config_{$chave}", 3600, function () use ($chave, $padrao) {
            $config = self::where('chave', $chave)->first();
            return $config ? $config->valor : $padrao;
        });
    }

    /**
     * Definir valor de configuração
     */
    public static function definir(string $chave, $valor): void
    {
        self::updateOrCreate(
            ['chave' => $chave],
            ['valor' => $valor]
        );

        // Limpar cache
        Cache::forget("config_{$chave}");
    }

    /**
     * Limpar todo cache de configurações
     */
    public static function limparCache(): void
    {
        $configs = self::all();
        foreach ($configs as $config) {
            Cache::forget("config_{$config->chave}");
        }
    }

    /**
     * Obter link WhatsApp formatado
     */
    public static function linkWhatsApp(?string $mensagem = null): string
    {
        $numero = self::obter('whatsapp_contato', '5527999999999');
        $mensagemPadrao = self::obter('mensagem_whatsapp_padrao', 'Olá!');
        $texto = urlencode($mensagem ?? $mensagemPadrao);
        
        return "https://wa.me/{$numero}?text={$texto}";
    }
}
