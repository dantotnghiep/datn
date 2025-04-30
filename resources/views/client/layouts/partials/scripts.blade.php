<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/popper/popper.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/anchorjs/anchor.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/is/is.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/fontawesome/all.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/lodash/lodash.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/list.js/list.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/dayjs/dayjs.min.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/js/phoenix.js') }}"></script>
<script src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/vendors/swiper/swiper-bundle.min.js') }}"></script>
<!-- Include Pusher JS -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<!-- Toast Notification Library -->
<script>
// Simple toast notification system
window.toast = {
    success: function(message) {
        this.show(message, 'success');
    },
    error: function(message) {
        this.show(message, 'danger');
    },
    info: function(message) {
        this.show(message, 'info');
    },
    warning: function(message) {
        this.show(message, 'warning');
    },
    show: function(message, type) {
        // Create toast container if it doesn't exist
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1050';
            document.body.appendChild(container);
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast bg-${type} text-white`;
        toast.innerHTML = `
            <div class="toast-header bg-${type} text-white">
                <strong class="me-auto">Thông báo</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        container.appendChild(toast);
        
        // Initialize Bootstrap toast
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 5000
        });
        
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
};
</script>
@stack('scripts')