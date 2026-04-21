<?php

namespace App\Domain\Pago;

class Pago
{
    public function __construct(
        private int $id,
        private ?int $codParcela,
        private ?string $dataPagamento,
        private ?float $valorPago,
        private ?int $diasAtraso,
        private ?float $moraDiaria,
        private ?float $valorJuros,
        private ?float $amortizado,
        private ?string $banco,
        private ?string $obs,
        private ?string $formaPagamento,
        private ?string $chequeComp,
        private ?string $numCheque,
        private ?string $empresa,
        private ?string $statusTipo
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCodParcela(): ?int
    {
        return $this->codParcela;
    }

    public function getDataPagamento(): ?string
    {
        return $this->dataPagamento;
    }

    public function getValorPago(): ?float
    {
        return $this->valorPago;
    }

    public function getDiasAtraso(): ?int
    {
        return $this->diasAtraso;
    }

    public function getMoraDiaria(): ?float
    {
        return $this->moraDiaria;
    }

    public function getValorJuros(): ?float
    {
        return $this->valorJuros;
    }

    public function getAmortizado(): ?float
    {
        return $this->amortizado;
    }

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function getObs(): ?string
    {
        return $this->obs;
    }

    public function getFormaPagamento(): ?string
    {
        return $this->formaPagamento;
    }

    public function getChequeComp(): ?string
    {
        return $this->chequeComp;
    }

    public function getNumCheque(): ?string
    {
        return $this->numCheque;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getStatusTipo(): ?string
    {
        return $this->statusTipo;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codParcela' => $this->codParcela,
            'dataPagamento' => $this->dataPagamento,
            'valorPago' => $this->valorPago,
            'diasAtraso' => $this->diasAtraso,
            'moraDiaria' => $this->moraDiaria,
            'valorJuros' => $this->valorJuros,
            'amortizado' => $this->amortizado,
            'banco' => $this->banco,
            'obs' => $this->obs,
            'formaPagamento' => $this->formaPagamento,
            'chequeComp' => $this->chequeComp,
            'numCheque' => $this->numCheque,
            'empresa' => $this->empresa,
            'statusTipo' => $this->statusTipo,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodPagamento'],
            isset($row['CodParcela']) ? (int) $row['CodParcela'] : null,
            $row['DataPagamento'] ?? null,
            isset($row['ValorPago']) ? (float) $row['ValorPago'] : null,
            isset($row['DiasAtraso']) ? (int) $row['DiasAtraso'] : null,
            isset($row['MoraDiaria']) ? (float) $row['MoraDiaria'] : null,
            isset($row['ValorJuros']) ? (float) $row['ValorJuros'] : null,
            isset($row['Amortizado']) ? (float) $row['Amortizado'] : null,
            $row['Banco'] ?? null,
            $row['Obs'] ?? null,
            $row['FormaPagamento'] ?? null,
            $row['ChequeComp'] ?? null,
            $row['NumCheque'] ?? null,
            $row['Empresa'] ?? null,
            $row['StatusTipo'] ?? null
        );
    }
}