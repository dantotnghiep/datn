@if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
    <div class="position-fixed top-0 end-0 py-2 pe-3" style="z-index: 9999; max-width: 500px;">
        @if (session('success'))
            <div class="toast-container position-relative">
                <div class="toast fade show p-0 m-0 border-0 shadow-sm" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="toast-body bg-success bg-gradient text-white p-0 rounded-3">
                        <div class="d-flex align-items-center py-2 px-3">
                            <div class="d-flex align-items-center me-auto">
                                <div class="me-2 rounded-circle bg-white text-success d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    <i class="fas fa-check fs-10"></i>
                                </div>
                                <span class="fw-semibold">{{ session('success') }}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-container position-relative">
                <div class="toast fade show p-0 m-0 border-0 shadow-sm" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="toast-body bg-danger bg-gradient text-white p-0 rounded-3">
                        <div class="d-flex align-items-center py-2 px-3">
                            <div class="d-flex align-items-center me-auto">
                                <div class="me-2 rounded-circle bg-white text-danger d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    <i class="fas fa-times fs-10"></i>
                                </div>
                                <span class="fw-semibold">{{ session('error') }}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="toast-container position-relative">
                <div class="toast fade show p-0 m-0 border-0 shadow-sm" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="toast-body bg-warning bg-gradient text-white p-0 rounded-3">
                        <div class="d-flex align-items-center py-2 px-3">
                            <div class="d-flex align-items-center me-auto">
                                <div class="me-2 rounded-circle bg-white text-warning d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    <i class="fas fa-exclamation fs-10"></i>
                                </div>
                                <span class="fw-semibold">{{ session('warning') }}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="toast-container position-relative">
                <div class="toast fade show p-0 m-0 border-0 shadow-sm" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="toast-body bg-info bg-gradient text-white p-0 rounded-3">
                        <div class="d-flex align-items-center py-2 px-3">
                            <div class="d-flex align-items-center me-auto">
                                <div class="me-2 rounded-circle bg-white text-info d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    <i class="fas fa-info fs-10"></i>
                                </div>
                                <span class="fw-semibold">{{ session('info') }}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="toast-container position-relative">
                <div class="toast fade show p-0 m-0 border-0 shadow-sm" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="toast-body bg-danger bg-gradient text-white p-0 rounded-3">
                        <div class="d-flex align-items-center py-2 px-3">
                            <div class="d-flex align-items-center me-auto">
                                <div class="me-2 rounded-circle bg-white text-danger d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    <i class="fas fa-exclamation-triangle fs-10"></i>
                                </div>
                                <span class="fw-semibold">An error occurred. Please check and try again.</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss all toasts after 3 seconds
            var toastElements = document.querySelectorAll('.toast.show');
            toastElements.forEach(function(toast) {
                setTimeout(function() {
                    var fadeEffect = setInterval(function() {
                        if (!toast.style.opacity) {
                            toast.style.opacity = 1;
                        }
                        if (toast.style.opacity > 0) {
                            toast.style.opacity -= 0.1;
                        } else {
                            clearInterval(fadeEffect);
                            toast.classList.remove('show');
                        }
                    }, 25);
                }, 3000);
            });
        });
    </script>
@endif
