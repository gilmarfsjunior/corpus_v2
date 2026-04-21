import api from '../../../shared/services/api';

export async function listarProdutos(filtro = '') {
  const response = await api.get('/api/produtos', {
    params: {
      q: filtro,
    },
  });
  return response.data.data;
}
