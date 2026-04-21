import api from '../../../shared/services/api';

export async function listarVendas({ dataVenda = '', status = '', empresa = '' } = {}) {
  const response = await api.get('/api/vendas', {
    params: {
      dataVenda,
      status,
      empresa,
    },
  });

  return response.data.data;
}

export async function obterVenda(id) {
  const response = await api.get(`/api/vendas/${id}`);
  return response.data.data;
}

export async function criarVenda(payload) {
  const response = await api.post('/api/vendas', payload);
  return response.data.data;
}

export async function atualizarVenda(id, payload) {
  const response = await api.put(`/api/vendas/${id}`, payload);
  return response.data.data;
}
