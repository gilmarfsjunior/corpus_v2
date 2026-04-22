import { useState, useEffect } from 'react';
import './ApartmentDashboard.css';

export default function ApartmentDashboard() {
  const [apartments, setApartments] = useState([]);
  const [groupedApartments, setGroupedApartments] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchApartments();
  }, []);

  const fetchApartments = async () => {
    try {
      setLoading(true);
      const response = await fetch('/api/apartamentos');
      
      if (!response.ok) {
        throw new Error('Erro ao buscar apartamentos');
      }

      const data = await response.json();
      setApartments(data.data || []);

      // Agrupar por tipo
      const grouped = {};
      (data.data || []).forEach((apt) => {
        const tipo = apt.tipoDescricao || 'Sem Tipo';
        if (!grouped[tipo]) {
          grouped[tipo] = [];
        }
        grouped[tipo].push(apt);
      });

      setGroupedApartments(grouped);
      setError(null);
    } catch (err) {
      setError(err.message);
      console.error('Erro:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleApartmentClick = (apartment) => {
    // Lógica para abrir a comanda
    console.log('Clicou no apartamento:', apartment);
    // Abre comanda em nova janela
    window.open(`/comanda/0?apartamento=${apartment.id}`, 'comanda_' + apartment.numero, 'width=900,height=700');
  };

  if (loading) {
    return <div className="apartment-dashboard"><p>Carregando apartamentos...</p></div>;
  }

  if (error) {
    return <div className="apartment-dashboard error"><p>Erro: {error}</p></div>;
  }

  return (
    <div className="apartment-dashboard">
      <h1>Apartamentos Disponíveis</h1>
      
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