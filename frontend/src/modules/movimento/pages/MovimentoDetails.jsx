import { useEffect, useState } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterMovimento, fecharMovimento } from '../services/movimentoService';

export default function MovimentoDetails() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [movimento, setMovimento] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [closing, setClosing] = useState(false);
  const [observacao, setObservacao] = useState('');

  const loadMovimento = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await obterMovimento(id);
      setMovimento(data);
      setObservacao(data.observacao || '');
    } catch (err) {
      setError(err.message || 'Erro ao carregar movimento');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!id) {
      setError('ID inválido');
      setLoading(false);
      return;
    }
    loadMovimento();
  }, [id]);

  const handleClose = async () => {
    setClosing(true);
    setError(null);

    try {
      await fecharMovimento(id, { Observacao: observacao });
      await loadMovimento();
    } catch (err) {
      setError(err.message || 'Erro ao fechar movimento');
    } finally {
      setClosing(false);
    }
  };

  return (
    <DefaultLayout title={`Movimento ${id}`}>
      <div className="detail-actions">
        <Link to="/movimentos">Voltar para lista</Link>
      </div>

      {loading && <p>Carregando movimento...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && movimento && (
        <>
          <section className="detail-card">
            <div>
              <strong>Usuário:</strong> {movimento.usuarioId ?? 'N/A'}
            </div>
            <div>
              <strong>Empresa:</strong> {movimento.empresa || 'N/A'}
            </div>
            <div>
              <strong>Abertura:</strong> {movimento.dataAbertura} {movimento.horaAbertura}
            </div>
            <div>
              <strong>Fechamento:</strong> {movimento.dataFechamento || '-'} {movimento.horaFechamento || ''}
            </div>
            <div>
              <strong>Status:</strong> {movimento.status || 'N/A'}
            </div>
            <div>
              <strong>Observação:</strong> {movimento.observacao || 'Sem observação'}
            </div>
          </section>

          {movimento.status !== 'F' && (
            <section className="detail-section">
              <h2>Fechar movimento</h2>
              <label>
                Observação
                <textarea value={observacao} onChange={(event) => setObservacao(event.target.value)} />
              </label>
              <button type="button" onClick={handleClose} disabled={closing}>
                {closing ? 'Fechando...' : 'Fechar movimento'}
              </button>
            </section>
          )}

          {movimento.status === 'F' && (
            <section className="detail-section">
              <p>Movimento fechado.</p>
              <button type="button" onClick={() => navigate('/movimentos')}>
                Voltar
              </button>
            </section>
          )}
        </>
      )}
    </DefaultLayout>
  );
}
