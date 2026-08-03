/* ==========================================================
   LITTLE BLOSSOM BABY SPA - JAVASCRIPT
   ========================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        });
    }

    // 2. Interactive Booking Price Calculator
    const serviceSelect = document.getElementById('service_select');
    const displayPrice = document.getElementById('display_price');

    if (serviceSelect && displayPrice) {
        function updateTotalPrice() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            
            if (price) {
                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(price);
                
                displayPrice.textContent = formatted;
            } else {
                displayPrice.textContent = 'Rp 0';
            }
        }

        serviceSelect.addEventListener('change', updateTotalPrice);
        // Initial run
        updateTotalPrice();
    }

    // 3. Set minimum date for reservation datepicker to today
    const reservationDate = document.getElementById('reservation_date');
    if (reservationDate) {
        const today = new Date().toISOString().split('T')[0];
        reservationDate.setAttribute('min', today);
    }
});

// Toast notification helper
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: #2D3748;
        color: #fff;
        padding: 12px 24px;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 9999;
        font-size: 0.9rem;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3500);
}
