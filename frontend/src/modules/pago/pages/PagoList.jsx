import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarPagos } from '../services/pagoService';

export default function PagoList() {
  const [pagos, setPagos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [codParcela, setCodParcela] = useState('');
  const [empresa, setEmpresa] = useState('');

  const loadPagos = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarPagos({ codParcela, empresa });
      setPagos(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar pagos');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadPagos();
  }, []);

  return (
    <DefaultLayout title="Pagamentos Efetuados">
      <div className="detail-actions">
        <Link to="/pagos/novo">Novo pago</Link>
      </div>

      <form className="search-form" onSubmit={(event) => { event.preventDefault(); loadPagos(); }}>
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

      {loading && <p>Carregando pagos...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Parcela</th>
              <th>Data Pagamento</th>
              <th>Valor Pago</th>
              <th>Forma</th>
              <th>Detalhes</th>
            </tr>
          </thead>
          <tbody>
            {pagos.map((pago) => (
              <tr key={pago.id}>
                <td>{pago.id}</td>
                <td>{pago.codParcela ?? '-'}</td>
                <td>{pago.dataPagamento || '-'}</td>
                <td>R$ {Number(pago.valorPago ?? 0).toFixed(2)}</td>
                <td>{pago.formaPagamento || '-'}</td>
                <td>
                  <Link to={`/pagos/${pago.id}`}>Ver</Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}