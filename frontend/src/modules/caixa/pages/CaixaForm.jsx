import { useEffect, useState } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { criarCaixa, obterCaixa, atualizarCaixa } from '../services/caixaService';

export default function CaixaForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    dataCaixa: '',
    dataPrev1: '',
    dataPrev2: '',
    saldoInicial: '',
    saldoFinal: '',
    empresa: '',
    saldoInicialBanco: '',
    saldoFinalBanco: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      return;
    }

    setLoading(true);
    obterCaixa(id)
      .then((data) => {
        setForm({
          dataCaixa: data.dataCaixa ?? '',
          dataPrev1: data.dataPrev1 ?? '',
          dataPrev2: data.dataPrev2 ?? '',
          saldoInicial: data.saldoInicial ?? '',
          saldoFinal: data.saldoFinal ?? '',
          empresa: data.empresa ?? '',
          saldoInicialBanco: data.saldoInicialBanco ?? '',
          saldoFinalBanco: data.saldoFinalBanco ?? '',
        });
      })
      .catch((err) => setError(err.message || 'Erro ao carregar caixa'))
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
        dataCaixa: form.dataCaixa,
        dataPrev1: form.dataPrev1,
        dataPrev2: form.dataPrev2,
        saldoInicial: form.saldoInicial ? Number(form.saldoInicial) : null,
        saldoFinal: form.saldoFinal ? Number(form.saldoFinal) : null,
        empresa: form.empresa,
        saldoInicialBanco: form.saldoInicialBanco ? Number(form.saldoInicialBanco) : null,
        saldoFinalBanco: form.saldoFinalBanco ? Number(form.saldoFinalBanco) : null,
      };

      if (id) {
        await atualizarCaixa(id, payload);
      } else {
        await criarCaixa(payload);
      }

      navigate('/caixas');
    } catch (err) {
      setError(err.message || 'Erro ao salvar o caixa');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title={id ? `Editar caixa ${id}` : 'Novo caixa'}>
      <div className="detail-actions">
        <Link to="/caixas">Voltar para lista</Link>
      </div>

      {error && <p className="error">{error}</p>}

      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Data Caixa
            <input type="date" name="dataCaixa" value={form.dataCaixa} onChange={handleChange} />
          </label>
          <label>
            Data Prev 1
            <input type="date" name="dataPrev1" value={form.dataPrev1} onChange={handleChange} />
          </label>
          <label>
            Data Prev 2
            <input type="date" name="dataPrev2" value={form.dataPrev2} onChange={handleChange} />
          </label>
          <label>
            Saldo Inicial
            <input type="number" step="0.01" name="saldoInicial" value={form.saldoInicial} onChange={handleChange} />
          </label>
          <label>
            Saldo Final
            <input type="number" step="0.01" name="saldoFinal" value={form.saldoFinal} onChange={handleChange} />
          </label>
          <label>
            Empresa
            <input name="empresa" value={form.empresa} onChange={handleChange} />
          </label>
          <label>
            Saldo Inicial Banco
            <input type="number" step="0.01" name="saldoInicialBanco" value={form.saldoInicialBanco} onChange={handleChange} />
          </label>
          <label>
            Saldo Final Banco
            <input type="number" step="0.01" name="saldoFinalBanco" value={form.saldoFinalBanco} onChange={handleChange} />
          </label>
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Salvando...' : 'Salvar caixa'}
        </button>
      </form>
    </DefaultLayout>
  );
}