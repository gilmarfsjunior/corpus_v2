import api from '../../../shared/services/api';

export async function listarComandas({ dataInicial = '', dataFinal = '', CodComanda = '', Concluido = '' } = {}) {
  const response = await api.get('/api/comandas', {
    params: {
      dataInicial,
      dataFinal,
      CodComanda,
      Concluido,
    },
  });

  return response.data.data;
}

export async function obterComanda(id) {
  const response = await api.get(`/api/comandas/${id}`);
  return response.data.data;
}

export async function criarComanda(payload) {
  const response = await api.post('/api/comandas', payload);
  return response.data.data;
}

export async function atualizarComanda(id, payload) {
  const response = await api.put(`/api/comandas/${id}`, payload);
  return response.data.data;
}
