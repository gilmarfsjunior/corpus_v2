import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarComandas } from '../services/comandaService';

export default function ComandaList() {
  const [comandas, setComandas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [dataInicial, setDataInicial] = useState('');
  const [dataFinal, setDataFinal] = useState('');
  const [codComanda, setCodComanda] = useState('');
  const [concluido, setConcluido] = useState('');

  const loadComandas = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarComandas({ dataInicial, dataFinal, CodComanda: codComanda, Concluido: concluido });
      setComandas(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar comandas');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadComandas();
  }, []);

  return (
    <DefaultLayout title="Comandas">
      <div className="detail-actions">
        <Link to="/comandas/novo">Nova comanda</Link>
      </div>
      <form className="search-form" onSubmit={(event) => { event.preventDefault(); loadComandas(); }}>
        <input type="date" value={dataInicial} onChange={(event) => setDataInicial(event.target.value)} />
        <input type="date" value={dataFinal} onChange={(event) => setDataFinal(event.target.value)} />
        <input
          type="text"
          placeholder="Código da comanda"
          value={codComanda}
          onChange={(event) => setCodComanda(event.target.value)}
        />
        <select value={concluido} onChange={(event) => setConcluido(event.target.value)}>
          <option value="">Todos</option>
          <option value="S">Concluídos</option>
          <option value="N">Em aberto</option>
        </select>
        <button type="submit">Buscar</button>
      </form>

      {loading && <p>Carregando comandas...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Entrada</th>
              <th>Saída</th>
              <th>Movimento</th>
              <th>Total</th>
              <th>Concluído</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {comandas.map((comanda) => (
              <tr key={comanda.id}>
                <td>{comanda.id}</td>
                <td>{comanda.dataEntrada} {comanda.horaEntrada}</td>
                <td>{comanda.dataSaida} {comanda.horaSaida}</td>
                <td>{comanda.movimentoId ?? '-'}</td>
                <td>R$ {Number(comanda.valorTotal ?? 0).toFixed(2)}</td>
                <td>{comanda.concluido ? 'Sim' : 'Não'}</td>
                <td>
                  <Link to={`/comandas/${comanda.id}`}>Ver</Link>
                  {' | '}
                  <Link to={`/comandas/${comanda.id}/editar`}>Editar</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}
