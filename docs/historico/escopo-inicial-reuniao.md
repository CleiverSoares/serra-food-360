Documentação de Escopo: Projeto Serra Food 360
1. Objetivo do Projeto
Criar um ecossistema digital que conecta donos de restaurantes, fornecedores e prestadores de serviços da região serrana 3, 4. A plataforma funcionará como um hub de informações, negociações coletivas e ferramentas de gestão, gerando valor tanto para quem compra quanto para quem vende 4.
2. Formato e Tecnologia
Plataforma: Site responsivo (web) acessível via link em celulares e computadores 1.
Painel Administrativo (Retaguarda): Dashboard para que o administrador possa alimentar dados semanalmente, editar informações, adicionar ícones e gerenciar o conteúdo de forma autônoma 5-7.
Domínio Oficial: serrafood360.com.br 8, 9.
3. Módulos e Funcionalidades
A plataforma será organizada nos seguintes módulos principais:
Banco de Talentos (Extras): Focado em profissionais universitários para trabalho extra 10, 11. O cadastro (inicialmente manual) conterá: foto, mini currículo, pretensão salarial, dias disponíveis e anexo do currículo original/carta de recomendação 3, 12. Possuirá botão direto para WhatsApp 12.
Cotação da Semana: Listagem de preços de insumos fornecidos por parceiros (ex: filé mignon) 3. A visualização deve ser intuitiva, utilizando formatos como planilhas, gráficos ou estilo "Kanban" 3, 13.
Interesse do Cliente (Compras Coletivas): Espaço onde proprietários manifestam interesse em itens específicos (ex: fardos de trigo) 14, 15. Fornecedores terão acesso a esses dados para negociar preços diferenciados para o grupo 14, 15.
Diretório de Restaurantes: Listagem de estabelecimentos pagantes, incluindo fotos e dados como número de colaboradores (útil para prestadores de serviços, como planos de saúde ou técnicos) 15. Todos com botão de WhatsApp automatizado 16.
Diretório de Fornecedores e Serviços: Organizado por categorias (bebidas, laticínios, manutenção de ar-condicionado, etc.) 16, 17. Um mesmo fornecedor poderá figurar em múltiplas categorias 16.
Material de Gestão: Repositório de vídeos (via links do YouTube para não onerar o servidor), PDFs e documentos sobre legislação, DRE, cálculo de CMV e treinamentos de equipe 18-20.
Consultor de IA: Assistente especializado em food service (integrado via API de IA como Gemini ou ChatGPT) para suporte técnico imediato aos usuários 13, 20.
Troca de Equipamentos: Área para anúncios de permuta ou venda de equipamentos usados entre os restaurantes da região 21.
4. Gestão de Acessos e Monetização
Níveis de Assinatura:
Plano Comum (X): Acesso às funcionalidades base da plataforma 9, 22.
Plano VIP (2X): Acesso exclusivo a mentorias mensais em grupo, promoções diferenciadas de fornecedores e benefícios em workshops 9, 22, 23.
Controle de Login: O acesso será restrito a usuários ativos e previamente aprovados de forma manual pelo administrador para garantir que o público seja nichado (apenas setor de alimentação) 6, 11, 24.
Pagamento: A gestão financeira será externa através da plataforma Asas, com foco em cobrança recorrente via cartão de crédito 25.
5. Regras de Negócio Importantes
Validação de Entrada: Novos membros passam por um pré-filtro ou entrevista antes da liberação do login 11, 24.
Atualização de Conteúdo: O sistema deve permitir que o administrador atualize as cotações e currículos de forma ágil 7, 26.
Escalabilidade: O código deve ser estruturado de forma a permitir a replicação do modelo para outras regiões (ex: Rio Food 360) e a adição de novos ícones/funcionalidades conforme a demanda 7, 27.
Observação para o Desenvolvedor: O cliente demonstrou grande preocupação com a facilidade de uso (usabilidade) e com a independência técnica para editar o conteúdo via dashboard 5, 7. O foco inicial é fazer o "básico bem feito" (Versão 1.0) para validar o modelo de negócio antes de automações mais complexas 23, 27.


Com base nas discussões da reunião e nas funcionalidades detalhadas nas fontes, aqui está um guia completo das telas para o site responsivo Serra Food 360. O foco é criar uma interface intuitiva, baseada em ícones ("bolinhas"), que funcione como o "maior apoiador do dono de restaurante" 1, 2.
1. Portal de Acesso e Boas-Vindas
Tela de Login/Cadastro: Como o acesso é restrito a pagantes e nichado ao setor, haverá um campo de login para usuários aprovados 3, 4.
Landing Page Informativa: Para quem ainda não é membro, explicando os benefícios do ecossistema e como solicitar a entrada (contato com o administrador para validação manual) 3, 4.
2. Dashboard Principal (Home)
Navegação por Ícones: Uma interface limpa com botões grandes para as áreas principais: Restaurantes, Fornecedores, Cotações, Talentos, Gestão e Consultor de IA 2, 5.
Destaques VIP: Banners ou avisos exclusivos para assinantes do Plano 2X sobre as próximas mentorias mensais no Zoom ou promoções especiais 6, 7.
3. Banco de Talentos (Foco em Extras)
Lista de Candidatos: Organizada por cargos (garçom, cozinheiro, recepcionista) 8.
Cards de Perfil: Exibindo foto, mini currículo, pretensão salarial e dias/horários disponíveis 2, 8, 9.
Tela de Detalhes: Visualização do currículo original em PDF ou carta de recomendação 2, 8.
Botão de Ação: Botão automatizado para abrir o WhatsApp do candidato imediatamente 8, 9.
4. Cotação da Semana (Insumos)
Visualização de Preços: Comparativo de itens (ex: filé mignon) entre diversos fornecedores 8.
Layout Flexível: O cliente prefere o formato Kanban, mas também sugere opções de planilha ou gráficos 2, 8.
Ficha do Item: Exibe unidade de medida, quantidade mínima e observações específicas de cada fornecedor 2.
5. Interesse do Cliente (Compras Coletivas)
Painel de Demandas: Onde donos de restaurantes sinalizam interesse em itens de alto volume (ex: fardos de trigo) 10.
Interface do Fornecedor: Uma visão exclusiva para fornecedores verem o volume total de interesse e negociarem diretamente com o grupo 10, 11.
6. Diretório de Restaurantes
Vitrine de Membros: Listagem dos estabelecimentos pagantes com foto e descrição 11, 12.
Dados Estratégicos: Informações como quantidade de colaboradores (para atrair prestadores de serviço como planos de saúde e manutenção) 11.
Botão de Contato: Link direto para o WhatsApp do restaurante 5, 11.
7. Diretório de Fornecedores e Serviços
Categorização: Separados por Bebidas, Laticínios, Hortifrúti e Manutenção (técnicos de ar-condicionado, refrigeração, etc.) 5, 12, 13.
Perfil do Fornecedor: Nome do representante, marcas atendidas, e-mail, site e botão de WhatsApp 2, 12.
8. Material de Gestão
Repositório de Conteúdo: Dividido por temas como Financeiro, Cozinha, Legislação e Gestão de Equipe 1, 14.
Visualização de Vídeos: Galeria com vídeos incorporados do YouTube 1, 12.
Downloads: Lista de PDFs com modelos de DRE, cálculo de CMV e sinalizações obrigatórias para impressão 1, 14.
9. Consultor de IA
Interface de Chat: Um assistente de inteligência artificial especializado em food service para tirar dúvidas técnicas dos usuários em tempo real 2, 14, 15.
10. Troca de Equipamentos (Classificados)
Painel de Permuta: Espaço para anúncios de compra, venda ou troca de equipamentos usados (ex: fatiadoras, mesas) entre os restaurantes da região 16.
11. Painel Administrativo (Retaguarda)
Gestão de Usuários: Tela para aprovar manualmente novos logins e monitorar acessos ativos 3, 6.
CRUD de Conteúdo: Interface para o administrador alimentar semanalmente as cotações, subir novos currículos e atualizar materiais de gestão 17-19.
Configuração de Ícones: Capacidade de adicionar ou remover módulos (ex: criar o ícone de "Relacionamento Governamental") 18.
Este guia reflete a necessidade de um sistema leve, responsivo e de fácil usabilidade, focado na conexão direta via WhatsApp para agilizar os negócios 5, 20.











