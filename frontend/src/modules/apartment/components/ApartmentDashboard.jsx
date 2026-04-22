import { useState, useEffect } from 'react';
import './ApartmentDashboard.css';

export default function ApartmentDashboard() {
  const [apartments, setApartments] = useState([]);
  const [groupedApartments, setGroupedApartments] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isUpdating, setIsUpdating] = useState(false);

  useEffect(() => {
    fetchApartments();

    // Polling para atualização automática a cada 5 segundos quando a aba está ativa
    let pollingInterval;

    const startPolling = () => {
      pollingInterval = setInterval(() => {
        if (!document.hidden) { // Só faz polling se a aba estiver ativa
          fetchApartments();
        }
      }, 3000); // 3 segundos - mais frequente com cache inteligente
    };

    const stopPolling = () => {
      if (pollingInterval) {
        clearInterval(pollingInterval);
      }
    };

    // Iniciar polling
    startPolling();

    // Parar polling quando a aba fica invisível, reiniciar quando volta
    const handleVisibilityChange = () => {
      if (document.hidden) {
        stopPolling();
      } else {
        fetchApartments(); // Atualizar imediatamente quando volta
        startPolling();
      }
    };

    document.addEventListener('visibilitychange', handleVisibilityChange);

    // Listener para mensagens da popup de comanda (fallback)
    const handleMessage = (event) => {
      if (event.data === 'statusChanged') {
        fetchApartments(); // Recarregar apartamentos após mudança de status
      }
    };

    window.addEventListener('message', handleMessage);

    return () => {
      stopPolling();
      document.removeEventListener('visibilitychange', handleVisibilityChange);
      window.removeEventListener('message', handleMessage);
    };
  }, []);

  const fetchApartments = async (force = false) => {
    try {
      // Só mostrar loading na primeira carga
      if (apartments.length === 0) {
        setLoading(true);
      } else {
        setIsUpdating(true);
      }
      
      // Se não for forçado e temos dados recentes (menos de 30 segundos), pular
      if (!force && lastUpdate && (Date.now() - lastUpdate) < 30000) {
        setIsUpdating(false);
        return;
      }

      const response = await fetch('/api/apartamentos', {
        headers: {
          'Cache-Control': 'no-cache',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      
      if (!response.ok) {
        throw new Error('Erro ao buscar apartamentos');
      }

      const data = await response.json();
      const newApartments = data.data || [];

      // Verificar se houve mudanças reais nos dados
      const hasChanges = !apartments.length || 
        newApartments.length !== apartments.length ||
        newApartments.some((newApt, index) => {
          const oldApt = apartments[index];
          return !oldApt || 
                 newApt.id !== oldApt.id || 
                 newApt.status !== oldApt.status ||
                 newApt.numero !== oldApt.numero;
        });

      if (hasChanges || force) {
        setApartments(newApartments);

        // Agrupar por tipo
        const grouped = {};
        newApartments.forEach((apt) => {
          const tipo = apt.tipoDescricao || 'Sem Tipo';
          if (!grouped[tipo]) {
            grouped[tipo] = [];
          }
          grouped[tipo].push(apt);
        });

        setGroupedApartments(grouped);
        setLastUpdate(Date.now());
      }
      
      setError(null);
    } catch (err) {
      setError(err.message);
      console.error('Erro:', err);
    } finally {
      setLoading(false);
      setIsUpdating(false);
    }
  };

  const handleApartmentClick = (apartment) => {
    // Lógica para abrir a comanda
    console.log('Clicou no apartamento:', apartment);
    // Abre comanda em nova janela passando apenas o ID do apartamento
    window.open(`/comanda/apartamento/${apartment.id}`, 'comanda_' + apartment.numero, 'width=900,height=700');
  };

  if (loading) {
    return <div className="apartment-dashboard"><p>Carregando apartamentos...</p></div>;
  }

  if (error) {
    return <div className="apartment-dashboard error"><p>Erro: {error}</p></div>;
  }

  return (
    <div className="apartment-dashboard">
      <h1>
        Apartamentos Disponíveis
        {isUpdating && <span className="updating-indicator">⟳</span>}
        {lastUpdate && (
          <small className="last-update">
            Última atualização: {new Date(lastUpdate).toLocaleTimeString()}
          </small>
        )}
      </h1>
      
      <div className="apartment-groups">
        {Object.entries(groupedApartments).map(([tipo, apts]) => (
          <div key={tipo} className="apartment-group">
            <h2>{tipo}</h2>
            <div className="apartment-grid">
              {apts.map((apt) => (
                <button
                  key={apt.id}
                  className={`apartment-button ${apt.status.toLowerCase()}`}
                  style={{ backgroundColor: apt.statusColor }}
                  onClick={() => handleApartmentClick(apt)}
                  title={apt.statusLabel}
                >
                  <div className="apartment-number">{apt.numero}</div>
                  <div className="apartment-status">{apt.statusLabel}</div>
                </button>
              ))}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}