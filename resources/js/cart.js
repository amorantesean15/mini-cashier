document.addEventListener('DOMContentLoaded', () => {
const paymentMethod = document.getElementById('payment-method');
const cashSection = document.getElementById('cash-section');
const cashInput = document.getElementById('cash-received');
const changeAmount = document.getElementById('change-amount');
const qrSection = document.getElementById('qr-section');
const qrCodeImg = document.getElementById('qr-code');
const checkoutBtn = document.getElementById('checkout-btn');
const confirmModal = document.getElementById('confirm-modal');
const modalTotal = document.getElementById('modal-total');
const modalCash = document.getElementById('modal-cash');
const modalCashLabel = document.getElementById('modal-cash-label');
const modalChange = document.getElementById('modal-change');
const cancelBtn = document.getElementById('cancel-btn');
const confirmBtn = document.getElementById('confirm-btn');
 const checkoutForm = document.getElementById('checkout-form');
    const totalAmount = parseFloat(checkoutForm.dataset.total);


 
// Show/hide sections depending on payment method
paymentMethod.addEventListener('change', () => {
    if(paymentMethod.value === 'cash'){
        cashSection.style.display = 'block';
        qrSection.classList.add('hidden');
    } else {
        cashSection.style.display = 'none';
        qrSection.classList.remove('hidden');

        // Generate dummy QR code (using https://api.qrserver.com/v1/create-qr-code/)
        const dummyData = `PAYMENT: PHP ${totalAmount.toFixed(2)}`;
        qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(dummyData)}`;
    }
});

// Calculate change in real-time for cash
cashInput.addEventListener('input', () => {
    const cash = parseFloat(cashInput.value) || 0;
    const change = cash - totalAmount;
    changeAmount.textContent = change >= 0 ? change.toFixed(2) : '0.00';
});

// Open confirmation modal
checkoutBtn.addEventListener('click', () => {
    let cash = paymentMethod.value === 'cash' ? parseFloat(cashInput.value) || 0 : totalAmount;
    let change = cash - totalAmount;

    if(paymentMethod.value === 'cash' && cash < totalAmount){
        alert('Cash received is less than total! Cannot checkout.');
        return;
    }

    modalTotal.textContent = totalAmount.toFixed(2);
    
    if(paymentMethod.value === 'cash'){
        modalCashLabel.style.display = 'block';
        modalCash.textContent = cash.toFixed(2);
        modalChange.textContent = change.toFixed(2);
    } else {
        modalCashLabel.style.display = 'none'; // hide cash info for QR
        modalChange.textContent = '0.00';
    }

    confirmModal.classList.remove('hidden');
    confirmModal.classList.add('flex');
});

// Cancel modal
cancelBtn.addEventListener('click', () => {
    confirmModal.classList.add('hidden');
    confirmModal.classList.remove('flex');
});

// Confirm modal submits the form
confirmBtn.addEventListener('click', () => {
    checkoutForm.submit();
});

// Initialize display
if(paymentMethod.value === 'cash'){
    cashSection.style.display = 'block';
    qrSection.classList.add('hidden');
} else {
    cashSection.style.display = 'none';
    qrSection.classList.remove('hidden');
}

});
// === Action modal for Delete
const actionModal = document.getElementById('action-confirm-modal');
const actionTitle = document.getElementById('modal-action-title');
const actionMessage = document.getElementById('modal-action-message');
const actionConfirmBtn = document.getElementById('modal-confirm-btn');
const actionCancelBtn = document.getElementById('modal-cancel-btn');

let actionForm = null; // store the form that triggered the modal

// Attach modal to all delete
document.querySelectorAll('form').forEach(form => {
    const deleteBtn = form.querySelector('.delete-btn');

    if(deleteBtn) {
        form.addEventListener('submit', e => {
            e.preventDefault();
            actionForm = form;

            actionTitle.textContent = "Confirm Delete";
            actionMessage.textContent = "Are you sure you want to delete this item?";
            actionConfirmBtn.classList.remove('bg-yellow-500');
            actionConfirmBtn.classList.add('bg-red-500');

            actionModal.classList.remove('hidden');
            actionModal.classList.add('flex');
        });
    }
});


// Cancel button closes modal
actionCancelBtn.addEventListener('click', () => {
    actionModal.classList.add('hidden');
    actionModal.classList.remove('flex');
});

// Confirm button submits the form
actionConfirmBtn.addEventListener('click', () => {
    if(actionForm) actionForm.submit();
});

document.addEventListener('DOMContentLoaded', () => {
    const editModal = document.getElementById('edit-modal');
    const editForm = document.getElementById('edit-form');
    const editQuantity = document.getElementById('edit-quantity');
    const editCancel = document.getElementById('edit-cancel');

    // Open modal when clicking update
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const itemId = btn.dataset.id;
            const quantity = btn.dataset.quantity;

            editQuantity.value = quantity;
            editForm.action = `/cart/update/${itemId}`; // route to your update method

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        });
    });

    // Close modal
    editCancel.addEventListener('click', () => {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    });
});
