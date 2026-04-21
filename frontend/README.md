# Frontend Corpus_v2

Estrutura recomendada:

- `src/app` - configuração global e rotas
- `src/modules` - módulos por domínio (produto, comanda, financeiro, estoque, compras, clientes, configurações)
- `src/shared` - componentes e serviços reutilizáveis
- `src/assets` - estilos, imagens e recursos estáticos

Próximos passos:

1. Inicializar projeto Vite + React dentro de `frontend`
2. Criar cliente Axios para conversar com o backend
3. Implementar telas correspondentes aos módulos do sistema legado
4. Reaproveitar fluxo de dados e formulários já existentes no inventário

O frontend já contém uma tela exemplo de listagem de produtos com pesquisa e exibição de categoria/estoque, além de uma nova lista de comandas com filtros por data, código e status e um formulário para criar/editar comandas.

Comandos iniciais:

- `cd frontend && npm install`
- `npm run dev`
- `npm run build` para gerar produção
