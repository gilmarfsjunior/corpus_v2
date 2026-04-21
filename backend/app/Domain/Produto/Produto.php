<?php

namespace App\Domain\Produto;

class Produto
{
    public function __construct(
        private int $id,
        private string $descricao,
        private ?string $marca,
        private ?string $unidade,
        private ?float $estoqueMinimo,
        private ?float $precoVenda,
        private ?string $tipoP,
        private bool $ativo,
        private ?int $categoriaId = null,
        private ?string $categoria = null,
        private ?string $codBarra = null,
        private ?float $estoque = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function getUnidade(): ?string
    {
        return $this->unidade;
    }

    public function getEstoqueMinimo(): ?float
    {
        return $this->estoqueMinimo;
    }

    public function getPrecoVenda(): ?float
    {
        return $this->precoVenda;
    }

    public function getTipoP(): ?string
    {
        return $this->tipoP;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function getCategoriaId(): ?int
    {
        return $this->categoriaId;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function getCodBarra(): ?string
    {
        return $this->codBarra;
    }

    public function getEstoque(): ?float
    {
        return $this->estoque;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'marca' => $this->marca,
            'unidade' => $this->unidade,
            'estoqueMinimo' => $this->estoqueMinimo,
            'precoVenda' => $this->precoVenda,
            'tipoP' => $this->tipoP,
            'ativo' => $this->ativo,
            'categoriaId' => $this->categoriaId,
            'categoria' => $this->categoria,
            'codBarra' => $this->codBarra,
            'estoque' => $this->estoque,
        ];
    }

    public function getAtivoChar(): string
    {
        return $this->ativo ? 'S' : 'N';
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) ($row['descricao'] ?? ''),
            $row['marca'] !== null ? (string) $row['marca'] : null,
            $row['unidade'] !== null ? (string) $row['unidade'] : null,
            $row['estoqueMinimo'] !== null ? (float) $row['estoqueMinimo'] : null,
            $row['precoVenda'] !== null ? (float) $row['precoVenda'] : null,
            $row['tipoP'] !== null ? (string) $row['tipoP'] : null,
            ($row['ativo'] ?? '') === 'S',
            $row['categoriaId'] !== null ? (int) $row['categoriaId'] : null,
            $row['categoria'] !== null ? (string) $row['categoria'] : null,
            $row['codBarra'] !== null ? (string) $row['codBarra'] : null,
            $row['estoque'] !== null ? (float) $row['estoque'] : null
        );
    }
}
