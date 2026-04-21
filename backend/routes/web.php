<?php

use App\Interfaces\Http\Controllers\ApartmentController;
use App\Interfaces\Http\Controllers\CaixaController;
use App\Interfaces\Http\Controllers\ComandaController;
use App\Interfaces\Http\Controllers\ComandaItemController;
use App\Interfaces\Http\Controllers\LoginController;
use App\Interfaces\Http\Controllers\MovimentoController;
use App\Interfaces\Http\Controllers\PagarController;
use App\Interfaces\Http\Controllers\PagoController;
use App\Interfaces\Http\Controllers\ProdutoController;
use App\Interfaces\Http\Controllers\RecebimentoController;
use App\Interfaces\Http\Controllers\RecebidoController;
use App\Interfaces\Http\Controllers\VendaController;

return [
    'GET' => [
        '/api/apartamentos' => [ApartmentController::class, 'index'],
        '/api/apartamentos/{id}' => [ApartmentController::class, 'show'],
        '/api/produtos' => [ProdutoController::class, 'index'],
        '/api/produtos/{id}' => [ProdutoController::class, 'show'],
        '/api/comandas' => [ComandaController::class, 'index'],
        '/api/comandas/{id}' => [ComandaController::class, 'show'],
        '/api/comandas/{id}/itens' => [ComandaItemController::class, 'index'],
        '/api/movimentos' => [MovimentoController::class, 'index'],
        '/api/movimentos/{id}' => [MovimentoController::class, 'show'],
        '/api/vendas' => [VendaController::class, 'index'],
        '/api/vendas/{id}' => [VendaController::class, 'show'],
        '/api/caixas' => [CaixaController::class, 'index'],
        '/api/caixas/{id}' => [CaixaController::class, 'show'],
        '/api/recebimentos' => [RecebimentoController::class, 'index'],
        '/api/recebimentos/{id}' => [RecebimentoController::class, 'show'],
        '/api/recebidos' => [RecebidoController::class, 'index'],
        '/api/recebidos/{id}' => [RecebidoController::class, 'show'],
        '/api/pagars' => [PagarController::class, 'index'],
        '/api/pagars/{id}' => [PagarController::class, 'show'],
        '/api/pagos' => [PagoController::class, 'index'],
        '/api/pagos/{id}' => [PagoController::class, 'show'],
    ],
    'POST' => [
        '/api/login' => [LoginController::class, 'login'],
        '/api/produtos' => [ProdutoController::class, 'store'],
        '/api/comandas' => [ComandaController::class, 'store'],
        '/api/comandas/{id}/itens' => [ComandaItemController::class, 'store'],
        '/api/movimentos' => [MovimentoController::class, 'store'],
        '/api/vendas' => [VendaController::class, 'store'],
        '/api/caixas' => [CaixaController::class, 'store'],
        '/api/recebimentos' => [RecebimentoController::class, 'store'],
        '/api/recebidos' => [RecebidoController::class, 'store'],
        '/api/pagars' => [PagarController::class, 'store'],
        '/api/pagos' => [PagoController::class, 'store'],
    ],
    'PUT' => [
        '/api/comandas/{id}' => [ComandaController::class, 'update'],
        '/api/movimentos/{id}/fechar' => [MovimentoController::class, 'fechar'],
        '/api/vendas/{id}' => [VendaController::class, 'update'],
        '/api/caixas/{id}' => [CaixaController::class, 'update'],
        '/api/caixas/{id}/fechar' => [CaixaController::class, 'fechar'],
        '/api/recebimentos/{id}' => [RecebimentoController::class, 'update'],
        '/api/recebimentos/{id}/receber' => [RecebimentoController::class, 'receber'],
        '/api/recebidos/{id}' => [RecebidoController::class, 'update'],
        '/api/pagars/{id}' => [PagarController::class, 'update'],
        '/api/pagars/{id}/pagar' => [PagarController::class, 'pagar'],
        '/api/pagos/{id}' => [PagoController::class, 'update'],
    ],
    'PATCH' => [
        '/api/comandas/{comandaId}/itens/{itemId}/status' => [ComandaItemController::class, 'toggleStatus'],
        '/api/produtos/{id}/status' => [ProdutoController::class, 'toggleStatus'],
    ],
];
