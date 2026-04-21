<?php

namespace App\Domain\Pagar;

class Pagar
{
    public function __construct(
        private int $id,
        private ?int $codNotaCompra,
        private ?string $dataVencimento,
        private ?float $valorParcela,
        private ?float $saldoParcela,
        private ?string $status,
        private ?string $empresa,
        private ?int $parcelaRef
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCodNotaCompra(): ?int
    {
        return $this->codNotaCompra;
    }

    public function getDataVencimento(): ?string
    {
        return $this->dataVencimento;
    }

    public function getValorParcela(): ?float
    {
        return $this->valorParcela;
    }

    public function getSaldoParcela(): ?float
    {
        return $this->saldoParcela;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getParcelaRef(): ?int
    {
        return $this->parcelaRef;
    }

    public function setSaldoParcela(?float $saldoParcela): void
    {
        $this->saldoParcela = $saldoParcela;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codNotaCompra' => $this->codNotaCompra,
            'dataVencimento' => $this->dataVencimento,
            'valorParcela' => $this->valorParcela,
            'saldoParcela' => $this->saldoParcela,
            'status' => $this->status,
            'empresa' => $this->empresa,
            'parcelaRef' => $this->parcelaRef,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodParcela'],
            isset($row['CodNotaCompra']) ? (int) $row['CodNotaCompra'] : null,
            $row['DataVencimento'] ?? null,
            isset($row['ValorParcela']) ? (float) $row['ValorParcela'] : null,
            isset($row['SaldoParcela']) ? (float) $row['SaldoParcela'] : null,
            $row['Status'] ?? null,
            $row['Empresa'] ?? null,
            isset($row['ParcelaRef']) ? (int) $row['ParcelaRef'] : null
        );
    }
}