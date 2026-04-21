import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterPago } from '../services/pagoService';

export default function PagoDetails() {
  const { id } = useParams();
  const [pago, setPago] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      setError('ID inválido');
      setLoading(false);
      return;
    }

    obterPago(id)
      .then((data) => setPago(data))
      .catch((err) => setError(err.message || 'Erro ao carregar pago'))
      .finally(() => setLoading(false));
  }, [id]);

  return (
    <DefaultLayout title={`Pago ${id}`}>
      <div className="detail-actions">
        <Link to="/pagos">Voltar para lista</Link>
      </div>

      {loading && <p>Carregando pago...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && pago && (
        <section className="detail-card">
          <div>
            <strong>Cód. Parcela:</strong> {pago.codParcela ?? 'N/A'}
          </div>
          <div>
            <strong>Data Pagamento:</strong> {pago.dataPagamento || 'N/A'}
          </div>
          <div>
            <strong>Valor Pago:</strong> R$ {Number(pago.valorPago ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Dias Atraso:</strong> {pago.diasAtraso ?? 'N/A'}
          </div>
          <div>
            <strong>Mora Diária:</strong> R$ {Number(pago.moraDiaria ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Valor Juros:</strong> R$ {Number(pago.valorJuros ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Amortizado:</strong> R$ {Number(pago.amortizado ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Banco:</strong> {pago.banco || 'N/A'}
          </div>
          <div>
            <strong>Obs:</strong> {pago.obs || 'N/A'}
          </div>
          <div>
            <strong>Forma Pagamento:</strong> {pago.formaPagamento || 'N/A'}
          </div>
          <div>
            <strong>Cheque Comp:</strong> {pago.chequeComp || 'N/A'}
          </div>
          <div>
            <strong>Núm. Cheque:</strong> {pago.numCheque || 'N/A'}
          </div>
          <div>
            <strong>Empresa:</strong> {pago.empresa || 'N/A'}
          </div>
          <div>
            <strong>Status Tipo:</strong> {pago.statusTipo || 'N/A'}
          </div>
        </section>
      )}
    </DefaultLayout>
  );
}