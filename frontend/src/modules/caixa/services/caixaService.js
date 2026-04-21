import api from '../../../shared/services/api';

export async function listarCaixas({ dataCaixa = '', empresa = '' } = {}) {
  const response = await api.get('/api/caixas', {
    params: {
      dataCaixa,
      empresa,
    },
  });

  return response.data.data;
}

export async function obterCaixa(id) {
  const response = await api.get(`/api/caixas/${id}`);
  return response.data.data;
}

export async function criarCaixa(payload) {
  const response = await api.post('/api/caixas', payload);
  return response.data.data;
}

export async function atualizarCaixa(id, payload) {
  const response = await api.put(`/api/caixas/${id}`, payload);
  return response.data.data;
}

export async function fecharCaixa(id, payload) {
  const response = await api.put(`/api/caixas/${id}/fechar`, payload);
  return response.data;
}