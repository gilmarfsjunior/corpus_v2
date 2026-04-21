import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarPagars } from '../services/pagarService';

export default function PagarList() {
  const [pagars, setPagars] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [codNotaCompra, setCodNotaCompra] = useState('');
  const [status, setStatus] = useState('');
  const [empresa, setEmpresa] = useState('');

  const loadPagars = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarPagars({ codNotaCompra, status, empresa });
      setPagars(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar pagars');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadPagars();
  }, []);

  return (
    <DefaultLayout title="Pagamentos a Pagar">
      <div className="detail-actions">
        <Link to="/pagars/novo">Novo pagar</Link>
      </div>

      <form className="search-form" onSubmit={(event) => { event.preventDefault(); loadPagars(); }}>
        <input
          type="number"
          placeholder="Cód. Nota Compra"
          value={codNotaCompra}
          onChange={(event) => setCodNotaCompra(event.target.value)}
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

      {loading && <p>Carregando pagars...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nota Compra</th>
              <th>Vencimento</th>
              <th>Valor Parcela</th>
              <th>Saldo</th>
              <th>Status</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {pagars.map((pagar) => (
              <tr key={pagar.id}>
                <td>{pagar.id}</td>
                <td>{pagar.codNotaCompra ?? '-'}</td>
                <td>{pagar.dataVencimento || '-'}</td>
                <td>R$ {Number(pagar.valorParcela ?? 0).toFixed(2)}</td>
                <td>R$ {Number(pagar.saldoParcela ?? 0).toFixed(2)}</td>
                <td>{pagar.status || '-'}</td>
                <td>
                  <Link to={`/pagars/${pagar.id}`}>Ver</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}