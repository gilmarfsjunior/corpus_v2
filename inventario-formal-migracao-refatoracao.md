# Inventario Formal do Sistema Corpus

Documento de apoio para futura migracao/refatoracao do projeto `corpus`, cobrindo arquitetura atual, catalogo de endpoints, inventario de dados, fluxos de negocio e pontos de integracao entre legado e CodeIgniter.

## 1) Escopo e Contexto

- Raiz analisada: `c:\xampp\htdocs\corpus`
- Blocos de aplicacao:
  - `sistema/` (legado procedural PHP)
  - `sis/` (CodeIgniter MVC)
- Banco principal identificado: `corpus_motel`

## 2) Arquitetura Atual

### 2.1 Legado procedural (`sistema/`)

Estrutura baseada em paginas PHP com includes centrais:

- `sistema/includes/i_acesso.php`
- `sistema/includes/i_conexaodb.php`
- `sistema/includes/i_funcoes.php`

Padrao dominante:

- SQL direto com `mysqli_query`
- Regras de negocio acopladas em paginas e funcoes utilitarias
- Endpoints AJAX por arquivos em pastas `ajax/`

Modulos funcionais principais:

- `sistema/sistema/` (shell principal, menu e operacao diaria)
- `sistema/login/`
- `sistema/comanda/` e `sistema/comanda/consumo/`
- `sistema/movimento/`
- `sistema/financeiro/`
- `sistema/estoque/` e `sistema/produtos/`
- `sistema/compras/`
- `sistema/clientes_fornecedores/`
- `sistema/configuracoes/`
- `sistema/usuarios/`
- `sistema/relatorios/`
- `sistema/historico/`

### 2.2 CodeIgniter (`sis/`)

Estrutura MVC classica:

- `sis/application/controllers/`
- `sis/application/models/`
- `sis/application/views/`
- `sis/application/config/`

Padrao dominante:

- Controllers com `session_start()` e modelos por dominio
- Models com SQL raw + alguns `insert/update` via `$this->db`
- Rotas por convencao `controller/metodo` + poucas rotas explicitas

Dominios CI mapeados:

- `financeiro`
- `estoque`
- `compras`
- `configuracoes`
- `clientefornecedor`

## 3) Catalogo de Endpoints/Routes

## 3.1 CodeIgniter (`sis/`)

Entrada:

- `sis/index.php`

Rotas explicitas:

- Arquivo: `sis/application/config/routes.php`
- `default_controller = "sms_admin/usuario"`
- `404_override = ''`

Controllers de negocio ativos:

- `clientefornecedor/clientefornecedor`
- `financeiro/relatoriocomanda`
- `financeiro/relatoriocaixacliente`
- `financeiro/caixa`
- `financeiro/duplicidadecaixa`
- `financeiro/comanda`
- `compras/compras_categoria`
- `compras/relatoriocompras`
- `estoque/produtos`
- `estoque/produtos_transicao`
- `estoque/categorias`
- `configuracoes/apartamentos`
- `configuracoes/lembretes`
- `configuracoes/relatorios`

Endpoints CI relevantes (amostra operacional):

- `/financeiro/relatoriocomanda/index`
- `/financeiro/relatoriocomanda/verificar_financeiro_comanda`
- `/financeiro/relatoriocomanda/comanda/{CodComanda}/{Empresa}`
- `/financeiro/relatoriocomanda/excluir_comanda`
- `/financeiro/relatoriocaixacliente/mensal`
- `/financeiro/relatoriocaixacliente/anual`
- `/financeiro/caixa/verificar_caixa_fechado`
- `/financeiro/duplicidadecaixa/confirmar`
- `/estoque/produtos/lista`
- `/estoque/produtos/gravar`
- `/estoque/produtos/editar`
- `/estoque/produtos/ativadesativa`
- `/estoque/produtos_transicao/lista`
- `/estoque/produtos_transicao/autocomplete`
- `/estoque/produtos_transicao/gravar`
- `/estoque/produtos_transicao/excluir`
- `/estoque/categorias/lista`
- `/estoque/categorias/gravar`
- `/configuracoes/apartamentos/listarapartamentos`
- `/configuracoes/lembretes/verificar_lembrete_usuario`
- `/configuracoes/lembretes/definir_lembrete_visualizado`

### 3.2 Procedural (`sistema/`)

Entrada:

- `sistema/index.php` (redireciona para `sistema/`)

Telas nucleares:

- `sistema/sistema/index.php`
- `sistema/login/index.php`
- `sistema/comanda/index.php`
- `sistema/comanda/consumo/index.php`
- `sistema/financeiro/caixafluxo.php`

Endpoints AJAX identificados (catalogo inicial):

- `sistema/comanda/ajax/total_horas_ajax.php`
- `sistema/comanda/ajax/print_linux_ajax.php`
- `sistema/comanda/ajax/auto_completa_ajax.php`
- `sistema/comanda/ajax/produto_comanda_ajax.php`
- `sistema/comanda/ajax/calcula_dinheiro_cartao_ajax.php`
- `sistema/comanda/ajax/calcula_dinheiro_pix_ajax.php`
- `sistema/comanda/consumo/ajax/auto_completa_consumo_ajax.php`
- `sistema/comanda/consumo/ajax/consumidor_ajax.php`
- `sistema/comanda/consumo/ajax/print_consumo_linux_ajax.php`
- `sistema/estoque/ajax/produto_autocomplete_ajax.php`
- `sistema/estoque/ajax/atualizar_quantidade.php`
- `sistema/estoque/ajax/ativa_desativa_local.php`
- `sistema/financeiro/ajax/valorhora_ajax.php`
- `sistema/financeiro/ajax/valorhoraate_ajax.php`
- `sistema/financeiro/ajax/valorhoraexcedente_ajax.php`
- `sistema/financeiro/ajax/valordiaria_ajax.php`
- `sistema/financeiro/ajax/valorpernoite_ajax.php`
- `sistema/compras/ajax/pagamento_ajax.php`
- `sistema/compras/ajax/parcelas_ajax.php`
- `sistema/apartamentos/ajax/apartamentos_ajax.php`
- `sistema/apartamentos/ajax/apartamentos_status_ajax.php`
- `sistema/relatorios/mov_caixa/ajax/usuarios_option.php`

## 4) Inventario de Dados (Tabelas e Relacionamentos)

Observacao: relacionamentos inferidos por uso real em SQL de models/controllers, pois nao foram encontradas migrations completas versionadas.

### 4.1 Comanda/Movimento

Tabelas:

- `tcomanda`
- `tcomanda_itens`
- `tmovimento`
- `tapartamentos`
- `ttipo_apartamento`
- `tvalorhora`

Relacoes inferidas:

- `tcomanda.CodMovimento -> tmovimento.CodMovimento`
- `tcomanda.CodApartamento -> tapartamentos.CodApartamento`
- `tcomanda_itens.CodComanda -> tcomanda.CodComanda`
- `tcomanda_itens.CodProduto -> tproduto.CodProduto`

### 4.2 Financeiro/Faturamento

Tabelas:

- `tvendas`
- `tareceber`
- `trecebido`
- `tcaixa`
- `tautoriza_dup_caixa`
- `tcaixa_observacao`
- `tpagar`
- `tpago`
- `tbanco`
- `tbancosaldo`

Relacoes inferidas:

- `tvendas.CodMovimento -> tmovimento.CodMovimento`
- `tareceber.CodPedido -> tvendas.CodVenda`
- `trecebido.CodParcela -> tareceber.CodParcela`
- `tpagar.CodNotaCompra -> tpedidoscompra.CodPedido`
- `tpago.CodParcela -> tpagar.CodParcela`

### 4.3 Estoque/Produtos

Tabelas:

- `tproduto`
- `tproduto_categoria`
- `testoqcompras`
- `tentrada_setor`
- `tproduto_transicao`
- `tsaida_estoque`

Relacoes inferidas:

- `tproduto.CodCategoria -> tproduto_categoria.CodCategoria`
- `tproduto_categoria.CodSetor -> tentrada_setor.Codigo` (via joins de listagem)

### 4.4 Compras

Tabelas:

- `tpedidoscompra`
- `titenscompra`
- `compras_categorias`

### 4.5 Cadastros/Acesso

Tabelas:

- `tusuarios`
- `tmenu`
- `tmenusub`
- `tusuariosmenu`
- `tusuariosmenusub`
- `tusuariosmenusub2`
- `tclientes`
- `tempresas_dados`
- `tcidades`
- `testados`
- `thistorico`

## 5) Fluxos de Negocio

### 5.1 Ciclo de comanda

1. Abertura da comanda (`tcomanda`) associada a apartamento/movimento.
2. Inclusao/exclusao logica de itens (`tcomanda_itens`, campo `Ativo`).
3. Calculo de horas/valores (`tvalorhora`, tolerancia, forma de pagamento).
4. Fechamento da comanda com consolidacao financeira.
5. Registro de historico (`thistorico`).
6. Possiveis reflexos em estoque (saida/reposicao, conforme regra).

### 5.2 Movimento e fechamento financeiro

1. Abertura de movimento (`tmovimento`).
2. Operacao de recebimentos/pagamentos no periodo.
3. Fechamento do movimento e consolidacao em `tvendas`/`tareceber`.
4. Fechamento do caixa diario (`tcaixa`, `tbancosaldo`, observacoes).
5. Controle de duplicidade via `tautoriza_dup_caixa`.

### 5.3 Estoque e compras

1. Cadastro e manutencao de produtos/categorias/setores.
2. Entradas/saidas e transicoes de estoque.
3. Compras com pedidos/itens e impacto financeiro.
4. Relatorios operacionais e gerenciais.

## 6) Matriz Modulo -> Tabelas -> Telas -> Endpoints

### 6.1 Comanda

- Tabelas: `tcomanda`, `tcomanda_itens`, `tapartamentos`, `tmovimento`, `tvalorhora`
- Telas:
  - `sistema/comanda/index.php`
  - `sistema/comanda/consumo/index.php`
  - `sis/application/views/financeiro/relatoriocomanda_view.php`
- Endpoints:
  - `sistema/comanda/ajax/*`
  - `sistema/comanda/consumo/ajax/*`
  - `sis/financeiro/comanda/*`
  - `sis/financeiro/relatoriocomanda/*`

### 6.2 Financeiro/Caixa

- Tabelas: `tcaixa`, `tmovimento`, `tvendas`, `tareceber`, `trecebido`, `tpagar`, `tpago`, `tautoriza_dup_caixa`
- Telas:
  - `sistema/financeiro/caixafluxo.php`
  - `sistema/financeiro/caixalist.php`
  - `sis/application/views/financeiro/relatoriocaixacliente_view.php`
- Endpoints:
  - `sistema/financeiro/ajax/*`
  - `sis/financeiro/caixa/*`
  - `sis/financeiro/duplicidadecaixa/*`
  - `sis/financeiro/relatoriocaixacliente/*`

### 6.3 Estoque/Produtos

- Tabelas: `tproduto`, `tproduto_categoria`, `tentrada_setor`, `testoqcompras`, `tproduto_transicao`
- Telas:
  - `sistema/estoque/*`
  - `sistema/produtos/*`
  - `sis/application/views/estoque/produtos_view.php`
- Endpoints:
  - `sistema/estoque/ajax/*`
  - `sistema/produtos/ajax/*`
  - `sis/estoque/produtos/*`
  - `sis/estoque/categorias/*`
  - `sis/estoque/produtos_transicao/*`

### 6.4 Compras

- Tabelas: `tpedidoscompra`, `titenscompra`, `compras_categorias`, `tpagar`, `tpago`
- Telas:
  - `sistema/compras/*`
  - `sis/application/views/compras/*`
- Endpoints:
  - `sistema/compras/ajax/*`
  - `sis/compras/compras_categoria/*`
  - `sis/compras/relatoriocompras/*`

### 6.5 Clientes/Fornecedores

- Tabelas: `tclientes` (e tabelas relacionadas por regras de negocio)
- Telas:
  - `sistema/clientes_fornecedores/*`
  - `sis/application/views/clientefornecedor/*`
- Endpoints:
  - `sistema/clientes_fornecedores/ajax/*`
  - `sis/clientefornecedor/clientefornecedor/*`

### 6.6 Configuracoes e acesso

- Tabelas: `tusuarios`, `tempresas_dados`, `tapartamentos`, `ttipo_apartamento`, tabelas de menu/permissao
- Telas:
  - `sistema/configuracoes/*`
  - `sistema/usuarios/*`
  - `sis/application/views/configuracoes/*`
- Endpoints:
  - `sis/configuracoes/apartamentos/*`
  - `sis/configuracoes/lembretes/*`
  - `sis/configuracoes/relatorios/*`

## 7) Integracao entre Legado e CI

Integracao bidirecional por URL (acoplamento por frontend/JS):

- CI consumindo recursos/telas do legado:
  - JS do `sis` abre relatorios e comanda em `../../sistema/...`
- Legado consumindo endpoints CI:
  - JS do legado chama `../../sis/index.php/configuracoes/lembretes/...`

Ponto central de convergencia:

- Mesma base de dados (`corpus_motel`) compartilhada por ambos os blocos.

## 8) Bibliotecas e Dependencias

Bibliotecas principais identificadas (vendor in-repo):

- `FPDF`
- `PHPExcel`
- `JPGraph`
- `ExtJS 2.2` (forte no legado)
- `jQuery`
- `xajax` (em partes do legado)

Observacao:

- Nao foram encontrados gerenciadores modernos de dependencia (`composer.json`, `package.json`) na raiz analisada.

## 9) Ponto Critico Especifico (Impressao Linux de Consumo)

Arquivo:

- `sistema/comanda/consumo/ajax/print_consumo_linux_ajax.php`

Resumo tecnico:

- Le `tcomanda` e `tcomanda_itens` para montar espelho de impressao.
- Escreve diretamente em porta de impressora via `exec`.
- Usa `chmod` e redirecionamento para dispositivo (`/dev/lp0`/USB).
- Depende de includes utilitarios para formatacao e lookup de consumidor.

Risco de migracao:

- Forte acoplamento com ambiente SO/permissoes de dispositivo e shell.

## 10) Lacunas e Proximos Passos Recomendados

Lacunas atuais deste inventario:

- Nao ha ER fisico completo com PK/FK declaradas no repositorio.
- Parte dos relacionamentos foi inferida por consultas SQL.
- Endpoints procedurais sao amplos e distribuidos em muitos arquivos.

Proximos passos para migracao/refatoracao:

1. Extrair dicionario fisico do banco em ambiente (schema completo).
2. Congelar contrato de endpoints atuais (request/response) por modulo.
3. Definir bounded contexts e estrategia de estrangulamento do legado.
4. Migrar primeiro modulos de menor acoplamento infra (evitar impressao no inicio).
5. Isolar integracoes de dispositivo (impressora) via adaptador/servico dedicado.

---

Versao: `v1`  
Status: Inventario consolidado para planejamento tecnico de migracao/refatoracao.
