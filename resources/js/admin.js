// Confirmation Modal
const confirmModal = document.getElementById('confirm-modal');
const modalTitle = document.getElementById('modal-title');
const modalMessage = document.getElementById('modal-message');
const modalCancel = document.getElementById('modal-cancel');
const modalConfirm = document.getElementById('modal-confirm');
let currentForm = null;

function openModal(title, message, form){
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    currentForm = form;
    confirmModal.classList.remove('hidden');
    confirmModal.classList.add('flex');
}
function closeModal(){
    confirmModal.classList.add('hidden');
    confirmModal.classList.remove('flex');
    currentForm = null;
}
modalCancel.addEventListener('click', closeModal);
modalConfirm.addEventListener('click', () => {
    if(currentForm) currentForm.submit();
    closeModal();
});

// Logout confirmation
const logoutForm = document.getElementById('logout-form');
logoutForm.addEventListener('submit', function(e){
    e.preventDefault();
    openModal('Logout', 'Are you sure you want to logout?', logoutForm);
});

// Delete confirmation
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.closest('form').addEventListener('submit', function(e){
        e.preventDefault();
        openModal('Delete Item', 'Are you sure you want to delete this item?', this);
    });
});

// Edit modal
const editModal = document.getElementById('edit-modal');
const editForm = document.getElementById('edit-form');
const editName = document.getElementById('edit-name');
const editPrice = document.getElementById('edit-price');
const editCancel = document.getElementById('edit-cancel');

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function(){
        const id = this.dataset.id;
        const name = this.dataset.name;
        const price = this.dataset.price;

        editForm.action = `/items/${id}`;
        editName.value = name;
        editPrice.value = price;

        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    });
});
editCancel.addEventListener('click', () => {
    editModal.classList.add('hidden');
    editModal.classList.remove('flex');
});

// Add modal
const addModal = document.getElementById('add-modal');
const addBtn = document.getElementById('add-item-btn');
const addCancel = document.getElementById('add-cancel');

addBtn.addEventListener('click', () => {
    addModal.classList.remove('hidden');
    addModal.classList.add('flex');
});
addCancel.addEventListener('click', () => {
    addModal.classList.add('hidden');
    addModal.classList.remove('flex');
});