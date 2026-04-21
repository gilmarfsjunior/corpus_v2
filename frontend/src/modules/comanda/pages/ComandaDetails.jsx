import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import DefaultLayout from '../../../shared/components/DefaultLayout';
import { obterComanda } from '../services/comandaService';
import { criarComandaItem, alterarStatusComandaItem } from '../services/comandaItemService';

export default function ComandaDetails() {
  const { id } = useParams();
  const [comanda, setComanda] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [itemForm, setItemForm] = useState({
    CodProduto: '',
    Descricao: '',
    Quantidade: '1',
    Valor: '',
  });
  const [itemLoading, setItemLoading] = useState(false);
  const [itemError, setItemError] = useState(null);

  const loadComanda = async () => {
    setLoading(true);
    setError(null);

    try {
      const data = await obterComanda(id);
      setComanda(data);
    } catch (err) {
      setError(err.message || 'Erro ao carregar a comanda');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!id) {
      setError('ID de comanda inválido');
      setLoading(false);
      return;
    }

    loadComanda();
  }, [id]);

  const handleItemChange = (event) => {
    const { name, value } = event.target;
    setItemForm((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleAddItem = async (event) => {
    event.preventDefault();
    setItemError(null);
    setItemLoading(true);

    try {
      await criarComandaItem(id, {
        CodProduto: itemForm.CodProduto ? Number(itemForm.CodProduto) : null,
        Descricao: itemForm.Descricao,
        Quantidade: itemForm.Quantidade ? Number(itemForm.Quantidade) : 0,
        Valor: itemForm.Valor ? Number(itemForm.Valor) : 0,
        Ativo: true,
      });
      setItemForm({ CodProduto: '', Descricao: '', Quantidade: '1', Valor: '' });
      await loadComanda();
    } catch (err) {
      setItemError(err.message || 'Erro ao adicionar item');
    } finally {
      setItemLoading(false);
    }
  };

  const handleToggleItemStatus = async (itemId) => {
    setItemError(null);
    setItemLoading(true);

    try {
      await alterarStatusComandaItem(id, itemId, false);
      await loadComanda();
    } catch (err) {
      setItemError(err.message || 'Erro ao remover item');
    } finally {
      setItemLoading(false);
    }
  };

  return (
    <DefaultLayout title={`Comanda ${id}`}>
      <div className="detail-actions">
        <Link to="/comandas">Voltar para lista</Link>
        {' | '}
        <Link to={`/comandas/${id}/editar`}>Editar comanda</Link>
      </div>

      {loading && <p>Carregando comanda...</p>}
      {error && <p className="error">{error}</p>}

      {!loading && comanda && (
        <>
          <section className="detail-card">
            <div>
              <strong>Empresa:</strong> {comanda.empresa || 'N/A'}
            </div>
            <div>
              <strong>Apartamento:</strong> {comanda.apartamentoId ?? 'N/A'}
            </div>
            <div>
              <strong>Entrada:</strong> {comanda.dataEntrada} {comanda.horaEntrada}
            </div>
            <div>
              <strong>Saída:</strong> {comanda.dataSaida} {comanda.horaSaida}
            </div>
            <div>
              <strong>Status:</strong> {comanda.concluido ? 'Concluída' : 'Aberta'}
            </div>
            <div>
              <strong>Total:</strong> R$ {Number(comanda.valorTotal ?? 0).toFixed(2)}
            </div>
            <div>
              <strong>Pagamento:</strong> Dinheiro R$ {Number(comanda.dinheiro ?? 0).toFixed(2)} | Cartão R$ {Number(comanda.cartao ?? 0).toFixed(2)} | Cheque R$ {Number(comanda.cheque ?? 0).toFixed(2)}
            </div>
          </section>

          <section className="detail-section">
            <h2>Itens da comanda</h2>
            {itemError && <p className="error">{itemError}</p>}
            {comanda.itens.length === 0 ? (
              <p>Não há itens ativos para esta comanda.</p>
            ) : (
              <table className="table">
                <thead>
                  <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor unitário</th>
                    <th>Total</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  {comanda.itens.map((item) => (
                    <tr key={item.id}>
                      <td>{item.produtoNome || item.descricao}</td>
                      <td>{item.quantidade}</td>
                      <td>R$ {Number(item.valor).toFixed(2)}</td>
                      <td>R$ {(Number(item.quantidade) * Number(item.valor)).toFixed(2)}</td>
                      <td>
                        <button type="button" onClick={() => handleToggleItemStatus(item.id)} disabled={itemLoading}>
                          Remover
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            )}

            <form className="comanda-item-form" onSubmit={handleAddItem}>
              <h3>Adicionar item</h3>
              <div className="form-grid">
                <label>
                  Produto (ID)
                  <input name="CodProduto" value={itemForm.CodProduto} onChange={handleItemChange} />
                </label>
                <label>
                  Descrição
                  <input name="Descricao" value={itemForm.Descricao} onChange={handleItemChange} />
                </label>
                <label>
                  Quantidade
                  <input type="number" name="Quantidade" value={itemForm.Quantidade} onChange={handleItemChange} />
                </label>
                <label>
                  Valor
                  <input type="number" step="0.01" name="Valor" value={itemForm.Valor} onChange={handleItemChange} />
                </label>
              </div>
              <button type="submit" disabled={itemLoading}>
                {itemLoading ? 'Adicionando...' : 'Adicionar item'}
              </button>
            </form>
          </section>
        </>
      )}
    </DefaultLayout>
  );
}
