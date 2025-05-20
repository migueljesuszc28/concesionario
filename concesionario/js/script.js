// Función mejorada para eliminar
function confirmarEliminacion(id, tipo) {
    if (confirm(`¿Está seguro de eliminar este ${tipo}? Esta acción no se puede deshacer.`)) {
        fetch(`../includes/eliminar.php?tipo=${tipo}&id=${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if(!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                // Mostrar notificación
                mostrarNotificacion(`${tipo} eliminado correctamente`, 'success');
                // Recargar después de 1.5 segundos
                setTimeout(() => window.location.reload(), 1500);
            } else {
                mostrarNotificacion(data.message, 'error');
            }
        })
        .catch(error => {
            mostrarNotificacion(`Error: ${error.message}`, 'error');
        });
    }
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion ${tipo}`;
    notificacion.textContent = mensaje;
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.classList.add('mostrar');
    }, 100);
    
    setTimeout(() => {
        notificacion.classList.remove('mostrar');
        setTimeout(() => document.body.removeChild(notificacion), 500);
    }, 3000);
}
// Cargar estadísticas
document.addEventListener('DOMContentLoaded', function() {
    fetch('../includes/estadisticas.php')
        .then(response => response.json())
        .then(data => {
            if (document.getElementById('total-vehiculos')) {
                document.getElementById('total-vehiculos').textContent = data.totalVehiculos;
            }
            if (document.getElementById('total-clientes')) {
                document.getElementById('total-clientes').textContent = data.totalClientes;
            }
        });
});

// Calcular cuota estimada
function calcularCuota() {
    const monto = parseFloat(document.getElementById('monto_financiado').value) || 0;
    const plazo = parseInt(document.getElementById('plazo_meses').value) || 1;
    const tasa = parseFloat(document.getElementById('tasa_interes').value) || 0;
    
    const tasaMensual = tasa / 100 / 12;
    const cuota = monto * (tasaMensual * Math.pow(1 + tasaMensual, plazo)) / (Math.pow(1 + tasaMensual, plazo) - 1);
    
    if(cuota && isFinite(cuota)) {
        document.getElementById('cuota-estimada').innerHTML = `
            <h5>Cuota Estimada</h5>
            <p><strong>$${cuota.toFixed(2)}</strong> mensuales por ${plazo} meses</p>
            <p>Total a pagar: <strong>$${(cuota * plazo).toFixed(2)}</strong></p>
        `;
    }
}

// Event listeners
['monto_financiado', 'plazo_meses', 'tasa_interes'].forEach(id => {
    document.getElementById(id).addEventListener('input', calcularCuota);
});

// Inicializar cálculo
calcularCuota();