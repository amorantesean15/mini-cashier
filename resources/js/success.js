document.addEventListener('DOMContentLoaded', () => {
    const redirectUrl = document.body.dataset.redirect;

    setTimeout(() => {
        window.location.href = redirectUrl;
    }, 3000);
});
