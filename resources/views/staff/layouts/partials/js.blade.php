<!-- Vendor Custom -->
<script src="/be/assets/js/vendor/jquery-3.6.4.min.js"></script>
<script src="/be/assets/js/vendor/simplebar.min.js"></script>
<script src="/be/assets/js/vendor/bootstrap.bundle.min.js"></script>
<script src="/be/assets/js/vendor/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/be/assets/js/vendor/jquery-jvectormap-world-mill-en.js"></script>
<script src="/be/assets/js/vendor/owl.carousel.min.js"></script>
<!-- Data Tables -->
<script src='/be/assets/js/vendor/jquery.datatables.min.js'></script>
<script src='/be/assets/js/vendor/datatables.bootstrap5.min.js'></script>
<script src='/be/assets/js/vendor/datatables.responsive.min.js'></script>
<!-- Caleddar -->
<script src="/be/assets/js/vendor/jquery.simple-calendar.js"></script>
<!-- Date Range Picker -->
<script src="/be/assets/js/vendor/moment.min.js"></script>
<script src="/be/assets/js/vendor/daterangepicker.js"></script>
<script src="/be/assets/js/vendor/date-range.js"></script>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">




<!-- Main Custom -->
<script src="/be/assets/js/main.js"></script>
<script src="/be/assets/js/data/ecommerce-chart-data.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(categoryId) {
        // Gọi SweetAlert2
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi form xóa nếu xác nhận
                document.getElementById(`delete-form-${categoryId}`).submit();
            }
        });
    }

</script>

