export function setupLogoutConfirmation(logoutSelector, modalElements) {
    const logoutForm = document.querySelector(logoutSelector);
    if (!logoutForm) return;

    const { modal, modalTitle, modalMessage, modalCancel, modalConfirm } = modalElements;

    let currentForm = null;

    function openModal(title, message, form) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        currentForm = form;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentForm = null;
    }

    modalCancel.addEventListener('click', closeModal);
    modalConfirm.addEventListener('click', () => {
        if (currentForm) currentForm.submit();
        closeModal();
    });

    logoutForm.addEventListener('submit', function (e) {
        e.preventDefault();
        openModal('Logout Confirmation', 'Are you sure you want to logout?', logoutForm);
    });
}
