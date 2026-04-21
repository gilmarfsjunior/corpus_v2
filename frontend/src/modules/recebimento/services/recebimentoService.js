import api from '../../../shared/services/api';

export async function listarRecebimentos({ codPedido = '', status = '', empresa = '' } = {}) {
  const response = await api.get('/api/recebimentos', {
    params: {
      codPedido,
      status,
      empresa,
    },
  });

  return response.data.data;
}

export async function obterRecebimento(id) {
  const response = await api.get(`/api/recebimentos/${id}`);
  return response.data.data;
}

export async function criarRecebimento(payload) {
  const response = await api.post('/api/recebimentos', payload);
  return response.data.data;
}

export async function atualizarRecebimento(id, payload) {
  const response = await api.put(`/api/recebimentos/${id}`, payload);
  return response.data.data;
}

export async function receberRecebimento(id, payload) {
  const response = await api.put(`/api/recebimentos/${id}/receber`, payload);
  return response.data;
}