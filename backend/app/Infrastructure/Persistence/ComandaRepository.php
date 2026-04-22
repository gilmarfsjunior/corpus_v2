<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Comanda\Comanda;
use App\Domain\Comanda\ComandaItem;
use App\Domain\Comanda\ComandaRepositoryInterface;
use App\Infrastructure\Database\Connection;
use PDO;

class ComandaRepository implements ComandaRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listar(?string $dataInicial = null, ?string $dataFinal = null, ?string $codComanda = null, ?bool $concluido = null, ?string $empresa = null): array
    {
        $sql = 'SELECT tcomanda.CodComanda AS id, tcomanda.* FROM tcomanda WHERE 1=1';
        $params = [];

        if ($empresa !== null && trim($empresa) !== '') {
            $sql .= ' AND Empresa = :empresa';
            $params[':empresa'] = $empresa;
        }

        if ($dataInicial !== null && $dataFinal !== null) {
            $sql .= ' AND DataSaida >= :dataInicial AND DataSaida <= :dataFinal';
            $params[':dataInicial'] = $dataInicial;
            $params[':dataFinal'] = $dataFinal;
        }

        if ($codComanda !== null && $codComanda !== '') {
            $sql .= ' AND CodComanda = :codComanda';
            $params[':codComanda'] = $codComanda;
        }

        if ($concluido !== null) {
            $sql .= ' AND Concluido = :concluido';
            $params[':concluido'] = $concluido ? 'S' : 'N';
        }

        $sql .= ' ORDER BY CodComanda DESC LIMIT 50';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): Comanda {
            return Comanda::fromDatabaseRow($row);
        }, $rows);
    }

    public function buscarPorId(int $id): ?Comanda
    {
        $sql = 'SELECT tcomanda.CodComanda AS id, tcomanda.* FROM tcomanda WHERE CodComanda = :id LIMIT 1';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        $comanda = Comanda::fromDatabaseRow($row);
        $comanda = $comanda->withItens($this->listarItens($id));

        return $comanda;
    }

    public function buscarPorApartamentoAtiva(int $apartamentoId): ?Comanda
    {
        // Busca a comanda mais recente do apartamento que ainda está ativa (sem data de saída)
        $sql = 'SELECT tcomanda.CodComanda AS id, tcomanda.* 
                FROM tcomanda 
                WHERE CodApartamento = :apartamentoId 
                AND DataSaida IS NULL 
                ORDER BY CodComanda DESC 
                LIMIT 1';
        
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([':apartamentoId' => $apartamentoId]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        $comanda = Comanda::fromDatabaseRow($row);
        $comanda = $comanda->withItens($this->listarItens($row['id']));

        return $comanda;
    }

    public function listarItens(int $codComanda): array
    {
        $sql = 'SELECT ti.CodItem, ti.CodComanda, ti.CodProduto, tp.Descricao AS Descricao, ti.Quantidade, ti.Valor, ti.Ativo, tp.Descricao AS Nome
                FROM tcomanda_itens ti
                INNER JOIN tproduto tp ON ti.CodProduto = tp.CodProduto
                WHERE ti.CodComanda = :codComanda AND ti.Ativo = :ativo';

        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([
            ':codComanda' => $codComanda,
            ':ativo' => 'S',
        ]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function (array $row): ComandaItem {
            return ComandaItem::fromDatabaseRow($row);
        }, $rows);
    }

    public function adicionarItem(ComandaItem $item): int
    {
        $sql = 'INSERT INTO tcomanda_itens (CodComanda, CodProduto, Descricao, Quantidade, Valor, Ativo) VALUES (:CodComanda, :CodProduto, :Descricao, :Quantidade, :Valor, :Ativo)';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([
            ':CodComanda' => $item->getComandaId(),
            ':CodProduto' => $item->getProdutoId(),
            ':Descricao' => $item->getDescricao(),
            ':Quantidade' => $item->getQuantidade(),
            ':Valor' => $item->getValor(),
            ':Ativo' => $item->isAtivo() ? 'S' : 'N',
        ]);

        return (int) $this->connection->getPdo()->lastInsertId();
    }

    public function alterarStatusItem(int $itemId, bool $ativo): bool
    {
        $sql = 'UPDATE tcomanda_itens SET Ativo = :ativo WHERE CodItem = :CodItem';
        $statement = $this->connection->getPdo()->prepare($sql);

        return $statement->execute([
            ':ativo' => $ativo ? 'S' : 'N',
            ':CodItem' => $itemId,
        ]);
    }

    public function salvar(Comanda $comanda): int
    {
        $data = [
            ':CodApartamento' => $comanda->getApartamentoId(),
            ':DataEntrada' => $comanda->getDataEntrada(),
            ':HoraEntrada' => $comanda->getHoraEntrada(),
            ':DataSaida' => $comanda->getDataSaida(),
            ':HoraSaida' => $comanda->getHoraSaida(),
            ':Usuario' => $comanda->getUsuario(),
            ':Empresa' => $comanda->getEmpresa(),
            ':Placa' => $comanda->getPlaca(),
            ':ValorTotal' => $comanda->getValorTotal(),
            ':ValorTotalConf' => $comanda->getValorTotalConf(),
            ':UsuarioSaida' => $comanda->getUsuarioSaida(),
            ':QuantHoras' => $comanda->getQuantHoras(),
            ':TotalHoras' => $comanda->getTotalHoras(),
            ':Concluido' => $comanda->isConcluido() ? 'S' : 'N',
            ':CodMovimento' => $comanda->getMovimentoId(),
            ':Dinheiro' => $comanda->getDinheiro(),
            ':Cheque' => $comanda->getCheque(),
            ':Cartao' => $comanda->getCartao(),
            ':ComandaTipo' => $comanda->getComandaTipo(),
            ':ClienteComanda' => $comanda->getClienteComanda(),
        ];

        if ($comanda->getId() > 0) {
            $data[':CodComanda'] = $comanda->getId();
            $sql = 'UPDATE tcomanda SET CodApartamento = :CodApartamento, DataEntrada = :DataEntrada, HoraEntrada = :HoraEntrada, DataSaida = :DataSaida, HoraSaida = :HoraSaida, Usuario = :Usuario, Empresa = :Empresa, Placa = :Placa, ValorTotal = :ValorTotal, ValorTotalConf = :ValorTotalConf, UsuarioSaida = :UsuarioSaida, QuantHoras = :QuantHoras, TotalHoras = :TotalHoras, Concluido = :Concluido, CodMovimento = :CodMovimento, Dinheiro = :Dinheiro, Cheque = :Cheque, Cartao = :Cartao, ComandaTipo = :ComandaTipo, ClienteComanda = :ClienteComanda WHERE CodComanda = :CodComanda';
            $statement = $this->connection->getPdo()->prepare($sql);
            $statement->execute($data);

            return $comanda->getId();
        }

        $sql = 'INSERT INTO tcomanda (CodApartamento, DataEntrada, HoraEntrada, DataSaida, HoraSaida, Usuario, Empresa, Placa, ValorTotal, ValorTotalConf, UsuarioSaida, QuantHoras, TotalHoras, Concluido, CodMovimento, Dinheiro, Cheque, Cartao, ComandaTipo, ClienteComanda) VALUES (:CodApartamento, :DataEntrada, :HoraEntrada, :DataSaida, :HoraSaida, :Usuario, :Empresa, :Placa, :ValorTotal, :ValorTotalConf, :UsuarioSaida, :QuantHoras, :TotalHoras, :Concluido, :CodMovimento, :Dinheiro, :Cheque, :Cartao, :ComandaTipo, :ClienteComanda)';
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute($data);

        return (int) $this->connection->getPdo()->lastInsertId();
    }
}
