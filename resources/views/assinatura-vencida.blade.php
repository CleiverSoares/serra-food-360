<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinatura Vencida - Serra Food 360</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            
            <!-- Card Principal -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                
                <!-- Header com Ícone -->
                <div class="bg-red-50 border-b border-red-100 p-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        Assinatura Vencida
                    </h1>
                    <p class="text-gray-600">
                        Seu acesso à plataforma foi suspenso
                    </p>
                </div>

                <!-- Conteúdo -->
                <div class="p-8">
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-yellow-800">
                            <strong>Sua assinatura expirou.</strong><br>
                            Para continuar utilizando a plataforma Serra Food 360, é necessário renovar seu plano.
                        </p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <h2 class="font-bold text-gray-900">Como renovar?</h2>
                        <p class="text-sm text-gray-600">
                            Entre em contato com nossa equipe administrativa através dos canais abaixo:
                        </p>
                    </div>

                    <!-- Botões de Contato -->
                    <div class="space-y-3">
                        @php
                            $whatsappLink = \App\Models\ConfiguracaoModel::linkWhatsApp('Olá! Gostaria de renovar minha assinatura na plataforma Serra Food 360.');
                            $emailContato = \App\Models\ConfiguracaoModel::obter('email_contato', 'contato@serrafood360.com.br');
                            $telefoneContato = \App\Models\ConfiguracaoModel::obter('telefone_contato', '(27) 99999-9999');
                        @endphp

                        <a href="{{ $whatsappLink }}" 
                           target="_blank"
                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            Falar no WhatsApp
                        </a>

                        <a href="mailto:{{ $emailContato }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Enviar Email
                        </a>

                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $telefoneContato) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Ligar Agora
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sair da Conta
                            </button>
                        </form>
                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">
                    © 2026 Serra Food 360. Todos os direitos reservados.
                </p>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
