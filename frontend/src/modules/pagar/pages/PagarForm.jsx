import { useEffect, useState } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { criarPagar, obterPagar, atualizarPagar } from '../services/pagarService';

export default function PagarForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    codNotaCompra: '',
    dataVencimento: '',
    valorParcela: '',
    saldoParcela: '',
    status: 'A',
    empresa: '',
    parcelaRef: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      return;
    }

    setLoading(true);
    obterPagar(id)
      .then((data) => {
        setForm({
          codNotaCompra: data.codNotaCompra ?? '',
          dataVencimento: data.dataVencimento ?? '',
          valorParcela: data.valorParcela ?? '',
          saldoParcela: data.saldoParcela ?? '',
          status: data.status ?? 'A',
          empresa: data.empresa ?? '',
          parcelaRef: data.parcelaRef ?? '',
        });
      })
      .catch((err) => setError(err.message || 'Erro ao carregar pagar'))
      .finally(() => setLoading(false));
  }, [id]);

  const handleChange = (event) => {
    const { name, value } = event.target;
    setForm((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const payload = {
        codNotaCompra: form.codNotaCompra ? Number(form.codNotaCompra) : null,
        dataVencimento: form.dataVencimento,
        valorParcela: form.valorParcela ? Number(form.valorParcela) : null,
        saldoParcela: form.saldoParcela ? Number(form.saldoParcela) : null,
        status: form.status,
        empresa: form.empresa,
        parcelaRef: form.parcelaRef ? Number(form.parcelaRef) : null,
      };

      if (id) {
        await atualizarPagar(id, payload);
      } else {
        await criarPagar(payload);
      }

      navigate('/pagars');
    } catch (err) {
      setError(err.message || 'Erro ao salvar o pagar');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title={id ? `Editar pagar ${id}` : 'Novo pagar'}>
      <div className="detail-actions">
        <Link to="/pagars">Voltar para lista</Link>
      </div>

      {error && <p className="error">{error}</p>}

      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Cód. Nota Compra
            <input name="codNotaCompra" value={form.codNotaCompra} onChange={handleChange} />
          </label>
          <label>
            Data Vencimento
            <input type="date" name="dataVencimento" value={form.dataVencimento} onChange={handleChange} />
          </label>
          <label>
            Valor Parcela
            <input type="number" step="0.01" name="valorParcela" value={form.valorParcela} onChange={handleChange} />
          </label>
          <label>
            Saldo Parcela
            <input type="number" step="0.01" name="saldoParcela" value={form.saldoParcela} onChange={handleChange} />
          </label>
          <label>
            Status
            <select name="status" value={form.status} onChange={handleChange}>
              <option value="A">Aberto</option>
              <option value="F">Fechado</option>
            </select>
          </label>
          <label>
            Empresa
            <input name="empresa" value={form.empresa} onChange={handleChange} />
          </label>
          <label>
            Parcela Ref
            <input name="parcelaRef" value={form.parcelaRef} onChange={handleChange} />
          </label>
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Salvando...' : 'Salvar pagar'}
        </button>
      </form>
    </DefaultLayout>
  );
}