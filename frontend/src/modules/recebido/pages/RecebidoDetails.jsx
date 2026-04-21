import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterRecebido } from '../services/recebidoService';

export default function RecebidoDetails() {
  const { id } = useParams();
  const [recebido, setRecebido] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      setError('ID inválido');
      setLoading(false);
      return;
    }

    obterRecebido(id)
      .then((data) => setRecebido(data))
      .catch((err) => setError(err.message || 'Erro ao carregar recebido'))
      .finally(() => setLoading(false));
  }, [id]);

  return (
    <DefaultLayout title={`Recebido ${id}`}>
      <div className="detail-actions">
        <Link to="/recebidos">Voltar para lista</Link>
      </div>

      {loading && <p>Carregando recebido...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && recebido && (
        <section className="detail-card">
          <div>
            <strong>Cód. Parcela:</strong> {recebido.codParcela ?? 'N/A'}
          </div>
          <div>
            <strong>Data Recebimento:</strong> {recebido.dataRecebimento || 'N/A'}
          </div>
          <div>
            <strong>Valor Recebido:</strong> R$ {Number(recebido.valorRecebido ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Dias Atraso:</strong> {recebido.diasAtraso ?? 'N/A'}
          </div>
          <div>
            <strong>Mora Diária:</strong> R$ {Number(recebido.moraDiaria ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Valor Juros:</strong> R$ {Number(recebido.valorJuros ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Amortizado:</strong> R$ {Number(recebido.amortizado ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Banco:</strong> {recebido.banco || 'N/A'}
          </div>
          <div>
            <strong>Obs:</strong> {recebido.obs || 'N/A'}
          </div>
          <div>
            <strong>Forma Pagamento:</strong> {recebido.formaPagamento || 'N/A'}
          </div>
          <div>
            <strong>Cheque Comp:</strong> {recebido.chequeComp || 'N/A'}
          </div>
          <div>
            <strong>Num Cheque:</strong> {recebido.numCheque || 'N/A'}
          </div>
          <div>
            <strong>Status Tipo:</strong> {recebido.statusTipo || 'N/A'}
          </div>
          <div>
            <strong>Empresa:</strong> {recebido.empresa || 'N/A'}
          </div>
          <div>
            <strong>Cód. Cliente:</strong> {recebido.codCliente ?? 'N/A'}
          </div>
        </section>
      )}
    </DefaultLayout>
  );
}