<?php

namespace App\Domain\Apartment;

class Apartment
{
    public function __construct(
        private int $id,
        private int $numero,
        private string $status,
        private int $tipoId,
        private ?string $tipoDescricao = null,
        private bool $ativo = true,
        private ?int $empresaId = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTipoId(): int
    {
        return $this->tipoId;
    }

    public function getTipoDescricao(): ?string
    {
        return $this->tipoDescricao;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function getEmpresaId(): ?int
    {
        return $this->empresaId;
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'L' => '#00cc00', // Liberado - Verde
            'O' => '#ff0000', // Ocupado - Vermelho
            'C' => '#f89200', // Conferência - Laranja
            'R' => '#800080', // Recebimento - Roxo (estimado)
            'Z' => '#ffff00', // Limpeza - Amarelo (estimado)
            default => '#cccccc' // Cinza para status desconhecido
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'L' => 'Liberado',
            'O' => 'Ocupado',
            'C' => 'Conferência',
            'R' => 'Recebimento',
            'Z' => 'Limpeza',
            default => 'Desconhecido'
        };
    }
}