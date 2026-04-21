<?php

namespace App\Domain\Recebido;

class Recebido
{
    public function __construct(
        private int $id,
        private ?int $codParcela,
        private ?string $dataRecebimento,
        private ?float $valorRecebido,
        private ?int $diasAtraso,
        private ?float $moraDiaria,
        private ?float $valorJuros,
        private ?float $amortizado,
        private ?string $banco,
        private ?string $obs,
        private ?string $formaPagamento,
        private ?string $chequeComp,
        private ?string $numCheque,
        private ?string $statusTipo,
        private ?string $empresa,
        private ?int $codCliente
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

    public function getDataRecebimento(): ?string
    {
        return $this->dataRecebimento;
    }

    public function getValorRecebido(): ?float
    {
        return $this->valorRecebido;
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

    public function getStatusTipo(): ?string
    {
        return $this->statusTipo;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getCodCliente(): ?int
    {
        return $this->codCliente;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codParcela' => $this->codParcela,
            'dataRecebimento' => $this->dataRecebimento,
            'valorRecebido' => $this->valorRecebido,
            'diasAtraso' => $this->diasAtraso,
            'moraDiaria' => $this->moraDiaria,
            'valorJuros' => $this->valorJuros,
            'amortizado' => $this->amortizado,
            'banco' => $this->banco,
            'obs' => $this->obs,
            'formaPagamento' => $this->formaPagamento,
            'chequeComp' => $this->chequeComp,
            'numCheque' => $this->numCheque,
            'statusTipo' => $this->statusTipo,
            'empresa' => $this->empresa,
            'codCliente' => $this->codCliente,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodRecebimento'],
            isset($row['CodParcela']) ? (int) $row['CodParcela'] : null,
            $row['DataRecebimento'] ?? null,
            isset($row['ValorRecebido']) ? (float) $row['ValorRecebido'] : null,
            isset($row['DiasAtraso']) ? (int) $row['DiasAtraso'] : null,
            isset($row['MoraDiaria']) ? (float) $row['MoraDiaria'] : null,
            isset($row['ValorJuros']) ? (float) $row['ValorJuros'] : null,
            isset($row['Amortizado']) ? (float) $row['Amortizado'] : null,
            $row['Banco'] ?? null,
            $row['Obs'] ?? null,
            $row['FormaPagamento'] ?? null,
            $row['ChequeComp'] ?? null,
            $row['NumCheque'] ?? null,
            $row['StatusTipo'] ?? null,
            $row['Empresa'] ?? null,
            isset($row['CodCliente']) ? (int) $row['CodCliente'] : null
        );
    }
}