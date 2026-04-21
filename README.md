# 📦 CORPUS_V2 — Arquitetura e Guia de Desenvolvimento

## 🧭 Visão Geral

O **CORPUS_V2** é um sistema de gestão para motel/hotel construído a partir da reestruturação de um sistema legado, preservando regras de negócio consolidadas, porém adotando uma arquitetura moderna, desacoplada e sustentável.

Este projeto segue o conceito de:

* **Monólito modular**
* **Arquitetura em camadas (DDD simplificado)**
* **Separação clara de responsabilidades**
* **Desenvolvimento guiado por IA com regras rígidas**

---

# 🧱 Stack Tecnológica

## 🔙 Backend

* PHP 8.2+
* Laravel (última versão LTS)
* Eloquent ORM (restrito à camada Infrastructure)

## 🎨 Frontend

* React
* Vite
* Axios
* React Router

## 🗄️ Banco de Dados

* MySQL (reaproveitando base existente)

## 🐳 Infraestrutura

* Docker
* Docker Compose
* Nginx

## ⚡ Extras

* Redis (cache e filas)
* Git (controle de versão)

---

# 🗂️ Estrutura Macro do Projeto

```
corpus_v2/
├── backend/
├── frontend/
├── docker/
├── docs/
├── scripts/
├── docker-compose.yml
└── README.md
```

---

# 🐳 Infraestrutura (docker/)

```
docker/
├── nginx/
├── php/
└── mysql/
```

## 🎯 Objetivo

Isolar o ambiente de execução, permitindo coexistência com o sistema legado.

## 🧠 Prompt para IA (Infraestrutura)

```
Crie a infraestrutura Docker do projeto corpus_v2 com:

- Container PHP 8.2 com extensões necessárias
- Container Nginx configurado para Laravel
- Container MySQL
- Container Redis

Regras:
- Usar docker-compose
- Mapear volumes corretamente
- Garantir comunicação entre containers
- Expor aplicação na porta 8080
```

---

# 🔙 Backend (Laravel)

```
backend/
├── app/
├── routes/
├── database/
└── tests/
```

---

## 🧠 Arquitetura Interna (Backend)

```
app/
├── Domain/
├── Application/
├── Infrastructure/
├── Interfaces/
└── Shared/
```

---

## 🧩 Domain (Regra de Negócio)

```
Domain/
├── Produto/
├── Comanda/
├── Financeiro/
```

### 🎯 Responsabilidade

* Regras de negócio puras
* Entidades
* Interfaces de repositório

### ❗ Regras

* NÃO usar Laravel
* NÃO acessar banco
* Código puro PHP

### 🧠 Prompt para IA

```
Crie uma entidade de domínio pura (sem Laravel) para o módulo especificado.

Regras:
- Não usar Eloquent
- Não acessar banco
- Implementar regras de negócio
- Código fortemente tipado
```

---

## ⚙️ Application (Casos de Uso)

```
Application/
├── Produto/
│   ├── CriarProdutoUseCase.php
│   └── ListarProdutoUseCase.php
```

### 🎯 Responsabilidade

* Orquestrar regras de negócio
* Executar ações do sistema

### ❗ Regras

* Não acessar banco diretamente
* Usar interfaces

### 🧠 Prompt para IA

```
Crie um UseCase para o módulo especificado.

Regras:
- Receber dados via DTO
- Usar interface de repositório
- Não usar Eloquent diretamente
- Implementar lógica da aplicação
```

---

## 🏗️ Infrastructure (Persistência e Serviços)

```
Infrastructure/
├── Persistence/
│   ├── Eloquent/
│   └── Repositories/
├── Services/
```

### 🎯 Responsabilidade

* Acesso ao banco
* Integrações externas

### ❗ Regras

* Pode usar Laravel
* Implementa interfaces do Domain

### 🧠 Prompt para IA

```
Crie a implementação de repositório usando Eloquent.

Regras:
- Implementar interface do Domain
- Isolar acesso ao banco
- Não expor Eloquent fora desta camada
```

---

## 🌐 Interfaces (Entrada do Sistema)

```
Interfaces/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
```

### 🎯 Responsabilidade

* Receber requisições HTTP
* Retornar respostas

### ❗ Regras

* NÃO conter regra de negócio
* NÃO acessar banco diretamente

### 🧠 Prompt para IA

```
Crie um Controller para o módulo especificado.

Regras:
- Controller deve ser fino
- Apenas chamar UseCase
- Não conter regra de negócio
- Não acessar banco diretamente
```

---

## 🔁 Shared

```
Shared/
├── DTO/
├── Helpers/
```

### 🎯 Responsabilidade

* Reutilização de código
* Objetos de transferência (DTO)

### 🧠 Prompt para IA

```
Crie um DTO para transporte de dados entre camadas.

Regras:
- Imutável
- Tipado
- Sem lógica de negócio
```

---

# ⚛️ Frontend (React)

```
frontend/
├── src/
│   ├── app/
│   ├── modules/
│   ├── shared/
│   └── assets/
```

---

## 📦 Organização por Módulos

```
modules/
├── produto/
├── comanda/
```

---

## 🎯 Responsabilidade

* Interface do usuário
* Consumo da API

---

## 🧠 Prompt para IA (Frontend)

```
Crie um módulo React para o domínio especificado.

Regras:
- Separar pages, components e services
- Consumir API via Axios
- Não misturar lógica de UI com regras de negócio
- Usar hooks quando necessário
```

---

# 🔗 Comunicação Backend ↔ Frontend

## API REST

```
GET    /api/produtos
POST   /api/produtos
PUT    /api/produtos/{id}
DELETE /api/produtos/{id}
```

---

## 🧠 Prompt para IA

```
Crie endpoints REST para o módulo especificado.

Regras:
- Seguir padrão REST
- Controller deve delegar para UseCase
- Retornar JSON estruturado
```

---

# 📚 Documentação (docs/)

```
docs/
├── arquitetura.md
├── modulos/
├── regras_negocio/
```

---

## 🎯 Objetivo

Evitar perda de conhecimento e recriação de problemas do legado.

---

## 🧠 Prompt para IA

```
Documente o módulo desenvolvido.

Inclua:
- Regras de negócio
- Fluxos
- Decisões técnicas
```

---

# 🧪 Testes (tests/)

## 🎯 Objetivo

Garantir qualidade e evitar regressões

---

## 🧠 Prompt para IA

```
Crie testes para o UseCase.

Regras:
- Testar regras de negócio
- Não depender de banco
- Usar mocks para repositórios
```

---

# ⚠️ Regras Globais do Projeto

* Nunca colocar regra de negócio em Controller
* Nunca acessar banco fora de Repository
* Domain não depende de Laravel
* Sempre usar DTO
* Sempre organizar por domínio

---

# 🚀 Fluxo de Desenvolvimento

1. Criar estrutura base
2. Criar módulo (ex: Produto)
3. Criar Domain
4. Criar UseCases
5. Criar Repository
6. Criar Controller
7. Criar Frontend
8. Criar testes
9. Documentar

---

# 🧭 Considerações Finais

Este projeto NÃO é apenas um sistema novo.

Ele é:

* Uma evolução arquitetural
* Uma reconstrução baseada em domínio
* Um ambiente controlado para evitar novo legado

---

# 🔥 Regra de Ouro

> Se começar a colocar regra de negócio no lugar errado, você estará recriando o sistema antigo.

---
