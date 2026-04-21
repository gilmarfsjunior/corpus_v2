import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterCaixa } from '../services/caixaService';

export default function CaixaDetails() {
  const { id } = useParams();
  const [caixa, setCaixa] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      setError('ID inválido');
      setLoading(false);
      return;
    }

    obterCaixa(id)
      .then((data) => setCaixa(data))
      .catch((err) => setError(err.message || 'Erro ao carregar caixa'))
      .finally(() => setLoading(false));
  }, [id]);

  return (
    <DefaultLayout title={`Caixa ${id}`}>
      <div className="detail-actions">
        <Link to="/caixas">Voltar para lista</Link>
      </div>

      {loading && <p>Carregando caixa...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && caixa && (
        <section className="detail-card">
          <div>
            <strong>Data Caixa:</strong> {caixa.dataCaixa || 'N/A'}
          </div>
          <div>
            <strong>Data Prev 1:</strong> {caixa.dataPrev1 || 'N/A'}
          </div>
          <div>
            <strong>Data Prev 2:</strong> {caixa.dataPrev2 || 'N/A'}
          </div>
          <div>
            <strong>Saldo Inicial:</strong> R$ {Number(caixa.saldoInicial ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Saldo Final:</strong> R$ {Number(caixa.saldoFinal ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Empresa:</strong> {caixa.empresa || 'N/A'}
          </div>
          <div>
            <strong>Saldo Inicial Banco:</strong> R$ {Number(caixa.saldoInicialBanco ?? 0).toFixed(2)}
          </div>
          <div>
            <strong>Saldo Final Banco:</strong> R$ {Number(caixa.saldoFinalBanco ?? 0).toFixed(2)}
          </div>
        </section>
      )}
    </DefaultLayout>
  );
}