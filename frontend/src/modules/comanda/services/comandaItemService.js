import api from '../../../shared/services/api';

export async function criarComandaItem(comandaId, payload) {
  const response = await api.post(`/api/comandas/${comandaId}/itens`, payload);
  return response.data.data;
}

export async function alterarStatusComandaItem(comandaId, itemId, ativo) {
  const response = await api.patch(`/api/comandas/${comandaId}/itens/${itemId}/status`, { ativo });
  return response.data.data;
}
