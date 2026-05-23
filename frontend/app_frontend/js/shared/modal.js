const modal1 = document.getElementById('modal1');

const showModal = (text, tipo = 'ok') => {
    const msg = modal1.getElementsByTagName('p')[0];
    msg.textContent = text;
    msg.style.color = tipo === 'ok' ? '#1e8e3e' : '#c0392b';
    modal1.classList.remove('close');
};

const hideModal = () => {
    modal1.classList.add('close');
};

modal1.getElementsByTagName('button')[0].addEventListener('click', () => {
    hideModal();
});
