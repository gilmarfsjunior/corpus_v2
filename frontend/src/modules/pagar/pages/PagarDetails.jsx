import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterPagar } from '../services/pagarService';
import { listarPagos } from '../../pago/services/pagoService';

export default function PagarDetails() {
  const { id } = useParams();
  const [pagar, setPagar] = useState(null);
  const [pagos, setPagos] = useState([]);
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
        const [pagarData, pagosData] = await Promise.all([
          obterPagar(id),
          listarPagos({ codParcela: id })
        ]);
        setPagar(pagarData);
        setPagos(pagosData);
      } catch (err) {
        setError(err.message || 'Erro ao carregar dados');
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, [id]);

  return (
    <DefaultLayout title={`Pagar ${id}`}>
      <div className="detail-actions">
        <Link to="/pagars">Voltar para lista</Link>
      </div>

      {loading && <p>Carregando pagar...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && pagar && (
        <>
          <section className="detail-card">
          <div>
            <strong>Cód. Nota Compra:</strong> {pagar.codNotaCompra ?? 'N/A'}
          </div>
          <div>
            <strong>Data Vencimento:</strong> {pagar.dataVencimento || 'N/A'}
          </div>
          <div>
            <strong>Valor Parcela:</strong> R$ {Number(pagar.valorParcela ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Saldo Parcela:</strong> R$ {Number(pagar.saldoParcela ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Status:</strong> {pagar.status || 'N/A'}
          </div>
          <div>
            <strong>Empresa:</strong> {pagar.empresa || 'N/A'}
          </div>
          <div>
            <strong>Parcela Ref:</strong> {pagar.parcelaRef ?? 'N/A'}
          </div>
        </section>

        <section className="detail-card">
          <h3>Pagamentos Relacionados</h3>
          {pagos.length === 0 ? (
            <p>Nenhum pagamento encontrado.</p>
          ) : (
            <table className="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Data Pagamento</th>
                  <th>Valor Pago</th>
                  <th>Forma</th>
                  <th>Detalhes</th>
                </tr>
              </thead>
              <tbody>
                {pagos.map((pago) => (
                  <tr key={pago.id}>
                    <td>{pago.id}</td>
                    <td>{pago.dataPagamento || '-'}</td>
                    <td>R$ {Number(pago.valorPago ?? 0).toFixed(2)}</td>
                    <td>{pago.formaPagamento || '-'}</td>
                    <td>
                      <Link to={`/pagos/${pago.id}`}>Ver</Link>
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