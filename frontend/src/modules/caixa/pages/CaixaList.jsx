import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarCaixas } from '../services/caixaService';

export default function CaixaList() {
  const [caixas, setCaixas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [dataCaixa, setDataCaixa] = useState('');
  const [empresa, setEmpresa] = useState('');

  const loadCaixas = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarCaixas({ dataCaixa, empresa });
      setCaixas(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar caixas');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadCaixas();
  }, []);

  return (
    <DefaultLayout title="Caixas">
      <div className="detail-actions">
        <Link to="/caixas/novo">Novo caixa</Link>
      </div>

      <form className="search-form" onSubmit={(event) => { event.preventDefault(); loadCaixas(); }}>
        <input type="date" value={dataCaixa} onChange={(event) => setDataCaixa(event.target.value)} />
        <input
          type="text"
          placeholder="Empresa"
          value={empresa}
          onChange={(event) => setEmpresa(event.target.value)}
        />
        <button type="submit">Buscar</button>
      </form>

      {loading && <p>Carregando caixas...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Data</th>
              <th>Saldo Inicial</th>
              <th>Saldo Final</th>
              <th>Empresa</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {caixas.map((caixa) => (
              <tr key={caixa.id}>
                <td>{caixa.id}</td>
                <td>{caixa.dataCaixa || '-'}</td>
                <td>R$ {Number(caixa.saldoInicial ?? 0).toFixed(2)}</td>
                <td>R$ {Number(caixa.saldoFinal ?? 0).toFixed(2)}</td>
                <td>{caixa.empresa || '-'}</td>
                <td>
                  <Link to={`/caixas/${caixa.id}`}>Ver</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}