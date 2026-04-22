import { useEffect, useState } from 'react';
import { useParams, useSearchParams } from 'react-router-dom';
import { obterComanda } from '../services/comandaService';
import { criarComandaItem, alterarStatusComandaItem } from '../services/comandaItemService';
import './ComandaPopup.css';

export default function ComandaPopup() {
  const { id, apartmentId: routeApartmentId } = useParams();
  const [searchParams] = useSearchParams();
  const queryApartmentId = searchParams.get('apartamento');
  
  // Prioriza o apartmentId da rota, depois o da query string
  const apartmentId = routeApartmentId || queryApartmentId;
  
  const [comanda, setComanda] = useState(null);
  const [apartment, setApartment] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [updatingStatus, setUpdatingStatus] = useState(false);
  const [comandaForm, setComandaForm] = useState({
    Placa: '',
    ClienteComanda: '',
  });

  const loadComanda = async () => {
    setLoading(true);
    setError(null);

    try {
      let comandaData = null;

      if (id) {
        // Se tem ID direto, busca a comanda existente
        comandaData = await obterComanda(id);
      } else if (apartmentId) {
        // Se tem ID do apartamento, busca ou cria comanda
        const apartResponse = await fetch(`/api/apartamentos/${apartmentId}`);
        const { data: apartData } = await apartResponse.json();
        setApartment(apartData);

        // Busca ou cria comanda do apartamento
        const comandaResponse = await fetch(`/api/apartamentos/${apartmentId}/comanda`);
        const { data } = await comandaResponse.json();
        comandaData = data;
      }

      setComanda(comandaData);

      // Inicializar campos da comanda se existir
      if (comandaData) {
        setComandaForm({
          Placa: comandaData.placa || '',
          ClienteComanda: comandaData.clienteComanda || '',
        });
      }
    } catch (err) {
      console.error('Erro:', err);
      setError(err.message || 'Erro ao carregar a comanda');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadComanda();
  }, [id, apartmentId]);

  const handleComandaChange = (event) => {
    const { name, value } = event.target;
    setComandaForm((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleItemChange = (event) => {
    const { name, value } = event.target;
    setItemForm((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleAddItem = async (e) => {
    e.preventDefault();
    if (!comanda || !itemForm.CodProduto) {
      setError('Selecione um produto');
      return;
    }

    try {
      await criarComandaItem(comanda.id, itemForm);
      loadComanda();
      setItemForm({ CodProduto: '', Descricao: '', Quantidade: '1', Valor: '' });
    } catch (err) {
      setError('Erro ao adicionar item');
    }
  };

  const handleToggleItem = async (itemId) => {
    try {
      await alterarStatusComandaItem(comanda.id, itemId);
      loadComanda();
    } catch (err) {
      setError('Erro ao atualizar item');
    }
  };

  const handleStatusChange = async (newStatus) => {
    if (!apartment) return;

    setUpdatingStatus(true);
    try {
      const response = await fetch(`/api/apartamentos/${apartment.id}/status`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: newStatus }),
      });

      if (!response.ok) {
        throw new Error('Erro ao atualizar status');
      }

      // Recarregar dados após mudança de status
      loadComanda();
      
      // Notificar janela pai sobre mudança de status
      if (window.opener) {
        window.opener.postMessage('statusChanged', '*');
      }
    } catch (err) {
      setError('Erro ao alterar status do apartamento');
    } finally {
      setUpdatingStatus(false);
    }
  };

  if (loading) {
    return <div className="comanda-popup"><p>Carregando...</p></div>;
  }

  if (error) {
    return <div className="comanda-popup error"><p>Erro: {error}</p></div>;
  }

  if (!comanda) {
    return <div className="comanda-popup"><p>Comanda não encontrada</p></div>;
  }

  const apartmentInfo = apartment ? `${apartment.numero} (${apartment.tipoDescricao})` : comanda.apartamentoId;

  return (
    <div className="comanda-popup">
      <div className="comanda-header">
        <h2>Comanda #{comanda.id}</h2>
        <button className="close-btn" onClick={() => window.close()}>×</button>
      </div>

      <div className="comanda-info">
        <p><strong>Apartamento:</strong> {apartmentInfo}</p>
        <p><strong>Status:</strong> 
          <span 
            className="status-badge" 
            style={{ backgroundColor: apartment?.statusColor || '#cccccc' }}
          >
            {apartment?.statusLabel || 'N/A'}
          </span>
        </p>
        <p><strong>Usuário:</strong> {comanda.usuario || 'N/A'}</p>
        <p><strong>Entrada:</strong> {comanda.dataEntrada} às {comanda.horaEntrada}</p>
        
        {comanda && (apartment?.status === 'O' || apartment?.status === 'C') && (
          <div className="comanda-fields">
            <div className="field-group">
              <label htmlFor="placa">Placa:</label>
              <input
                id="placa"
                type="text"
                name="Placa"
                value={comandaForm.Placa}
                onChange={handleComandaChange}
                placeholder="Digite a placa do veículo"
              />
            </div>
            <div className="field-group">
              <label htmlFor="cliente">Cliente:</label>
              <input
                id="cliente"
                type="text"
                name="ClienteComanda"
                value={comandaForm.ClienteComanda}
                onChange={handleComandaChange}
                placeholder="Nome do cliente"
              />
            </div>
          </div>
        )}
      </div>

      <div className="comanda-items">
        <h3>Itens</h3>
        {comanda.itens && comanda.itens.length > 0 ? (
          <table className="items-table">
            <thead>
              <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Valor</th>
                <th>Total</th>
                {(apartment?.status === 'O' || apartment?.status === 'C') && <th>Ação</th>}
              </tr>
            </thead>
            <tbody>
              {comanda.itens.map((item) => (
                <tr key={item.id} className={item.ativo ? '' : 'inativo'}>
                  <td>{item.descricao}</td>
                  <td>{item.quantidade}</td>
                  <td>R$ {parseFloat(item.valor).toFixed(2)}</td>
                  <td>R$ {(item.quantidade * item.valor).toFixed(2)}</td>
                  {(apartment?.status === 'O' || apartment?.status === 'C') && (
                    <td>
                      <button
                        onClick={() => handleToggleItem(item.id)}
                        className={`toggle-btn ${item.ativo ? 'ativo' : 'inativo'}`}
                      >
                        {item.ativo ? 'Remover' : 'Readicionar'}
                      </button>
                    </td>
                  )}
                </tr>
              ))}
            </tbody>
          </table>
        ) : (
          <p className="no-items">Nenhum item adicionado</p>
        )}
      </div>

      {(apartment?.status === 'O' || apartment?.status === 'C') && (
        <div className="comanda-form">
          <h3>Adicionar Item</h3>
          <form onSubmit={handleAddItem}>
            <input
              type="text"
              name="Descricao"
              placeholder="Descrição do produto"
              value={itemForm.Descricao}
              onChange={handleItemChange}
              required
            />
            <input
              type="number"
              name="Quantidade"
              placeholder="Quantidade"
              value={itemForm.Quantidade}
              onChange={handleItemChange}
              min="1"
              required
            />
            <input
              type="number"
              name="Valor"
              placeholder="Valor"
              step="0.01"
              value={itemForm.Valor}
              onChange={handleItemChange}
              required
            />
            <button type="submit">Adicionar</button>
          </form>
        </div>
      )}

      <div className="comanda-footer">
        <div className="total">
          <strong>Total:</strong>
          <span>R$ {comanda.valorTotal ? parseFloat(comanda.valorTotal).toFixed(2) : '0.00'}</span>
        </div>
        <div className="actions">
          {updatingStatus && <p>Atualizando status...</p>}
          
          {apartment?.status === 'L' && (
            <button 
              className="btn-confirmar" 
              onClick={() => handleStatusChange('O')}
              disabled={updatingStatus}
            >
              Confirmar Entrada
            </button>
          )}
          
          {apartment?.status === 'O' && (
            <button 
              className="btn-conferencia" 
              onClick={() => handleStatusChange('C')}
              disabled={updatingStatus}
            >
              Ir para Conferência
            </button>
          )}
          
          {apartment?.status === 'C' && (
            <button 
              className="btn-finalizar" 
              onClick={() => handleStatusChange('R')}
              disabled={updatingStatus}
            >
              Finalizar
            </button>
          )}
          
          <button className="btn-cancelar" onClick={() => window.close()}>Cancelar</button>
        </div>
      </div>
    </div>
  );
}