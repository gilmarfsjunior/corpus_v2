import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarVendas } from '../services/vendaService';

export default function VendaList() {
  const [vendas, setVendas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [dataVenda, setDataVenda] = useState('');
  const [status, setStatus] = useState('');
  const [empresa, setEmpresa] = useState('');

  const loadVendas = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarVendas({ dataVenda, status, empresa });
      setVendas(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar vendas');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadVendas();
  }, []);

  return (
    <DefaultLayout title="Vendas">
      <div className="detail-actions">
        <Link to="/vendas/novo">Nova venda</Link>
      </div>

      <form className="search-form" onSubmit={(event) => { event.preventDefault(); loadVendas(); }}>
        <input type="date" value={dataVenda} onChange={(event) => setDataVenda(event.target.value)} />
        <select value={status} onChange={(event) => setStatus(event.target.value)}>
          <option value="">Todos</option>
          <option value="A">Aberta</option>
          <option value="F">Fechada</option>
        </select>
        <input
          type="text"
          placeholder="Empresa"
          value={empresa}
          onChange={(event) => setEmpresa(event.target.value)}
        />
        <button type="submit">Buscar</button>
      </form>

      {loading && <p>Carregando vendas...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Data</th>
              <th>Total</th>
              <th>Status</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {vendas.map((venda) => (
              <tr key={venda.id}>
                <td>{venda.id}</td>
                <td>{venda.clienteId ?? '-'}</td>
                <td>{venda.vendedorId ?? '-'}</td>
                <td>{venda.dataVenda || '-'}</td>
                <td>R$ {Number(venda.totalVenda ?? 0).toFixed(2)}</td>
                <td>{venda.status || '-'}</td>
                <td>
                  <Link to={`/vendas/${venda.id}`}>Ver</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}
