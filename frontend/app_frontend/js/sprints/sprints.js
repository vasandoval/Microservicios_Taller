const sprints = [];
let sprintActual = null;
const sprintsTabla = document.getElementById('sprintsTB');

const BASE_URL = 'http://127.0.0.1:8000';

const mostrarSprints = () => {
    const tbody = sprintsTabla.getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';

    if (sprints.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5">No hay sprints registrados.</td></tr>';
        return;
    }

    for (let item of sprints) {
        const tr = document.createElement('tr');

        const idTd      = document.createElement('td'); idTd.textContent = item.id;
        const nombreTd  = document.createElement('td'); nombreTd.textContent = item.nombre;
        const inicioTd  = document.createElement('td'); inicioTd.textContent = item.fecha_inicio;
        const finTd     = document.createElement('td'); finTd.textContent = item.fecha_fin;

        const accionesTd = document.createElement('td');

        const borrarBtn = document.createElement('button');
        borrarBtn.textContent = 'Borrar';
        borrarBtn.className = 'btn-borrar';
        borrarBtn.addEventListener('click', () => borrarSprint(item.id));

        const editarBtn = document.createElement('button');
        editarBtn.textContent = 'Editar';
        editarBtn.className = 'btn-editar';
        editarBtn.addEventListener('click', () => editarSprint(item));

        accionesTd.appendChild(borrarBtn);
        accionesTd.appendChild(editarBtn);

        tr.appendChild(idTd);
        tr.appendChild(nombreTd);
        tr.appendChild(inicioTd);
        tr.appendChild(finTd);
        tr.appendChild(accionesTd);

        tbody.appendChild(tr);
    }
};

const consultarSprints = async () => {
    try {
        if (sprints.length > 0) sprints.splice(0, sprints.length);
        const response = await fetch(`${BASE_URL}/sprints-v2`);
        const body = await response.json();
        body.forEach((item) => sprints.push({
            id: item.id,
            nombre: item.nombre,
            fecha_inicio: item.fecha_inicio,
            fecha_fin: item.fecha_fin,
        }));
        mostrarSprints();
    } catch (ex) {
        console.error('Error al consultar sprints:', ex);
    }
    console.log('Fin del request sprints...');
};

const borrarSprint = async (id) => {
    try {
        const response = await fetch(`${BASE_URL}/sprints-v2/${id}`, { method: 'delete' });
        if (response.status === 200) {
            showModal('Sprint borrado');
            consultarSprints();
        }
    } catch (ex) {
        console.error('Error al borrar sprint:', ex);
    }
    console.log('Fin del request borrar sprint...');
};

const editarSprint = (value) => {
    sprintActual = value;
    setSprintForm(sprintActual);
};

consultarSprints();
