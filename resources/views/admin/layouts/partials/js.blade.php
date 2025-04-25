<script src="{{ asset('assets/admin/js/vendor/jquery-3.6.4.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/js/menu.js') }}"></script>

<script src="{{ asset('assets/admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

<!-- Caleddar -->
<script src="{{ asset('assets/admin/js/vendor/jquery.simple-calendar.js') }}"></script>
<!-- Date Range Picker -->
<script src="{{ asset('assets/admin/js/vendor/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/date-range.js') }}"></script>

<!-- Main Custom -->
<script src="{{ asset('assets/admin/js/main.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.tiny.cloud/1/38ae9ba0wriqvw8vp3verlh18yjjenvdqk5fsgbbhso2pbzl/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>
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

