<?php

namespace App\Domain\Caixa;

class Caixa
{
    public function __construct(
        private int $id,
        private ?string $dataCaixa,
        private ?string $dataPrev1,
        private ?string $dataPrev2,
        private ?float $saldoInicial,
        private ?float $saldoFinal,
        private ?string $empresa,
        private ?float $saldoInicialBanco,
        private ?float $saldoFinalBanco
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDataCaixa(): ?string
    {
        return $this->dataCaixa;
    }

    public function getDataPrev1(): ?string
    {
        return $this->dataPrev1;
    }

    public function getDataPrev2(): ?string
    {
        return $this->dataPrev2;
    }

    public function getSaldoInicial(): ?float
    {
        return $this->saldoInicial;
    }

    public function getSaldoFinal(): ?float
    {
        return $this->saldoFinal;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getSaldoInicialBanco(): ?float
    {
        return $this->saldoInicialBanco;
    }

    public function getSaldoFinalBanco(): ?float
    {
        return $this->saldoFinalBanco;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'dataCaixa' => $this->dataCaixa,
            'dataPrev1' => $this->dataPrev1,
            'dataPrev2' => $this->dataPrev2,
            'saldoInicial' => $this->saldoInicial,
            'saldoFinal' => $this->saldoFinal,
            'empresa' => $this->empresa,
            'saldoInicialBanco' => $this->saldoInicialBanco,
            'saldoFinalBanco' => $this->saldoFinalBanco,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodCaixa'],
            $row['DataCaixa'] ?? null,
            $row['DataPrev1'] ?? null,
            $row['DataPrev2'] ?? null,
            isset($row['SaldoInicial']) ? (float) $row['SaldoInicial'] : null,
            isset($row['SaldoFinal']) ? (float) $row['SaldoFinal'] : null,
            $row['Empresa'] ?? null,
            isset($row['SaldoInicialBanco']) ? (float) $row['SaldoInicialBanco'] : null,
            isset($row['SaldoFinalBanco']) ? (float) $row['SaldoFinalBanco'] : null
        );
    }
}