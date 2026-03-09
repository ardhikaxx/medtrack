// MedTrack JavaScript

$(document).ready(function() {
    // Sidebar toggle
    $('#sidebarToggle').on('click', function() {
        $('#sidebar').toggleClass('mobile-open');
        $('.content-wrapper').toggleClass('sidebar-collapsed');
    });

    // Initialize DataTables
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
            },
            responsive: true,
            order: [[0, 'desc']]
        });
    }

    // Initialize Select2
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    }

    // Confirm delete
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').Out('fadeslow');
    }, 5000);
});

// Sidebar toggle function
function toggleSidebar() {
    $('#sidebar').toggleClass('mobile-open');
}
