import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarMovimentos, abrirMovimento } from '../services/movimentoService';

export default function MovimentoList() {
  const [movimentos, setMovimentos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [newMovement, setNewMovement] = useState({
    CodUsuario: '',
    Empresa: '',
    Observacao: '',
  });

  const loadMovimentos = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarMovimentos();
      setMovimentos(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar movimentos');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadMovimentos();
  }, []);

  const handleChange = (event) => {
    const { name, value } = event.target;
    setNewMovement((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleCreate = async (event) => {
    event.preventDefault();
    setError(null);
    setLoading(true);

    try {
      const payload = {
        CodUsuario: newMovement.CodUsuario ? Number(newMovement.CodUsuario) : null,
        Empresa: newMovement.Empresa,
        Observacao: newMovement.Observacao,
      };
      await abrirMovimento(payload);
      setNewMovement({ CodUsuario: '', Empresa: '', Observacao: '' });
      await loadMovimentos();
    } catch (err) {
      setError(err.message || 'Erro ao abrir movimento');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title="Movimentos">
      <div className="detail-actions">
        <Link to="/movimentos/novo">Abrir movimento</Link>
      </div>

      {error && <p className="error">{error}</p>}
      {loading && <p>Carregando movimentos...</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Usuário</th>
              <th>Empresa</th>
              <th>Abertura</th>
              <th>Fechamento</th>
              <th>Status</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {movimentos.map((movimento) => (
              <tr key={movimento.id}>
                <td>{movimento.id}</td>
                <td>{movimento.usuarioId ?? '-'}</td>
                <td>{movimento.empresa || '-'}</td>
                <td>{movimento.dataAbertura} {movimento.horaAbertura}</td>
                <td>{movimento.dataFechamento || '-'} {movimento.horaFechamento || ''}</td>
                <td>{movimento.status || '-'}</td>
                <td>
                  <Link to={`/movimentos/${movimento.id}`}>Ver</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}
