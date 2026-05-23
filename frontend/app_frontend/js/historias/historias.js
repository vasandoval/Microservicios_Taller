/*  VARIABLES  */
const sprints = [];
const historias = [];
let historiaActual = null;
const BASE_URL = 'http://127.0.0.1:8000';

/*  BADGE  */
const badgeEstado = (estado) => {
    const span = document.createElement('span');
    span.textContent = estado;
    span.className = `badge badge-${estado}`;
    return span;
};

/*  MOSTRAR AGRUPADO POR SPRINT  */
const mostrarHistoriasAgrupadas = () => {
    const contenedor = document.getElementById('historiasAgrupadas');
    contenedor.innerHTML = '';

    const filtroEstado = document.getElementById('filtroEstado').value;

    for (let sprint of sprints) {
        let historiasFiltradas = historias.filter(h => h.sprint_id == sprint.id);
        if (filtroEstado) {
            historiasFiltradas = historiasFiltradas.filter(h => h.estado === filtroEstado);
        }

        // Siempre muestra el sprint, aunque no tenga historias tras filtrar
        const grupo = document.createElement('div');
        grupo.className = 'sprint-grupo';

        const header = document.createElement('div');
        header.className = 'sprint-grupo__header';
        header.innerHTML = `
            <span class="sprint-grupo__nombre">🗂️ ${sprint.nombre}</span>
            <span class="sprint-grupo__fechas">${sprint.fecha_inicio} → ${sprint.fecha_fin}</span>
        `;

        grupo.appendChild(header);

        if (historiasFiltradas.length === 0) {
            const sinH = document.createElement('div');
            sinH.className = 'sin-historias';
            sinH.textContent = 'Sin historias' + (filtroEstado ? ` con estado "${filtroEstado}"` : '') + '.';
            grupo.appendChild(sinH);
        } else {
            const tabla = document.createElement('table');
            tabla.innerHTML = `
                <thead>
                  <tr>
                    <th>Título</th>
                    <th>Responsable</th>
                    <th>Estado</th>
                    <th>Puntos</th>
                    <th>F. Inicio</th>
                    <th>F. Fin</th>
                    <th>Acciones</th>
                  </tr>
                </thead>`;
            const tbody = document.createElement('tbody');

            for (let h of historiasFiltradas) {
                const tr = document.createElement('tr');

                const tituloTd = document.createElement('td'); tituloTd.textContent = h.titulo;
                const respTd   = document.createElement('td'); respTd.textContent = h.responsable;
                const estadoTd = document.createElement('td'); estadoTd.appendChild(badgeEstado(h.estado));
                const puntosTd = document.createElement('td'); puntosTd.textContent = h.puntos;
                const finiTd   = document.createElement('td'); finiTd.textContent = h.fecha_creacion || '-';
                const ffinTd   = document.createElement('td'); ffinTd.textContent = h.fecha_finalizacion || '-';

                const accionesTd = document.createElement('td');
                const borrarBtn  = document.createElement('button');
                borrarBtn.textContent = 'Borrar';
                borrarBtn.className = 'btn-borrar';
                borrarBtn.addEventListener('click', () => borrarHistoria(h.id));

                const editarBtn = document.createElement('button');
                editarBtn.textContent = 'Editar';
                editarBtn.className = 'btn-editar';
                editarBtn.addEventListener('click', () => editarHistoria(h));

                accionesTd.appendChild(borrarBtn);
                accionesTd.appendChild(editarBtn);

                tr.appendChild(tituloTd);
                tr.appendChild(respTd);
                tr.appendChild(estadoTd);
                tr.appendChild(puntosTd);
                tr.appendChild(finiTd);
                tr.appendChild(ffinTd);
                tr.appendChild(accionesTd);
                tbody.appendChild(tr);
            }

            tabla.appendChild(tbody);
            grupo.appendChild(tabla);
        }

        contenedor.appendChild(grupo);
    }
};

/*  CARGAR SPRINTS EN SELECT  */
const cargarSprintsEnSelect = () => {
    const select = document.getElementById('selectSprint');
    while (select.options.length > 1) select.remove(1);
    sprints.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.nombre;
        select.appendChild(opt);
    });
};

/*  CONSULTAR SPRINTS  */
const consultarSprints = async () => {
    try {
        const response = await fetch(`${BASE_URL}/sprints-v2`);
        const body = await response.json();
        body.forEach(item => sprints.push({
            id: item.id, nombre: item.nombre,
            fecha_inicio: item.fecha_inicio, fecha_fin: item.fecha_fin,
        }));
        cargarSprintsEnSelect();
    } catch (ex) {
        console.error('Error al consultar sprints:', ex);
    }
};

/*  CONSULTAR HISTORIAS  */
const consultarHistorias = async () => {
    try {
        if (historias.length > 0) historias.splice(0, historias.length);
        const response = await fetch(`${BASE_URL}/historias-v2`);
        const body = await response.json();
        body.forEach(item => historias.push({
            id: item.id, titulo: item.titulo,
            descripcion: item.descripcion, responsable: item.responsable,
            estado: item.estado, puntos: item.puntos,
            sprint_id: item.sprint_id,
            fecha_creacion: item.fecha_creacion,
            fecha_finalizacion: item.fecha_finalizacion,
        }));
        mostrarHistoriasAgrupadas();
    } catch (ex) {
        console.error('Error al consultar historias:', ex);
    }
    console.log('Fin del request historias...');
};

/*  BORRAR  */
const borrarHistoria = async (id) => {
    try {
        const response = await fetch(`${BASE_URL}/historias-v2/${id}`, { method: 'delete' });
        if (response.status === 200) {
            showModal('Historia borrada');
            consultarHistorias();
        }
    } catch (ex) {
        console.error('Error al borrar historia:', ex);
    }
};

/*  EDITAR  */
const editarHistoria = (value) => {
    historiaActual = value;
    setHistoriaForm(historiaActual);
};

/*  FILTRO  */
document.getElementById('filtroEstado').addEventListener('change', () => {
    mostrarHistoriasAgrupadas();
});

/*  INICIO  */
const init = async () => {
    await consultarSprints();
    await consultarHistorias();
};
init();
