import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterRecebimento } from '../services/recebimentoService';

export default function RecebimentoDetails() {
  const { id } = useParams();
  const [recebimento, setRecebimento] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      setError('ID inválido');
      setLoading(false);
      return;
    }

    obterRecebimento(id)
      .then((data) => setRecebimento(data))
      .catch((err) => setError(err.message || 'Erro ao carregar recebimento'))
      .finally(() => setLoading(false));
  }, [id]);

  return (
    <DefaultLayout title={`Recebimento ${id}`}>
      <div className="detail-actions">
        <Link to="/recebimentos">Voltar para lista</Link>
      </div>

      {loading && <p>Carregando recebimento...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && recebimento && (
        <section className="detail-card">
          <div>
            <strong>Cód. Pedido:</strong> {recebimento.codPedido ?? 'N/A'}
          </div>
          <div>
            <strong>Data Vencimento:</strong> {recebimento.dataVencimento || 'N/A'}
          </div>
          <div>
            <strong>Valor Parcela:</strong> R$ {Number(recebimento.valorParcela ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Saldo Parcela:</strong> R$ {Number(recebimento.saldoParcela ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Valor Recebido:</strong> R$ {Number(recebimento.valorRecebido ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Nota Fiscal:</strong> {recebimento.notaFParcela || 'N/A'}
          </div>
          <div>
            <strong>Data Prev. Receb:</strong> {recebimento.dataPrevReceb || 'N/A'}
          </div>
          <div>
            <strong>Status:</strong> {recebimento.status || 'N/A'}
          </div>
          <div>
            <strong>Empresa:</strong> {recebimento.empresa || 'N/A'}
          </div>
          <div>
            <strong>Parcela Ref:</strong> {recebimento.parcelaRef ?? 'N/A'}
          </div>
          <div>
            <strong>Ativa:</strong> {recebimento.ativa || 'N/A'}
          </div>
        </section>
      )}
    </DefaultLayout>
  );
}