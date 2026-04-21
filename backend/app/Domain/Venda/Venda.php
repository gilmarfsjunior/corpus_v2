<?php

namespace App\Domain\Venda;

class Venda
{
    public function __construct(
        private int $id,
        private ?int $clienteId,
        private ?int $vendedorId,
        private ?string $dataVenda,
        private ?string $usuario,
        private ?string $empresa,
        private ?string $data,
        private ?string $hora,
        private ?float $totalVenda,
        private ?float $desconto,
        private ?float $despesasViagem,
        private ?string $status,
        private ?int $movimentoId
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClienteId(): ?int
    {
        return $this->clienteId;
    }

    public function getVendedorId(): ?int
    {
        return $this->vendedorId;
    }

    public function getDataVenda(): ?string
    {
        return $this->dataVenda;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getHora(): ?string
    {
        return $this->hora;
    }

    public function getTotalVenda(): ?float
    {
        return $this->totalVenda;
    }

    public function getDesconto(): ?float
    {
        return $this->desconto;
    }

    public function getDespesasViagem(): ?float
    {
        return $this->despesasViagem;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getMovimentoId(): ?int
    {
        return $this->movimentoId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'clienteId' => $this->clienteId,
            'vendedorId' => $this->vendedorId,
            'dataVenda' => $this->dataVenda,
            'usuario' => $this->usuario,
            'empresa' => $this->empresa,
            'data' => $this->data,
            'hora' => $this->hora,
            'totalVenda' => $this->totalVenda,
            'desconto' => $this->desconto,
            'despesasViagem' => $this->despesasViagem,
            'status' => $this->status,
            'movimentoId' => $this->movimentoId,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodVenda'],
            isset($row['CodCliente']) ? (int) $row['CodCliente'] : null,
            isset($row['CodVendedor']) ? (int) $row['CodVendedor'] : null,
            $row['DataVenda'] ?? null,
            $row['Usuario'] ?? null,
            $row['Empresa'] ?? null,
            $row['Data'] ?? null,
            $row['Hora'] ?? null,
            isset($row['TotalVenda']) ? (float) $row['TotalVenda'] : null,
            isset($row['Desconto']) ? (float) $row['Desconto'] : null,
            isset($row['DespesasViagem']) ? (float) $row['DespesasViagem'] : null,
            $row['Status'] ?? null,
            isset($row['CodMovimento']) ? (int) $row['CodMovimento'] : null
        );
    }
}
