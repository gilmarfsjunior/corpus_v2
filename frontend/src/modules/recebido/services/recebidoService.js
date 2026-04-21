import api from '../../../shared/services/api';

export async function listarRecebidos({ codParcela = '', empresa = '' } = {}) {
  const response = await api.get('/api/recebidos', {
    params: {
      codParcela,
      empresa,
    },
  });

  return response.data.data;
}

export async function obterRecebido(id) {
  const response = await api.get(`/api/recebidos/${id}`);
  return response.data.data;
}

export async function criarRecebido(payload) {
  const response = await api.post('/api/recebidos', payload);
  return response.data.data;
}

export async function atualizarRecebido(id, payload) {
  const response = await api.put(`/api/recebidos/${id}`, payload);
  return response.data.data;
}
