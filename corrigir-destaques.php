<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CotacaoModel;
use App\Models\CotacaoFornecedorModel;

echo "🔧 Corrigindo destaques das cotações...\n\n";

$cotacoes = CotacaoModel::with('ofertas')->get();
$corrigidas = 0;

foreach ($cotacoes as $cotacao) {
    if ($cotacao->ofertas->isEmpty()) {
        continue;
    }

    echo "📋 Cotação: {$cotacao->titulo}\n";
    
    // Remover todos os destaques
    CotacaoFornecedorModel::where('cotacao_id', $cotacao->id)
        ->update(['destaque' => false]);
    
    // Pegar menor preço
    $melhorOferta = $cotacao->ofertas->sortBy('preco_unitario')->first();
    
    if ($melhorOferta) {
        // Marcar como destaque
        $melhorOferta->update(['destaque' => true]);
        echo "   ✅ Destaque: R$ {$melhorOferta->preco_unitario} - {$melhorOferta->fornecedor->fornecedor->nome_empresa}\n";
        $corrigidas++;
    }
    
    echo "\n";
}

echo "✨ Concluído!\n";
echo "📊 {$corrigidas} cotações corrigidas\n";
