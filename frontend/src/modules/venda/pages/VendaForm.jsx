import { useEffect, useState } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { criarVenda, obterVenda, atualizarVenda } from '../services/vendaService';

export default function VendaForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    CodCliente: '',
    CodVendedor: '',
    DataVenda: '',
    Usuario: '',
    Empresa: '',
    Data: '',
    Hora: '',
    TotalVenda: '',
    Desconto: '',
    DespesasViagem: '',
    Status: 'A',
    CodMovimento: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      return;
    }

    setLoading(true);
    obterVenda(id)
      .then((data) => {
        setForm({
          CodCliente: data.clienteId ?? '',
          CodVendedor: data.vendedorId ?? '',
          DataVenda: data.dataVenda ?? '',
          Usuario: data.usuario ?? '',
          Empresa: data.empresa ?? '',
          Data: data.data ?? '',
          Hora: data.hora ?? '',
          TotalVenda: data.totalVenda ?? '',
          Desconto: data.desconto ?? '',
          DespesasViagem: data.despesasViagem ?? '',
          Status: data.status ?? 'A',
          CodMovimento: data.movimentoId ?? '',
        });
      })
      .catch((err) => setError(err.message || 'Erro ao carregar venda'))
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
        CodCliente: form.CodCliente ? Number(form.CodCliente) : null,
        CodVendedor: form.CodVendedor ? Number(form.CodVendedor) : null,
        DataVenda: form.DataVenda,
        Usuario: form.Usuario,
        Empresa: form.Empresa,
        Data: form.Data,
        Hora: form.Hora,
        TotalVenda: form.TotalVenda ? Number(form.TotalVenda) : null,
        Desconto: form.Desconto ? Number(form.Desconto) : null,
        DespesasViagem: form.DespesasViagem ? Number(form.DespesasViagem) : null,
        Status: form.Status,
        CodMovimento: form.CodMovimento ? Number(form.CodMovimento) : null,
      };

      if (id) {
        await atualizarVenda(id, payload);
      } else {
        await criarVenda(payload);
      }

      navigate('/vendas');
    } catch (err) {
      setError(err.message || 'Erro ao salvar a venda');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title={id ? `Editar venda ${id}` : 'Nova venda'}>
      <div className="detail-actions">
        <Link to="/vendas">Voltar para lista</Link>
      </div>

      {error && <p className="error">{error}</p>}

      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Cliente
            <input name="CodCliente" value={form.CodCliente} onChange={handleChange} />
          </label>
          <label>
            Vendedor
            <input name="CodVendedor" value={form.CodVendedor} onChange={handleChange} />
          </label>
          <label>
            Data Venda
            <input type="date" name="DataVenda" value={form.DataVenda} onChange={handleChange} />
          </label>
          <label>
            Usuário
            <input name="Usuario" value={form.Usuario} onChange={handleChange} />
          </label>
          <label>
            Empresa
            <input name="Empresa" value={form.Empresa} onChange={handleChange} />
          </label>
          <label>
            Data
            <input type="date" name="Data" value={form.Data} onChange={handleChange} />
          </label>
          <label>
            Hora
            <input type="time" name="Hora" value={form.Hora} onChange={handleChange} />
          </label>
          <label>
            Total venda
            <input type="number" step="0.01" name="TotalVenda" value={form.TotalVenda} onChange={handleChange} />
          </label>
          <label>
            Desconto
            <input type="number" step="0.01" name="Desconto" value={form.Desconto} onChange={handleChange} />
          </label>
          <label>
            Despesas viagem
            <input type="number" step="0.01" name="DespesasViagem" value={form.DespesasViagem} onChange={handleChange} />
          </label>
          <label>
            Status
            <input name="Status" value={form.Status} onChange={handleChange} />
          </label>
          <label>
            Movimento
            <input name="CodMovimento" value={form.CodMovimento} onChange={handleChange} />
          </label>
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Salvando...' : 'Salvar venda'}
        </button>
      </form>
    </DefaultLayout>
  );
}
