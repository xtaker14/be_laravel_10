<script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>

<!-- Vendors JS -->
<script src="{{ asset('template/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/pickr/pickr.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/select2/select2.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('template/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('template/js/web/custom.js') }}"></script>
<script src="{{ asset('template/assets/js/extended-ui-sweetalert2.js') }}"></script>

<script>
    $('#DataTableBasic').DataTable({
        "lengthChange": false,
        "searching": false
    });
    
    $('.date').flatpickr({
        monthSelectorType: 'static',
        maxDate: 'today',
        wrap: true,
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        disableMobile: "true"
    });

    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
</script>