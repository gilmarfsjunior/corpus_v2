import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterVenda } from '../services/vendaService';
import { listarRecebimentos } from '../../recebimento/services/recebimentoService';

export default function VendaDetails() {
  const { id } = useParams();
  const [venda, setVenda] = useState(null);
  const [recebimentos, setRecebimentos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      setError('ID inválido');
      setLoading(false);
      return;
    }

    const loadData = async () => {
      try {
        const [vendaData, recebimentosData] = await Promise.all([
          obterVenda(id),
          listarRecebimentos({ codPedido: id })
        ]);
        setVenda(vendaData);
        setRecebimentos(recebimentosData);
      } catch (err) {
        setError(err.message || 'Erro ao carregar dados');
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, [id]);

  return (
    <DefaultLayout title={`Venda ${id}`}>
      <div className="detail-actions">
        <Link to="/vendas">Voltar para lista</Link>
      </div>

      {loading && <p>Carregando venda...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && venda && (
        <>
          <section className="detail-card">
          <div>
            <strong>Cliente:</strong> {venda.clienteId ?? 'N/A'}
          </div>
          <div>
            <strong>Vendedor:</strong> {venda.vendedorId ?? 'N/A'}
          </div>
          <div>
            <strong>Data Venda:</strong> {venda.dataVenda || 'N/A'}
          </div>
          <div>
            <strong>Empresa:</strong> {venda.empresa || 'N/A'}
          </div>
          <div>
            <strong>Total:</strong> R$ {Number(venda.totalVenda ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Desconto:</strong> R$ {Number(venda.desconto ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Despesas de viagem:</strong> R$ {Number(venda.despesasViagem ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Status:</strong> {venda.status || 'N/A'}
          </div>
          <div>
            <strong>Movimento:</strong> {venda.movimentoId ?? 'N/A'}
          </div>
        </section>

        <section className="detail-card">
          <h3>Recebimentos Relacionados</h3>
          {recebimentos.length === 0 ? (
            <p>Nenhum recebimento encontrado.</p>
          ) : (
            <table className="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Vencimento</th>
                  <th>Valor Parcela</th>
                  <th>Saldo</th>
                  <th>Status</th>
                  <th>Detalhes</th>
                </tr>
              </thead>
              <tbody>
                {recebimentos.map((recebimento) => (
                  <tr key={recebimento.id}>
                    <td>{recebimento.id}</td>
                    <td>{recebimento.dataVencimento || '-'}</td>
                    <td>R$ {Number(recebimento.valorParcela ?? 0).toFixed(2)}</td>
                    <td>R$ {Number(recebimento.saldoParcela ?? 0).toFixed(2)}</td>
                    <td>{recebimento.status || '-'}</td>
                    <td>
                      <Link to={`/recebimentos/${recebimento.id}`}>Ver</Link>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </section>
        </>
      )}
    </DefaultLayout>
  );
}
