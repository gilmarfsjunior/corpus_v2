import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { abrirMovimento } from '../services/movimentoService';

export default function MovimentoForm() {
  const navigate = useNavigate();
  const [form, setForm] = useState({
    CodUsuario: '',
    Empresa: '',
    Observacao: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

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
      await abrirMovimento({
        CodUsuario: form.CodUsuario ? Number(form.CodUsuario) : null,
        Empresa: form.Empresa,
        Observacao: form.Observacao,
      });
      navigate('/movimentos');
    } catch (err) {
      setError(err.message || 'Erro ao abrir movimento');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DefaultLayout title="Abrir movimento">
      <div className="detail-actions">
        <Link to="/movimentos">Voltar para lista</Link>
      </div>

      {error && <p className="error">{error}</p>}

      <form className="comanda-form" onSubmit={handleSubmit}>
        <div className="form-grid">
          <label>
            Usuário
            <input name="CodUsuario" value={form.CodUsuario} onChange={handleChange} />
          </label>
          <label>
            Empresa
            <input name="Empresa" value={form.Empresa} onChange={handleChange} />
          </label>
          <label>
            Observação
            <textarea name="Observacao" value={form.Observacao} onChange={handleChange} />
          </label>
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Abrindo...' : 'Abrir movimento'}
        </button>
      </form>
    </DefaultLayout>
  );
}
