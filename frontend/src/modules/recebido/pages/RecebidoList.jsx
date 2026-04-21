import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarRecebidos } from '../services/recebidoService';

export default function RecebidoList() {
  const [recebidos, setRecebidos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [codParcela, setCodParcela] = useState('');
  const [empresa, setEmpresa] = useState('');

  const loadRecebidos = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarRecebidos({ codParcela, empresa });
      setRecebidos(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar recebidos');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadRecebidos();
  }, []);

  return (
    <DefaultLayout title="Recebidos">
      <div className="detail-actions">
        <Link to="/recebidos/novo">Novo recebido</Link>
      </div>

      <form className="search-form" onSubmit={(event) => { event.preventDefault(); loadRecebidos(); }}>
        <input
          type="number"
          placeholder="Cód. Parcela"
          value={codParcela}
          onChange={(event) => setCodParcela(event.target.value)}
        />
        <input
          type="text"
          placeholder="Empresa"
          value={empresa}
          onChange={(event) => setEmpresa(event.target.value)}
        />
        <button type="submit">Buscar</button>
      </form>

      {loading && <p>Carregando recebidos...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Parcela</th>
              <th>Recebimento</th>
              <th>Valor</th>
              <th>Banco</th>
              <th>Status</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {recebidos.map((recebido) => (
              <tr key={recebido.id}>
                <td>{recebido.id}</td>
                <td>{recebido.codParcela ?? '-'}</td>
                <td>{recebido.dataRecebimento || '-'}</td>
                <td>R$ {Number(recebido.valorRecebido ?? 0).toFixed(2)}</td>
                <td>{recebido.banco || '-'}</td>
                <td>{recebido.statusTipo || '-'}</td>
                <td>
                  <Link to={`/recebidos/${recebido.id}`}>Ver</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}