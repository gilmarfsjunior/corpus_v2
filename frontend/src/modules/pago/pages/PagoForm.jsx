import { useEffect, useState } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { criarPago, obterPago, atualizarPago } from '../services/pagoService';

export default function PagoForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    codParcela: '',
    dataPagamento: '',
    valorPago: '',
    diasAtraso: '',
    moraDiaria: '',
    valorJuros: '',
    amortizado: '',
    banco: '',
    obs: '',
    formaPagamento: '',
    chequeComp: '',
    numCheque: '',
    empresa: '',
    statusTipo: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      return;
    }

    setLoading(true);
    obterPago(id)
      .then((data) => {
        setForm({
          codParcela: data.codParcela ?? '',
          dataPagamento: data.dataPagamento ?? '',
          valorPago: data.valorPago ?? '',
          diasAtraso: data.diasAtraso ?? '',
          moraDiaria: data.moraDiaria ?? '',
          valorJuros: data.valorJuros ?? '',
          amortizado: data.amortizado ?? '',
          banco: data.banco ?? '',
          obs: data.obs ?? '',
          formaPagamento: data.formaPagamento ?? '',
          chequeComp: data.chequeComp ?? '',
          numCheque: data.numCheque ?? '',
          empresa: data.empresa ?? '',
          statusTipo: data.statusTipo ?? '',
        });
      })
      .catch((err) => setError(err.message || 'Erro ao carregar pago'))
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
        codParcela: form.codParcela ? Number(form.codParcela) : null,
        dataPagamento: form.dataPagamento,
        valorPago: form.valorPago ? Number(form.valorPago) : null,
        diasAtraso: form.diasAtraso ? Number(form.diasAtraso) : null,
        moraDiaria: form.moraDiaria ? Number(form.moraDiaria) : null,
        valorJuros: form.valorJuros ? Number(form.valorJuros) : null,
        amortizado: form.amortizado ? Number(form.amortizado) : null,
        banco: form.banco,
        obs: form.obs,
        formaPagamento: form.formaPagamento,
        chequeComp: form.chequeComp,
        numCheque: form.numCheque,
        empresa: form.empresa,
        statusTipo: form.statusTipo,
      };

      if (id) {
        await atualizarPago(id, payload);
      } else {
        await criarPago(payload);
      }

      navigate('/pagos');
    } catch (err) {
      setError(err.message || 'Erro ao salvar o pago');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title={id ? `Editar pago ${id}` : 'Novo pago'}>
      <div className="detail-actions">
        <Link to="/pagos">Voltar para lista</Link>
      </div>

      {error && <p className="error">{error}</p>}

      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Cód. Parcela
            <input name="codParcela" value={form.codParcela} onChange={handleChange} />
          </label>
          <label>
            Data Pagamento
            <input type="date" name="dataPagamento" value={form.dataPagamento} onChange={handleChange} />
          </label>
          <label>
            Valor Pago
            <input type="number" step="0.01" name="valorPago" value={form.valorPago} onChange={handleChange} />
          </label>
          <label>
            Dias Atraso
            <input type="number" name="diasAtraso" value={form.diasAtraso} onChange={handleChange} />
          </label>
          <label>
            Mora Diária
            <input type="number" step="0.01" name="moraDiaria" value={form.moraDiaria} onChange={handleChange} />
          </label>
          <label>
            Valor Juros
            <input type="number" step="0.01" name="valorJuros" value={form.valorJuros} onChange={handleChange} />
          </label>
          <label>
            Amortizado
            <input type="number" step="0.01" name="amortizado" value={form.amortizado} onChange={handleChange} />
          </label>
          <label>
            Banco
            <input name="banco" value={form.banco} onChange={handleChange} />
          </label>
          <label>
            Obs
            <input name="obs" value={form.obs} onChange={handleChange} />
          </label>
          <label>
            Forma Pagamento
            <input name="formaPagamento" value={form.formaPagamento} onChange={handleChange} />
          </label>
          <label>
            Cheque Comp
            <input name="chequeComp" value={form.chequeComp} onChange={handleChange} />
          </label>
          <label>
            Núm. Cheque
            <input name="numCheque" value={form.numCheque} onChange={handleChange} />
          </label>
          <label>
            Empresa
            <input name="empresa" value={form.empresa} onChange={handleChange} />
          </label>
          <label>
            Status Tipo
            <input name="statusTipo" value={form.statusTipo} onChange={handleChange} />
          </label>
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Salvando...' : 'Salvar pago'}
        </button>
      </form>
    </DefaultLayout>
  );
}