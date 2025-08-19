
const modal = document.getElementById('modal');
const modalTitle = document.getElementById('modal-title');
const modalMessage = document.getElementById('modal-message');
const modalCancel = document.getElementById('modal-cancel');
const modalConfirm = document.getElementById('modal-confirm');

let currentForm = null;

// Open modal
function openModal(title, message, form, type = 'default'){
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    currentForm = form;

    // Change confirm button color
    if(type === 'cart'){
        modalConfirm.classList.remove('bg-red-500');
        modalConfirm.classList.add('bg-green-500');
    } else {
        modalConfirm.classList.remove('bg-green-500');
        modalConfirm.classList.add('bg-red-500');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Close modal
function closeModal(){
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentForm = null;
}

// Cancel button
modalCancel.addEventListener('click', closeModal);

// Confirm button submits the form
modalConfirm.addEventListener('click', () => {
    if(currentForm) currentForm.submit();
    closeModal();
});

// Logout confirmation
const logoutForm = document.getElementById('logout-form');
if (logoutForm) {
    logoutForm.addEventListener('submit', function(e){
        e.preventDefault();
        openModal('Logout Confirmation', 'Are you sure you want to logout?', logoutForm, 'logout');
    });
}

// Add to Cart confirmation
document.querySelectorAll('.cart-form button[type="submit"]').forEach(button => {
    button.addEventListener('click', function(e){
        e.preventDefault();
        const form = this.closest('form');
        const row = this.closest('tr');
        const itemName = row.querySelector('td:nth-child(2)').textContent;
        const quantityInput = row.querySelector('input[name="quantity"]');
        const quantityHidden = form.querySelector('input[name="quantity"]');

        // Copy quantity to hidden input inside the form
        if (quantityInput && quantityHidden) {
            quantityHidden.value = quantityInput.value;
        }

        openModal('Add to Cart', `Add ${quantityInput.value} of "${itemName}" to cart?`, form, 'cart');
    });
});

// Generic Edit/Delete modal (from dataset attributes)
document.querySelectorAll(".open-modal-btn").forEach(button => {
    button.addEventListener("click", () => {
        const form = document.getElementById("modal-form");
        const itemName = button.dataset.name || "this item";
        const actionUrl = button.dataset.action;

        form.action = actionUrl;
        openModal('Confirmation', `Are you sure you want to update/delete "${itemName}"?`, form, 'default');
    });

    
});

