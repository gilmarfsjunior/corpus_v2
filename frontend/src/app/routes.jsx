import { Routes, Route, Navigate } from 'react-router-dom';
import Home from '../modules/apartment/pages/Home';
import ProdutoList from '../modules/produto/pages/ProdutoList';
import ComandaList from '../modules/comanda/pages/ComandaList';
import ComandaDetails from '../modules/comanda/pages/ComandaDetails';
import ComandaForm from '../modules/comanda/pages/ComandaForm';
import ComandaPopup from '../modules/comanda/pages/ComandaPopup';
import MovimentoList from '../modules/movimento/pages/MovimentoList';
import MovimentoDetails from '../modules/movimento/pages/MovimentoDetails';
import MovimentoForm from '../modules/movimento/pages/MovimentoForm';
import VendaList from '../modules/venda/pages/VendaList';
import VendaDetails from '../modules/venda/pages/VendaDetails';
import VendaForm from '../modules/venda/pages/VendaForm';
import CaixaList from '../modules/caixa/pages/CaixaList';
import CaixaDetails from '../modules/caixa/pages/CaixaDetails';
import CaixaForm from '../modules/caixa/pages/CaixaForm';
import RecebimentoList from '../modules/recebimento/pages/RecebimentoList';
import RecebimentoDetails from '../modules/recebimento/pages/RecebimentoDetails';
import RecebimentoForm from '../modules/recebimento/pages/RecebimentoForm';
import RecebidoList from '../modules/recebido/pages/RecebidoList';
import RecebidoDetails from '../modules/recebido/pages/RecebidoDetails';
import RecebidoForm from '../modules/recebido/pages/RecebidoForm';
import PagarList from '../modules/pagar/pages/PagarList';
import PagarDetails from '../modules/pagar/pages/PagarDetails';
import PagarForm from '../modules/pagar/pages/PagarForm';
import PagoList from '../modules/pago/pages/PagoList';
import PagoDetails from '../modules/pago/pages/PagoDetails';
import PagoForm from '../modules/pago/pages/PagoForm';
import Login from '../modules/login/pages/Login';
import ProtectedRoute from '../shared/components/ProtectedRoute';

export default function AppRoutes() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route path="/" element={<ProtectedRoute><Home /></ProtectedRoute>} />
      <Route path="/comanda/:id" element={<ComandaPopup />} />
      <Route path="/produtos" element={<ProtectedRoute><ProdutoList /></ProtectedRoute>} />
      <Route path="/comandas" element={<ProtectedRoute><ComandaList /></ProtectedRoute>} />
      <Route path="/comandas/novo" element={<ProtectedRoute><ComandaForm /></ProtectedRoute>} />
      <Route path="/comandas/:id" element={<ProtectedRoute><ComandaDetails /></ProtectedRoute>} />
      <Route path="/comandas/:id/editar" element={<ProtectedRoute><ComandaForm /></ProtectedRoute>} />
      <Route path="/movimentos" element={<ProtectedRoute><MovimentoList /></ProtectedRoute>} />
      <Route path="/movimentos/novo" element={<ProtectedRoute><MovimentoForm /></ProtectedRoute>} />
      <Route path="/movimentos/:id" element={<ProtectedRoute><MovimentoDetails /></ProtectedRoute>} />
      <Route path="/vendas" element={<ProtectedRoute><VendaList /></ProtectedRoute>} />
      <Route path="/vendas/novo" element={<ProtectedRoute><VendaForm /></ProtectedRoute>} />
      <Route path="/vendas/:id" element={<ProtectedRoute><VendaDetails /></ProtectedRoute>} />
      <Route path="/caixas" element={<ProtectedRoute><CaixaList /></ProtectedRoute>} />
      <Route path="/caixas/novo" element={<ProtectedRoute><CaixaForm /></ProtectedRoute>} />
      <Route path="/caixas/:id" element={<ProtectedRoute><CaixaDetails /></ProtectedRoute>} />
      <Route path="/recebimentos" element={<ProtectedRoute><RecebimentoList /></ProtectedRoute>} />
      <Route path="/recebimentos/novo" element={<ProtectedRoute><RecebimentoForm /></ProtectedRoute>} />
      <Route path="/recebimentos/:id" element={<ProtectedRoute><RecebimentoDetails /></ProtectedRoute>} />
      <Route path="/recebidos" element={<ProtectedRoute><RecebidoList /></ProtectedRoute>} />
      <Route path="/recebidos/novo" element={<ProtectedRoute><RecebidoForm /></ProtectedRoute>} />
      <Route path="/recebidos/:id" element={<ProtectedRoute><RecebidoDetails /></ProtectedRoute>} />
      <Route path="/pagars" element={<ProtectedRoute><PagarList /></ProtectedRoute>} />
      <Route path="/pagars/novo" element={<ProtectedRoute><PagarForm /></ProtectedRoute>} />
      <Route path="/pagars/:id" element={<ProtectedRoute><PagarDetails /></ProtectedRoute>} />
      <Route path="/pagos" element={<ProtectedRoute><PagoList /></ProtectedRoute>} />
      <Route path="/pagos/novo" element={<ProtectedRoute><PagoForm /></ProtectedRoute>} />
      <Route path="/pagos/:id" element={<ProtectedRoute><PagoDetails /></ProtectedRoute>} />
    </Routes>
  );
}
