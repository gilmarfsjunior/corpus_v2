<?php

namespace App\Domain\Comanda;

class Comanda
{
    public function __construct(
        private int $id,
        private ?int $apartamentoId,
        private ?string $dataEntrada,
        private ?string $horaEntrada,
        private ?string $dataSaida,
        private ?string $horaSaida,
        private ?string $usuario,
        private ?string $empresa,
        private ?string $placa,
        private ?float $valorTotal,
        private ?float $valorTotalConf,
        private ?string $usuarioSaida,
        private ?int $quantHoras,
        private ?float $totalHoras,
        private ?bool $concluido,
        private ?int $movimentoId,
        private ?float $dinheiro,
        private ?float $cheque,
        private ?float $cartao,
        private ?string $comandaTipo,
        private ?string $clienteComanda,
        private array $itens = []
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getApartamentoId(): ?int
    {
        return $this->apartamentoId;
    }

    public function getDataEntrada(): ?string
    {
        return $this->dataEntrada;
    }

    public function getHoraEntrada(): ?string
    {
        return $this->horaEntrada;
    }

    public function getDataSaida(): ?string
    {
        return $this->dataSaida;
    }

    public function getHoraSaida(): ?string
    {
        return $this->horaSaida;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getPlaca(): ?string
    {
        return $this->placa;
    }

    public function getValorTotal(): ?float
    {
        return $this->valorTotal;
    }

    public function getValorTotalConf(): ?float
    {
        return $this->valorTotalConf;
    }

    public function getUsuarioSaida(): ?string
    {
        return $this->usuarioSaida;
    }

    public function getQuantHoras(): ?int
    {
        return $this->quantHoras;
    }

    public function getTotalHoras(): ?float
    {
        return $this->totalHoras;
    }

    public function isConcluido(): ?bool
    {
        return $this->concluido;
    }

    public function getMovimentoId(): ?int
    {
        return $this->movimentoId;
    }

    public function getDinheiro(): ?float
    {
        return $this->dinheiro;
    }

    public function getCheque(): ?float
    {
        return $this->cheque;
    }

    public function getCartao(): ?float
    {
        return $this->cartao;
    }

    public function getComandaTipo(): ?string
    {
        return $this->comandaTipo;
    }

    public function getClienteComanda(): ?string
    {
        return $this->clienteComanda;
    }

    public function getItens(): array
    {
        return $this->itens;
    }

    public function withItens(array $itens): self
    {
        $clone = clone $this;
        $clone->itens = $itens;

        return $clone;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'apartamentoId' => $this->apartamentoId,
            'dataEntrada' => $this->dataEntrada,
            'horaEntrada' => $this->horaEntrada,
            'dataSaida' => $this->dataSaida,
            'horaSaida' => $this->horaSaida,
            'usuario' => $this->usuario,
            'empresa' => $this->empresa,
            'placa' => $this->placa,
            'valorTotal' => $this->valorTotal,
            'valorTotalConf' => $this->valorTotalConf,
            'usuarioSaida' => $this->usuarioSaida,
            'quantHoras' => $this->quantHoras,
            'totalHoras' => $this->totalHoras,
            'concluido' => $this->concluido,
            'movimentoId' => $this->movimentoId,
            'dinheiro' => $this->dinheiro,
            'cheque' => $this->cheque,
            'cartao' => $this->cartao,
            'comandaTipo' => $this->comandaTipo,
            'clienteComanda' => $this->clienteComanda,
            'itens' => array_map(static fn(ComandaItem $item) => $item->toArray(), $this->itens),
        ];
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            (int) $row['CodComanda'],
            isset($row['CodApartamento']) ? (int) $row['CodApartamento'] : null,
            $row['DataEntrada'] ?? null,
            $row['HoraEntrada'] ?? null,
            $row['DataSaida'] ?? null,
            $row['HoraSaida'] ?? null,
            $row['Usuario'] ?? null,
            $row['Empresa'] ?? null,
            $row['Placa'] ?? null,
            isset($row['ValorTotal']) ? (float) $row['ValorTotal'] : null,
            isset($row['ValorTotalConf']) ? (float) $row['ValorTotalConf'] : null,
            $row['UsuarioSaida'] ?? null,
            isset($row['QuantHoras']) ? (int) $row['QuantHoras'] : null,
            isset($row['TotalHoras']) ? (float) $row['TotalHoras'] : null,
            isset($row['Concluido']) ? $row['Concluido'] === 'S' : null,
            isset($row['CodMovimento']) ? (int) $row['CodMovimento'] : null,
            isset($row['Dinheiro']) ? (float) $row['Dinheiro'] : null,
            isset($row['Cheque']) ? (float) $row['Cheque'] : null,
            isset($row['Cartao']) ? (float) $row['Cartao'] : null,
            $row['ComandaTipo'] ?? null,
            $row['ClienteComanda'] ?? null
        );
    }
}
