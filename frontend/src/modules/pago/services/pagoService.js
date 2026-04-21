import api from '../../../shared/services/api';

export async function listarPagos({ codParcela = '', empresa = '' } = {}) {
  const response = await api.get('/api/pagos', {
    params: {
      codParcela,
      empresa,
    },
  });

  return response.data.data;
}

export async function obterPago(id) {
  const response = await api.get(`/api/pagos/${id}`);
  return response.data.data;
}

export async function criarPago(payload) {
  const response = await api.post('/api/pagos', payload);
  return response.data.data;
}

export async function atualizarPago(id, payload) {
  const response = await api.put(`/api/pagos/${id}`, payload);
  return response.data.data;
}