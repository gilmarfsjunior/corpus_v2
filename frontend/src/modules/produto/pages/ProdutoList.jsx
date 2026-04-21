import { useEffect, useState } from 'react';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { listarProdutos } from '../services/produtoService';

export default function ProdutoList() {
  const [produtos, setProdutos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filtro, setFiltro] = useState('');

  const loadProdutos = async (search = '') => {
    setLoading(true);
    setError(null);

    try {
      const data = await listarProdutos(search);
      setProdutos(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar produtos');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProdutos();
  }, []);

  const handleSearch = (event) => {
    event.preventDefault();
    loadProdutos(filtro);
  };

  return (
    <DefaultLayout title="Produtos">
      <form onSubmit={handleSearch} className="search-form">
        <input
          type="text"
          placeholder="Buscar por descrição"
          value={filtro}
          onChange={(event) => setFiltro(event.target.value)}
        />
        <button type="submit">Buscar</button>
      </form>

      {loading && <p>Carregando produtos...</p>}
      {error && <p className="error">{error}</p>}
      {!loading && !error && (
        <table className="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Descrição</th>
              <th>Marca</th>
              <th>Unidade</th>
              <th>Estoque</th>
              <th>Preço</th>
              <th>Ativo</th>
              <th>Categoria</th>
            </tr>
          </thead>
          <tbody>
            {produtos.map((produto) => (
              <tr key={produto.id}>
                <td>{produto.id}</td>
                <td>{produto.descricao}</td>
                <td>{produto.marca || '-'}</td>
                <td>{produto.unidade || '-'}</td>
                <td>{produto.estoque ?? 0}</td>
                <td>R$ {Number(produto.precoVenda ?? 0).toFixed(2)}</td>
                <td>{produto.ativo ? 'Sim' : 'Não'}</td>
                <td>{produto.categoria || '-'}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </DefaultLayout>
  );
}
