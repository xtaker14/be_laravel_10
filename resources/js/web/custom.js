/**
 * Sweet Alerts
 */

'use strict';

$('.confirm-logout').click(function(event) {
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
            window.location.href = 'logout';
        }
    });
});