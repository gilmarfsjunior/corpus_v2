import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../../shared/services/api';
import './login.css';

export default function Login() {
  const [form, setForm] = useState({
    usuario: '',
    senha: '',
    empresa: '1',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const navigate = useNavigate();

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
      const response = await api.post('/api/login', form);
      const { user } = response.data;

      // Store user in localStorage for now
      localStorage.setItem('user', JSON.stringify(user));

      // Redirect to home
      navigate('/');
    } catch (err) {
      setError(err.response?.data?.message || 'Erro ao fazer login');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-container">
      <div className="login-box">
        <div className="login-logo">
          <h1>Sistema Corpus</h1>
        </div>

        {error && <p className="error">{error}</p>}

        <form className="login-form" onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="usuario">Usuário</label>
            <input
              type="text"
              id="usuario"
              name="usuario"
              value={form.usuario}
              onChange={handleChange}
              required
              autoFocus
            />
          </div>

          <div className="form-group">
            <label htmlFor="senha">Senha</label>
            <input
              type="password"
              id="senha"
              name="senha"
              value={form.senha}
              onChange={handleChange}
              required
            />
          </div>

          <div className="form-group" hidden>
            <label htmlFor="empresa">Empresa</label>
            <input
              type="text"
              id="empresa"
              name="empresa"
              value={form.empresa}
              onChange={handleChange}
              required
            />
          </div>

          <button type="submit" disabled={loading} className="login-button">
            {loading ? 'Entrando...' : 'Acessar'}
          </button>
        </form>
      </div>
    </div>
  );
}