<?php

namespace App\Domain\Comanda;

class ComandaItem
{
    public function __construct(
        private int $id,
        private int $comandaId,
        private int $produtoId,
        private string $descricao,
        private float $quantidade,
        private float $valor,
        private bool $ativo,
        private ?string $produtoNome = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getComandaId(): int
    {
        return $this->comandaId;
    }

    public function getProdutoId(): int
    {
        return $this->produtoId;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getQuantidade(): float
    {
        return $this->quantidade;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function getProdutoNome(): ?string
    {
        return $this->produtoNome;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'comandaId' => $this->comandaId,
            'produtoId' => $this->produtoId,
            'descricao' => $this->descricao,
            'quantidade' => $this->quantidade,
            'valor' => $this->valor,
            'ativo' => $this->ativo,
            'produtoNome' => $this->produtoNome,
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodItem'],
            (int) $row['CodComanda'],
            (int) $row['CodProduto'],
            $row['Descricao'] ?? '',
            isset($row['Quantidade']) ? (float) $row['Quantidade'] : 0.0,
            isset($row['Valor']) ? (float) $row['Valor'] : 0.0,
            ($row['Ativo'] ?? 'N') === 'S',
            $row['Nome'] ?? null
        );
    }
}
