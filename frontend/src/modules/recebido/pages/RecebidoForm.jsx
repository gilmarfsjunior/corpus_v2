import { useEffect, useState } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { criarRecebido, obterRecebido, atualizarRecebido } from '../services/recebidoService';

export default function RecebidoForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    codParcela: '',
    dataRecebimento: '',
    valorRecebido: '',
    diasAtraso: '',
    moraDiaria: '',
    valorJuros: '',
    amortizado: '',
    banco: '',
    obs: '',
    formaPagamento: '',
    chequeComp: 'N',
    numCheque: '',
    statusTipo: '',
    empresa: '',
    codCliente: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      return;
    }

    setLoading(true);
    obterRecebido(id)
      .then((data) => {
        setForm({
          codParcela: data.codParcela ?? '',
          dataRecebimento: data.dataRecebimento ?? '',
          valorRecebido: data.valorRecebido ?? '',
          diasAtraso: data.diasAtraso ?? '',
          moraDiaria: data.moraDiaria ?? '',
          valorJuros: data.valorJuros ?? '',
          amortizado: data.amortizado ?? '',
          banco: data.banco ?? '',
          obs: data.obs ?? '',
          formaPagamento: data.formaPagamento ?? '',
          chequeComp: data.chequeComp ?? 'N',
          numCheque: data.numCheque ?? '',
          statusTipo: data.statusTipo ?? '',
          empresa: data.empresa ?? '',
          codCliente: data.codCliente ?? '',
        });
      })
      .catch((err) => setError(err.message || 'Erro ao carregar recebido'))
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
        dataRecebimento: form.dataRecebimento,
        valorRecebido: form.valorRecebido ? Number(form.valorRecebido) : null,
        diasAtraso: form.diasAtraso ? Number(form.diasAtraso) : null,
        moraDiaria: form.moraDiaria ? Number(form.moraDiaria) : null,
        valorJuros: form.valorJuros ? Number(form.valorJuros) : null,
        amortizado: form.amortizado ? Number(form.amortizado) : null,
        banco: form.banco,
        obs: form.obs,
        formaPagamento: form.formaPagamento,
        chequeComp: form.chequeComp,
        numCheque: form.numCheque,
        statusTipo: form.statusTipo,
        empresa: form.empresa,
        codCliente: form.codCliente ? Number(form.codCliente) : null,
      };

      if (id) {
        await atualizarRecebido(id, payload);
      } else {
        await criarRecebido(payload);
      }

      navigate('/recebidos');
    } catch (err) {
      setError(err.message || 'Erro ao salvar o recebido');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title={id ? `Editar recebido ${id}` : 'Novo recebido'}>
      <div className="detail-actions">
        <Link to="/recebidos">Voltar para lista</Link>
      </div>

      {error && <p className="error">{error}</p>}

      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Cód. Parcela
            <input name="codParcela" value={form.codParcela} onChange={handleChange} />
          </label>
          <label>
            Data Recebimento
            <input type="date" name="dataRecebimento" value={form.dataRecebimento} onChange={handleChange} />
          </label>
          <label>
            Valor Recebido
            <input type="number" step="0.01" name="valorRecebido" value={form.valorRecebido} onChange={handleChange} />
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
            <select name="chequeComp" value={form.chequeComp} onChange={handleChange}>
              <option value="S">S</option>
              <option value="N">N</option>
            </select>
          </label>
          <label>
            Num Cheque
            <input name="numCheque" value={form.numCheque} onChange={handleChange} />
          </label>
          <label>
            Status Tipo
            <input name="statusTipo" value={form.statusTipo} onChange={handleChange} />
          </label>
          <label>
            Empresa
            <input name="empresa" value={form.empresa} onChange={handleChange} />
          </label>
          <label>
            Cód. Cliente
            <input name="codCliente" value={form.codCliente} onChange={handleChange} />
          </label>
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Salvando...' : 'Salvar recebido'}
        </button>
      </form>
    </DefaultLayout>
  );
}