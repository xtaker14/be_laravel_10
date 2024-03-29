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
<script src="{{ asset('template/assets/vendor/libs/toastr/toastr.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('template/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('template/js/web/custom.js') }}"></script>
<script src="{{ asset('template/assets/js/extended-ui-sweetalert2.js') }}"></script>

@if (session()->has('success'))
<script>
    toastr.success("{{ session()->get('success') }}", 'success')
</script>
@endif

@if (session()->has('failed'))
<script>
    toastr.error("{{ session()->get('failed') }}", 'failed')
</script>
@endif

<script>
    $('#DataTableBasic').DataTable({
        "lengthChange": false,
        "searching": false
    });
    
    $('.date').flatpickr({
        monthSelectorType: 'static',
        maxDate: 'today',
    });

    $('.input-date').flatpickr({
        monthSelectorType: 'static',
        minDate: 'today',
    });

    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Scrollable table
    var $table = $('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

    // Adjust the width of thead cells when window resizes
    $(window).resize(function() {
        // Get the tbody columns width array
        colWidth = $bodyCells.map(function() {
            return $(this).width();
        }).get();
        
        // Set the width of thead columns
        $table.find('thead tr').children().each(function(i, v) {
            $(v).width(colWidth[i]);
        });    
    }).resize();

    //logout alert confirm
    $('.confirm-logout-new').click(function(event) {
        event.preventDefault();

        Swal.fire({
            title: `Logout`,
            text: "Are you sure wants to logout ?",
            icon: 'warning',
            type: "warning",
            showCancelButton: false,
            showDenyButton: false,
            confirmButtonText: "Yes, logout!",
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
        }).then((result) => {
            if(result.value === true) {
                $('form#logout-form').submit();
            }
        });
    });
</script>