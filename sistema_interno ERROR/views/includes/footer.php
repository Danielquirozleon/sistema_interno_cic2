</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función Centralizada de Modo Dark
        function toggleTheme() {
            const doc = document.documentElement;
            const isDark = doc.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            
            doc.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Notificación elegante opcional
            notify(`Modo ${newTheme === 'dark' ? 'Oscuro' : 'Claro'} activado`, 'info');
        }

        // Función Global para Notificaciones Toast
        function notify(msg, icon = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: icon,
                title: msg
            });
        }

        // Marcar enlace activo automáticamente
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>