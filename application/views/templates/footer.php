</div> <!-- /.container -->

<footer class="text-center py-3">
    <div class="footer-text">
        &copy; <?= date('Y'); ?> Absensi Kantor. All rights reserved.
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetSelector = this.getAttribute('data-target');
            const input = document.querySelector(targetSelector);
            if (!input) return;

            const icon = this.querySelector('i');
            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            if (icon) {
                icon.classList.toggle('bi-eye', !isPassword);
                icon.classList.toggle('bi-eye-slash', isPassword);
            }
        });
    });
});
</script>

</body>
</html>
