import api from '../../../shared/services/api';

export async function listarMovimentos({ dataAbertura = '', status = '', empresa = '' } = {}) {
  const response = await api.get('/api/movimentos', {
    params: {
      dataAbertura,
      status,
      empresa,
    },
  });

  return response.data.data;
}

export async function obterMovimento(id) {
  const response = await api.get(`/api/movimentos/${id}`);
  return response.data.data;
}

export async function abrirMovimento(payload) {
  const response = await api.post('/api/movimentos', payload);
  return response.data.data;
}

export async function fecharMovimento(id, payload) {
  const response = await api.put(`/api/movimentos/${id}/fechar`, payload);
  return response.data.data;
}
