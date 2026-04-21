<?php

namespace App\Domain\Movimento;

class Movimento
{
    public function __construct(
        private int $id,
        private ?int $usuarioId,
        private ?string $empresa,
        private ?string $dataAbertura,
        private ?string $horaAbertura,
        private ?string $dataFechamento,
        private ?string $horaFechamento,
        private ?string $status,
        private ?string $observacao
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuarioId;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getDataAbertura(): ?string
    {
        return $this->dataAbertura;
    }

    public function getHoraAbertura(): ?string
    {
        return $this->horaAbertura;
    }

    public function getDataFechamento(): ?string
    {
        return $this->dataFechamento;
    }

    public function getHoraFechamento(): ?string
    {
        return $this->horaFechamento;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'usuarioId' => $this->usuarioId,
            'empresa' => $this->empresa,
            'dataAbertura' => $this->dataAbertura,
            'horaAbertura' => $this->horaAbertura,
            'dataFechamento' => $this->dataFechamento,
            'horaFechamento' => $this->horaFechamento,
            'status' => $this->status,
            'observacao' => $this->observacao,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodMovimento'],
            isset($row['CodUsuario']) ? (int) $row['CodUsuario'] : null,
            $row['Empresa'] ?? null,
            $row['DataAbertura'] ?? null,
            $row['HoraAbertura'] ?? null,
            $row['DataFechamento'] ?? null,
            $row['HoraFechamento'] ?? null,
            $row['Status'] ?? null,
            $row['Observacao'] ?? null
        );
    }
}
