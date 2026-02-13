<?php
/**
 * Footer Template - Sneat Bootstrap 5
 * sistem keuangan Sekolah
 */

// Get flash message
$flash = getFlash();
?>

<!-- / Content -->

<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            Copyright &copy; <?php echo APP_NAME; ?> <?php echo date('Y'); ?>
        </div>
    </div>
</footer>
<!-- / Footer -->

<div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Apakah Anda yakin ingin keluar dari sistem?</div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <a class="btn btn-primary" href="<?php echo $base_url; ?>logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
                <p class="text-danger"><small>Data yang sudah dihapus tidak dapat dikembalikan.</small></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <a class="btn btn-danger" id="deleteConfirmBtn" href="#">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- Core JS -->
<script src="<?php echo $sneat_path; ?>assets/vendor/libs/jquery/jquery.js"></script>
<script src="<?php echo $sneat_path; ?>assets/vendor/libs/popper/popper.js"></script>
<script src="<?php echo $sneat_path; ?>assets/vendor/js/bootstrap.js"></script>
<script src="<?php echo $sneat_path; ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="<?php echo $sneat_path; ?>assets/vendor/js/menu.js"></script>

<!-- Main JS -->
<script src="<?php echo $sneat_path; ?>assets/js/main.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Initialize DataTables
    $(document).ready(function () {
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        }
    });

    // Delete confirmation
    function confirmDelete(url) {
        $('#deleteConfirmBtn').attr('href', url);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Show loading
    function showLoading() {
        $('#loading').show();
    }

    // Hide loading
    function hideLoading() {
        $('#loading').hide();
    }

    // Format currency input
    function formatCurrency(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        input.value = new Intl.NumberFormat('id-ID').format(value);
    }

    <?php if ($flash): ?>
        // Show flash message with SweetAlert2
        $(document).ready(function () {
            Swal.fire({
                icon: '<?php echo $flash['type'] == 'success' ? 'success' : ($flash['type'] == 'danger' ? 'error' : $flash['type']); ?>',
                title: '<?php echo $flash['type'] == 'success' ? 'Berhasil!' : ($flash['type'] == 'danger' ? 'Error!' : 'Info'); ?>',
                text: '<?php echo addslashes($flash['message']); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        });
    <?php endif; ?>
</script>

<?php if (isset($extra_js)): ?>
    <?php echo $extra_js; ?>
<?php endif; ?>

</body>

</html>
