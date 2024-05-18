<footer>
    <div class="container">
        <p class="mb-0">Alto Vale News &copy; <?php echo date("Y"); ?></p>
        <p class="text-muted small">Desenvolvido por AJMV Tech Ltda.</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.querySelector('body');
        const main = document.querySelector('main');

        function updateFooterVisibility() {
            if (main.offsetHeight < window.innerHeight) {
                body.classList.remove('content-filled');
            } else {
                body.classList.add('content-filled');
            }
        }

        updateFooterVisibility();

        window.addEventListener('resize', function() {
            updateFooterVisibility();
        });
    });
</script>