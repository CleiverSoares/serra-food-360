<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Criar tabelas normalizadas de endereços e contatos
     */
    public function up(): void
    {
        // 1. Criar tabela enderecos
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['principal', 'entrega', 'cobranca'])->default('principal');
            $table->string('cep', 9)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento', 100)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('cidade', 100);
            $table->string('estado', 2);
            $table->string('pais', 2)->default('BR');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_padrao')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'tipo']);
            $table->index(['cidade', 'estado']);
        });

        // 2. Criar tabela contatos
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['telefone', 'whatsapp', 'comercial', 'suporte']);
            $table->string('valor', 20);
            $table->boolean('is_principal')->default(false);
            $table->timestamp('verificado_em')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'tipo']);
        });

        // 3. Migrar dados existentes de users para as novas tabelas
        DB::transaction(function () {
            $users = DB::table('users')
                ->whereNotNull('cidade')
                ->orWhereNotNull('telefone')
                ->orWhereNotNull('whatsapp')
                ->get();

            foreach ($users as $user) {
                // Migrar endereço (se tiver cidade)
                if ($user->cidade) {
                    DB::table('enderecos')->insert([
                        'user_id' => $user->id,
                        'tipo' => 'principal',
                        'cidade' => $user->cidade,
                        'estado' => 'ES', // Região Serrana = Espírito Santo
                        'pais' => 'BR',
                        'is_padrao' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Migrar telefone
                if ($user->telefone) {
                    DB::table('contatos')->insert([
                        'user_id' => $user->id,
                        'tipo' => 'telefone',
                        'valor' => $user->telefone,
                        'is_principal' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Migrar WhatsApp
                if ($user->whatsapp) {
                    DB::table('contatos')->insert([
                        'user_id' => $user->id,
                        'tipo' => 'whatsapp',
                        'valor' => $user->whatsapp,
                        'is_principal' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }

    /**
     * Reverter a migration (não remove colunas antigas por segurança)
     */
    public function down(): void
    {
        Schema::dropIfExists('contatos');
        Schema::dropIfExists('enderecos');
    }
};
