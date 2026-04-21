# Backend Corpus_v2

Arquitetura baseada em camadas:

- `app/Domain` - entidades e regras de negócio puras
- `app/Application` - casos de uso e orquestração
- `app/Infrastructure` - repositórios, persistência e serviços externos
- `app/Interfaces` - controllers, requests, resources
- `app/Shared` - DTOs, helpers e objetos reutilizáveis

Próximos passos:

1. Criar o projeto Laravel na pasta `backend` ou adaptar o scaffolding atual para a infraestrutura desejada
2. Definir `.env` com conexão ao banco `corpus_motel`
3. Implementar domínios em ordem de prioridade do negócio
4. Mapear cada módulo legado para um bounded context no novo backend

O backend já contém uma primeira API de exemplo para o domínio `Produto` em:

- `app/Domain/Produto`
- `app/Application/Produto`
- `app/Infrastructure/Persistence/ProdutoRepository.php`
- `app/Interfaces/Http/Controllers/ProdutoController.php`

O módulo `estoque/produtos` foi atualizado para:

- listar produtos com categoria e estoque calculado
- buscar produto por ID
- salvar produto (inserir/atualizar)
- alterar status ativo/inativo

O módulo `financeiro/comanda` foi iniciado com:

- listagem de comandas com filtros por data, código e status
- detalhamento de comanda com itens ativos
- criação e edição de comandas via API
- arquitetura baseada em `Domain`, `Application`, `Infrastructure` e `Interfaces`

Comandos iniciais:

- `docker compose up -d`
- `docker compose exec app composer install`
- `docker compose exec app cp .env.example .env`
- `docker compose exec app php artisan key:generate`
- `docker compose exec app php artisan migrate` (quando as migrations estiverem prontas)
