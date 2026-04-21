import api from '../../../shared/services/api';

export async function listarPagars({ codNotaCompra = '', status = '', empresa = '' } = {}) {
  const response = await api.get('/api/pagars', {
    params: {
      codNotaCompra,
      status,
      empresa,
    },
  });

  return response.data.data;
}

export async function obterPagar(id) {
  const response = await api.get(`/api/pagars/${id}`);
  return response.data.data;
}

export async function criarPagar(payload) {
  const response = await api.post('/api/pagars', payload);
  return response.data.data;
}

export async function atualizarPagar(id, payload) {
  const response = await api.put(`/api/pagars/${id}`, payload);
  return response.data.data;
}

export async function pagarPagar(id) {
  const response = await api.put(`/api/pagars/${id}/pagar`);
  return response.data;
}