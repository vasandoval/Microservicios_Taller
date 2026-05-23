const sprintForm = document.forms['sprintForm'];

const getSprintForm = () => ({
    nombre: sprintForm['nombre'].value,
    fecha_inicio: sprintForm['fecha_inicio'].value,
    fecha_fin: sprintForm['fecha_fin'].value,
});

const setSprintForm = (sprint) => {
    sprintForm['nombre'].value = sprint.nombre;
    sprintForm['fecha_inicio'].value = sprint.fecha_inicio;
    sprintForm['fecha_fin'].value = sprint.fecha_fin;
    document.querySelector('.form-titulo').textContent = 'Editar Sprint';
};

const validarSprint = (data) => {
    let valido = true;
    const campos = [
        { id: 'msgNombre', val: data.nombre },
        { id: 'msgInicio', val: data.fecha_inicio },
        { id: 'msgFin',    val: data.fecha_fin },
    ];
    campos.forEach(({ id, val }) => {
        const el = document.getElementById(id);
        el.style.display = val ? 'none' : 'block';
        if (!val) valido = false;
    });
    return valido;
};

const registrarSprint = async () => {
    try {
        const response = await fetch(`${BASE_URL}/sprints-v2`, {
            method: 'post',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(getSprintForm()),
        });
        const body = await response.json();
        if (response.status === 201) {
            showModal('Sprint guardado');
            sprints.push({ id: body.id, nombre: body.nombre, fecha_inicio: body.fecha_inicio, fecha_fin: body.fecha_fin });
            mostrarSprints();
            sprintForm.reset();
        }
    } catch (ex) {
        console.error('Error al registrar sprint:', ex);
    }
    console.log('Fin del request registrar sprint...');
};

const actualizarSprint = async () => {
    try {
        const response = await fetch(`${BASE_URL}/sprints-v2/${sprintActual.id}`, {
            method: 'put',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(getSprintForm()),
        });
        if (response.status === 200) {
            showModal('Sprint actualizado');
            consultarSprints();
            sprintActual = null;
            sprintForm.reset();
        }
    } catch (ex) {
        console.error('Error al actualizar sprint:', ex);
    }
    console.log('Fin del request actualizar sprint...');
};

sprintForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const data = getSprintForm();
    if (!validarSprint(data)) {
        showModal('Completa todos los campos obligatorios', 'error');
        return;
    }
    sprintActual ? actualizarSprint() : registrarSprint();
});

sprintForm.addEventListener('reset', () => {
    sprintActual = null;
    document.querySelector('.form-titulo').textContent = 'Crear Sprint';
    ['msgNombre', 'msgInicio', 'msgFin'].forEach(id => {
        document.getElementById(id).style.display = 'none';
    });
});
