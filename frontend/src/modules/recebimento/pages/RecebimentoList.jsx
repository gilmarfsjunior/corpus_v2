import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarRecebimentos } from '../services/recebimentoService';

export default function RecebimentoList() {
  const [recebimentos, setRecebimentos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [codPedido, setCodPedido] = useState('');
  const [status, setStatus] = useState('');
  const [empresa, setEmpresa] = useState('');

  const loadRecebimentos = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarRecebimentos({ codPedido, status, empresa });
      setRecebimentos(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar recebimentos');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadRecebimentos();
  }, []);

  return (
    <DefaultLayout title="Recebimentos">
      <div className="detail-actions">
        <Link to="/recebimentos/novo">Novo recebimento</Link>
      </div>

      <form className="search-form" onSubmit={(event) => { event.preventDefault(); loadRecebimentos(); }}>
        <input
          type="number"
          placeholder="Cód. Pedido"
          value={codPedido}
          onChange={(event) => setCodPedido(event.target.value)}
        />
        <select value={status} onChange={(event) => setStatus(event.target.value)}>
          <option value="">Todos</option>
          <option value="A">Aberto</option>
          <option value="F">Fechado</option>
        </select>
        <input
          type="text"
          placeholder="Empresa"
          value={empresa}
          onChange={(event) => setEmpresa(event.target.value)}
        />
        <button type="submit">Buscar</button>
      </form>

      {loading && <p>Carregando recebimentos...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Pedido</th>
              <th>Vencimento</th>
              <th>Valor Parcela</th>
              <th>Saldo</th>
              <th>Recebido</th>
              <th>Status</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {recebimentos.map((recebimento) => (
              <tr key={recebimento.id}>
                <td>{recebimento.id}</td>
                <td>{recebimento.codPedido ?? '-'}</td>
                <td>{recebimento.dataVencimento || '-'}</td>
                <td>R$ {Number(recebimento.valorParcela ?? 0).toFixed(2)}</td>
                <td>R$ {Number(recebimento.saldoParcela ?? 0).toFixed(2)}</td>
                <td>R$ {Number(recebimento.valorRecebido ?? 0).toFixed(2)}</td>
                <td>{recebimento.status || '-'}</td>
                <td>
                  <Link to={`/recebimentos/${recebimento.id}`}>Ver</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}