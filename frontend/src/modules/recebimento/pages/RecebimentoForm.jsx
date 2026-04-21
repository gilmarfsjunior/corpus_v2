import { useEffect, useState } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { criarRecebimento, obterRecebimento, atualizarRecebimento } from '../services/recebimentoService';

export default function RecebimentoForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    codPedido: '',
    dataVencimento: '',
    valorParcela: '',
    saldoParcela: '',
    valorRecebido: '',
    notaFParcela: '',
    dataPrevReceb: '',
    status: 'A',
    empresa: '',
    parcelaRef: '',
    ativa: 'S',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      return;
    }

    setLoading(true);
    obterRecebimento(id)
      .then((data) => {
        setForm({
          codPedido: data.codPedido ?? '',
          dataVencimento: data.dataVencimento ?? '',
          valorParcela: data.valorParcela ?? '',
          saldoParcela: data.saldoParcela ?? '',
          valorRecebido: data.valorRecebido ?? '',
          notaFParcela: data.notaFParcela ?? '',
          dataPrevReceb: data.dataPrevReceb ?? '',
          status: data.status ?? 'A',
          empresa: data.empresa ?? '',
          parcelaRef: data.parcelaRef ?? '',
          ativa: data.ativa ?? 'S',
        });
      })
      .catch((err) => setError(err.message || 'Erro ao carregar recebimento'))
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
        codPedido: form.codPedido ? Number(form.codPedido) : null,
        dataVencimento: form.dataVencimento,
        valorParcela: form.valorParcela ? Number(form.valorParcela) : null,
        saldoParcela: form.saldoParcela ? Number(form.saldoParcela) : null,
        valorRecebido: form.valorRecebido ? Number(form.valorRecebido) : null,
        notaFParcela: form.notaFParcela,
        dataPrevReceb: form.dataPrevReceb,
        status: form.status,
        empresa: form.empresa,
        parcelaRef: form.parcelaRef ? Number(form.parcelaRef) : null,
        ativa: form.ativa,
      };

      if (id) {
        await atualizarRecebimento(id, payload);
      } else {
        await criarRecebimento(payload);
      }

      navigate('/recebimentos');
    } catch (err) {
      setError(err.message || 'Erro ao salvar o recebimento');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title={id ? `Editar recebimento ${id}` : 'Novo recebimento'}>
      <div className="detail-actions">
        <Link to="/recebimentos">Voltar para lista</Link>
      </div>

      {error && <p className="error">{error}</p>}

      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Cód. Pedido
            <input name="codPedido" value={form.codPedido} onChange={handleChange} />
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
            Valor Recebido
            <input type="number" step="0.01" name="valorRecebido" value={form.valorRecebido} onChange={handleChange} />
          </label>
          <label>
            Nota Fiscal
            <input name="notaFParcela" value={form.notaFParcela} onChange={handleChange} />
          </label>
          <label>
            Data Prev. Receb
            <input type="date" name="dataPrevReceb" value={form.dataPrevReceb} onChange={handleChange} />
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
          <label>
            Ativa
            <select name="ativa" value={form.ativa} onChange={handleChange}>
              <option value="S">Sim</option>
              <option value="N">Não</option>
            </select>
          </label>
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Salvando...' : 'Salvar recebimento'}
        </button>
      </form>
    </DefaultLayout>
  );
}