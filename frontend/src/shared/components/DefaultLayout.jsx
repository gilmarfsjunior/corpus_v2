import { Link, useNavigate } from 'react-router-dom';
import './layout.css';

export default function DefaultLayout({ title, children }) {
  const navigate = useNavigate();
  const user = JSON.parse(localStorage.getItem('user') || 'null');

  const handleLogout = () => {
    localStorage.removeItem('user');
    navigate('/login');
  };

  return (
    <div className="app-shell">
      <header className="app-header">
        <div className="header-top">
          <h1>{title}</h1>
          <div className="header-right">
            {user && (
              <div className="user-info">
                <span>Usuário: {user.nome} (Nível: {user.nivel})</span>
                <button onClick={handleLogout} className="logout-button">Sair</button>
              </div>
            )}
            <nav className="main-nav">
              <Link to="/produtos">Produtos</Link>
              <Link to="/comandas">Comandas</Link>
              <Link to="/movimentos">Movimentos</Link>
              <Link to="/vendas">Vendas</Link>
              <Link to="/caixas">Caixas</Link>
              <Link to="/recebimentos">Recebimentos</Link>
              <Link to="/recebidos">Recebidos</Link>
              <Link to="/pagars">Pagamentos a Pagar</Link>
              <Link to="/pagos">Pagamentos Efetuados</Link>
            </nav>
          </div>
        </div>
      </header>
      <main className="app-content">{children}</main>
    </div>
  );
}
