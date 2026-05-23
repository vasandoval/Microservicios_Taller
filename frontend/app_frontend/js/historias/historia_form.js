const historiaForm = document.forms['historiaForm'];

const getHistoriaForm = () => ({
    titulo: historiaForm['titulo'].value,
    descripcion: historiaForm['descripcion'].value,
    responsable: historiaForm['responsable'].value,
    estado: historiaForm['estado'].value,
    puntos: parseInt(historiaForm['puntos'].value) || 0,
    sprint_id: parseInt(historiaForm['sprint_id'].value) || null,
    fecha_creacion: historiaForm['fecha_creacion'].value || null,
    fecha_finalizacion: historiaForm['fecha_finalizacion'].value || null,
});

const setHistoriaForm = (h) => {
    historiaForm['titulo'].value = h.titulo;
    historiaForm['descripcion'].value = h.descripcion;
    historiaForm['responsable'].value = h.responsable;
    historiaForm['estado'].value = h.estado;
    historiaForm['puntos'].value = h.puntos;
    historiaForm['sprint_id'].value = h.sprint_id;
    historiaForm['fecha_creacion'].value = h.fecha_creacion || '';
    historiaForm['fecha_finalizacion'].value = h.fecha_finalizacion || '';
    document.querySelector('.form-titulo').textContent = 'Editar Historia';
};

const validarHistoria = (data) => {
    let valido = true;
    const campos = [
        { id: 'msgTitulo', val: data.titulo },
        { id: 'msgSprint', val: data.sprint_id },
    ];
    campos.forEach(({ id, val }) => {
        const el = document.getElementById(id);
        el.style.display = val ? 'none' : 'block';
        if (!val) valido = false;
    });
    return valido;
};

const registrarHistoria = async () => {
    try {
        const response = await fetch(`${BASE_URL}/historias-v2`, {
            method: 'post',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(getHistoriaForm()),
        });
        const body = await response.json();
        if (response.status === 201) {
            showModal('Historia guardada');
            historias.push({
                id: body.id, titulo: body.titulo, descripcion: body.descripcion,
                responsable: body.responsable, estado: body.estado, puntos: body.puntos,
                sprint_id: body.sprint_id, fecha_creacion: body.fecha_creacion,
                fecha_finalizacion: body.fecha_finalizacion,
            });
            mostrarHistoriasAgrupadas();
            historiaForm.reset();
        }
    } catch (ex) {
        console.error('Error al registrar historia:', ex);
    }
    console.log('Fin del request registrar historia...');
};

const actualizarHistoria = async () => {
    try {
        const response = await fetch(`${BASE_URL}/historias-v2/${historiaActual.id}`, {
            method: 'put',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(getHistoriaForm()),
        });
        if (response.status === 200) {
            showModal('Historia actualizada');
            consultarHistorias();
            historiaActual = null;
            historiaForm.reset();
        }
    } catch (ex) {
        console.error('Error al actualizar historia:', ex);
    }
    console.log('Fin del request actualizar historia...');
};

historiaForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const data = getHistoriaForm();
    if (!validarHistoria(data)) {
        showModal('Completa los campos obligatorios', 'error');
        return;
    }
    historiaActual ? actualizarHistoria() : registrarHistoria();
});

historiaForm.addEventListener('reset', () => {
    historiaActual = null;
    document.querySelector('.form-titulo').textContent = 'Crear Historia';
    ['msgTitulo', 'msgSprint'].forEach(id => {
        document.getElementById(id).style.display = 'none';
    });
});
