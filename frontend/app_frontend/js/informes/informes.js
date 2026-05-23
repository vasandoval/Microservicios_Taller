const BASE_URL = 'http://127.0.0.1:8000';
const sprints   = [];
const historias = [];

const badgeEstado = (estado) => {
    const span = document.createElement('span');
    span.textContent = estado;
    span.className = `badge badge-${estado}`;
    return span;
};


const cargarFiltroSprints = () => {
    const select = document.getElementById('filtroSprint');
    sprints.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.nombre;
        select.appendChild(opt);
    });
};

const actualizarResumen = (lista) => {
    document.getElementById('totalHistorias').textContent   = lista.length;
    document.getElementById('totalNuevas').textContent      = lista.filter(h => h.estado === 'nueva').length;
    document.getElementById('totalActivas').textContent     = lista.filter(h => h.estado === 'activa').length;
    document.getElementById('totalFinalizadas').textContent = lista.filter(h => h.estado === 'finalizada').length;
    document.getElementById('totalImpedimentos').textContent = lista.filter(h => h.estado === 'impedimento').length;
};

const mostrarInforme = (lista) => {
    actualizarResumen(lista);

    const tbody = document.querySelector('#informesTB tbody');
    tbody.innerHTML = '';

    if (lista.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7">No hay historias con esos filtros.</td></tr>';
        return;
    }

    for (let h of lista) {
        const tr = document.createElement('tr');

        const sprint = sprints.find(s => s.id == h.sprint_id);

        const sprintTd = document.createElement('td'); sprintTd.textContent = sprint ? sprint.nombre : h.sprint_id;
        const tituloTd = document.createElement('td'); tituloTd.textContent = h.titulo;
        const respTd   = document.createElement('td'); respTd.textContent = h.responsable;
        const estadoTd = document.createElement('td'); estadoTd.appendChild(badgeEstado(h.estado));
        const puntosTd = document.createElement('td'); puntosTd.textContent = h.puntos;
        const finiTd   = document.createElement('td'); finiTd.textContent = h.fecha_creacion || '-';
        const ffinTd   = document.createElement('td'); ffinTd.textContent = h.fecha_finalizacion || '-';

        tr.appendChild(sprintTd);
        tr.appendChild(tituloTd);
        tr.appendChild(respTd);
        tr.appendChild(estadoTd);
        tr.appendChild(puntosTd);
        tr.appendChild(finiTd);
        tr.appendChild(ffinTd);
        tbody.appendChild(tr);
    }
};

const aplicarFiltros = () => {
    const sprintId     = document.getElementById('filtroSprint').value;
    const responsable  = document.getElementById('filtroResponsable').value.trim().toLowerCase();

    let resultado = [...historias];

    if (sprintId) {
        resultado = resultado.filter(h => h.sprint_id == sprintId);
    }
    if (responsable) {
        resultado = resultado.filter(h => h.responsable.toLowerCase().includes(responsable));
    }

    mostrarInforme(resultado);
};

const consultarSprints = async () => {
    try {
        const response = await fetch(`${BASE_URL}/sprints-v2`);
        const body = await response.json();
        body.forEach(item => sprints.push({
            id: item.id, nombre: item.nombre,
            fecha_inicio: item.fecha_inicio, fecha_fin: item.fecha_fin,
        }));
        cargarFiltroSprints();
    } catch (ex) {
        console.error('Error al consultar sprints:', ex);
    }
};

const consultarHistorias = async () => {
    try {
        const response = await fetch(`${BASE_URL}/historias-v2`);
        const body = await response.json();
        body.forEach(item => historias.push({
            id: item.id, titulo: item.titulo,
            responsable: item.responsable, estado: item.estado,
            puntos: item.puntos, sprint_id: item.sprint_id,
            fecha_creacion: item.fecha_creacion,
            fecha_finalizacion: item.fecha_finalizacion,
        }));
        mostrarInforme(historias);
    } catch (ex) {
        console.error('Error al consultar historias:', ex);
    }
    console.log('Fin del request informes...');
};

document.getElementById('btnFiltrar').addEventListener('click', aplicarFiltros);

const init = async () => {
    await consultarSprints();
    await consultarHistorias();
};
init();
