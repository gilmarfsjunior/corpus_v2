import { useEffect, useState } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { criarComanda, obterComanda, atualizarComanda } from '../services/comandaService';

export default function ComandaForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    Empresa: '',
    CodApartamento: '',
    DataEntrada: '',
    HoraEntrada: '',
    DataSaida: '',
    HoraSaida: '',
    Placa: '',
    ValorTotal: '',
    ValorTotalConf: '',
    Usuario: '',
    UsuarioSaida: '',
    QuantHoras: '',
    TotalHoras: '',
    Concluido: false,
    CodMovimento: '',
    Dinheiro: '',
    Cheque: '',
    Cartao: '',
    ComandaTipo: '',
    ClienteComanda: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!id) {
      return;
    }

    setLoading(true);
    obterComanda(id)
      .then((data) => {
        setForm({
          Empresa: data.empresa ?? '',
          CodApartamento: data.apartamentoId ?? '',
          DataEntrada: data.dataEntrada ?? '',
          HoraEntrada: data.horaEntrada ?? '',
          DataSaida: data.dataSaida ?? '',
          HoraSaida: data.horaSaida ?? '',
          Placa: data.placa ?? '',
          ValorTotal: data.valorTotal ?? '',
          ValorTotalConf: data.valorTotalConf ?? '',
          Usuario: data.usuario ?? '',
          UsuarioSaida: data.usuarioSaida ?? '',
          QuantHoras: data.quantHoras ?? '',
          TotalHoras: data.totalHoras ?? '',
          Concluido: data.concluido ?? false,
          CodMovimento: data.movimentoId ?? '',
          Dinheiro: data.dinheiro ?? '',
          Cheque: data.cheque ?? '',
          Cartao: data.cartao ?? '',
          ComandaTipo: data.comandaTipo ?? '',
          ClienteComanda: data.clienteComanda ?? '',
        });
      })
      .catch((err) => setError(err.message || 'Erro ao carregar a comanda'))
      .finally(() => setLoading(false));
  }, [id]);

  const handleChange = (event) => {
    const { name, value, type, checked } = event.target;
    setForm((prev) => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value,
    }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoading(true);
    setError(null);

    const payload = {
      Empresa: form.Empresa,
      CodApartamento: form.CodApartamento ? Number(form.CodApartamento) : null,
      DataEntrada: form.DataEntrada,
      HoraEntrada: form.HoraEntrada,
      DataSaida: form.DataSaida,
      HoraSaida: form.HoraSaida,
      Placa: form.Placa,
      ValorTotal: form.ValorTotal ? Number(form.ValorTotal) : null,
      ValorTotalConf: form.ValorTotalConf ? Number(form.ValorTotalConf) : null,
      Usuario: form.Usuario,
      UsuarioSaida: form.UsuarioSaida,
      QuantHoras: form.QuantHoras ? Number(form.QuantHoras) : null,
      TotalHoras: form.TotalHoras ? Number(form.TotalHoras) : null,
      Concluido: form.Concluido,
      CodMovimento: form.CodMovimento ? Number(form.CodMovimento) : null,
      Dinheiro: form.Dinheiro ? Number(form.Dinheiro) : null,
      Cheque: form.Cheque ? Number(form.Cheque) : null,
      Cartao: form.Cartao ? Number(form.Cartao) : null,
      ComandaTipo: form.ComandaTipo,
      ClienteComanda: form.ClienteComanda,
    };

    try {
      if (id) {
        await atualizarComanda(id, payload);
      } else {
        await criarComanda(payload);
      }
      navigate('/comandas');
    } catch (err) {
      setError(err.message || 'Erro ao salvar a comanda');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title={id ? `Editar comanda ${id}` : 'Nova comanda'}>
      <div className="detail-actions">
        <Link to="/comandas">Voltar para lista</Link>
      </div>
      {error && <p className="error">{error}</p>}
      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Empresa
            <input name="Empresa" value={form.Empresa} onChange={handleChange} />
          </label>
          <label>
            Apartamento
            <input name="CodApartamento" value={form.CodApartamento} onChange={handleChange} />
          </label>
          <label>
            Data Entrada
            <input type="date" name="DataEntrada" value={form.DataEntrada} onChange={handleChange} />
          </label>
          <label>
            Hora Entrada
            <input type="time" name="HoraEntrada" value={form.HoraEntrada} onChange={handleChange} />
          </label>
          <label>
            Data Saída
            <input type="date" name="DataSaida" value={form.DataSaida} onChange={handleChange} />
          </label>
          <label>
            Hora Saída
            <input type="time" name="HoraSaida" value={form.HoraSaida} onChange={handleChange} />
          </label>
          <label>
            Placa
            <input name="Placa" value={form.Placa} onChange={handleChange} />
          </label>
          <label>
            Valor Total
            <input type="number" step="0.01" name="ValorTotal" value={form.ValorTotal} onChange={handleChange} />
          </label>
          <label>
            Valor Total Conf.
            <input type="number" step="0.01" name="ValorTotalConf" value={form.ValorTotalConf} onChange={handleChange} />
          </label>
          <label>
            Usuário
            <input name="Usuario" value={form.Usuario} onChange={handleChange} />
          </label>
          <label>
            Usuário Saída
            <input name="UsuarioSaida" value={form.UsuarioSaida} onChange={handleChange} />
          </label>
          <label>
            Quant. Horas
            <input type="number" name="QuantHoras" value={form.QuantHoras} onChange={handleChange} />
          </label>
          <label>
            Total Horas
            <input type="number" step="0.01" name="TotalHoras" value={form.TotalHoras} onChange={handleChange} />
          </label>
          <label>
            Movimento
            <input name="CodMovimento" value={form.CodMovimento} onChange={handleChange} />
          </label>
          <label>
            Dinheiro
            <input type="number" step="0.01" name="Dinheiro" value={form.Dinheiro} onChange={handleChange} />
          </label>
          <label>
            Cheque
            <input type="number" step="0.01" name="Cheque" value={form.Cheque} onChange={handleChange} />
          </label>
          <label>
            Cartão
            <input type="number" step="0.01" name="Cartao" value={form.Cartao} onChange={handleChange} />
          </label>
          <label>
            Tipo
            <input name="ComandaTipo" value={form.ComandaTipo} onChange={handleChange} />
          </label>
          <label>
            Cliente
            <input name="ClienteComanda" value={form.ClienteComanda} onChange={handleChange} />
          </label>
          <label className="checkbox-label">
            <input type="checkbox" name="Concluido" checked={form.Concluido} onChange={handleChange} />
            Concluído
          </label>
        </div>

        <button type="submit" disabled={loading}>
          {loading ? 'Salvando...' : 'Salvar comanda'}
        </button>
      </form>
    </DefaultLayout>
  );
}
