<?php

namespace App\Domain\Recebimento;

class Recebimento
{
    public function __construct(
        private int $id,
        private ?int $codPedido,
        private ?string $dataVencimento,
        private ?float $valorParcela,
        private ?float $saldoParcela,
        private ?float $valorRecebido,
        private ?string $notaFParcela,
        private ?string $dataPrevReceb,
        private ?string $status,
        private ?string $empresa,
        private ?int $parcelaRef,
        private ?string $ativa
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCodPedido(): ?int
    {
        return $this->codPedido;
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

    public function getValorRecebido(): ?float
    {
        return $this->valorRecebido;
    }

    public function getNotaFParcela(): ?string
    {
        return $this->notaFParcela;
    }

    public function getDataPrevReceb(): ?string
    {
        return $this->dataPrevReceb;
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

    public function getAtiva(): ?string
    {
        return $this->ativa;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codPedido' => $this->codPedido,
            'dataVencimento' => $this->dataVencimento,
            'valorParcela' => $this->valorParcela,
            'saldoParcela' => $this->saldoParcela,
            'valorRecebido' => $this->valorRecebido,
            'notaFParcela' => $this->notaFParcela,
            'dataPrevReceb' => $this->dataPrevReceb,
            'status' => $this->status,
            'empresa' => $this->empresa,
            'parcelaRef' => $this->parcelaRef,
            'ativa' => $this->ativa,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodParcela'],
            isset($row['CodPedido']) ? (int) $row['CodPedido'] : null,
            $row['DataVencimento'] ?? null,
            isset($row['ValorParcela']) ? (float) $row['ValorParcela'] : null,
            isset($row['SaldoParcela']) ? (float) $row['SaldoParcela'] : null,
            isset($row['ValorRecebido']) ? (float) $row['ValorRecebido'] : null,
            $row['NotaFParcela'] ?? null,
            $row['DataPrevReceb'] ?? null,
            $row['Status'] ?? null,
            $row['Empresa'] ?? null,
            isset($row['ParcelaRef']) ? (int) $row['ParcelaRef'] : null,
            $row['Ativa'] ?? null
        );
    }
}