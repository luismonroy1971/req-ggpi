<?php
// views/templates/footer.php
?>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">© <?= date('Y') ?> Tema Litoclean Perú - Gestión de Requerimientos de Sistemas</span>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <!-- Scripts propios -->
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    
    <?php if (isLoggedIn()): ?>
    <script>
        // Cargar contador de notificaciones
        function cargarNotificaciones() {
            fetch('<?= BASE_URL ?>notificaciones/contar-no-leidas')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificacionesBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                });

            // Cargar menú de notificaciones
            fetch('<?= BASE_URL ?>notificaciones')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const notificaciones = doc.querySelectorAll('.notificacion-item');
                    
                    const menu = document.getElementById('notificacionesMenu');
                    menu.innerHTML = '';
                    
                    if (notificaciones.length > 0) {
                        const header = document.createElement('li');
                        header.innerHTML = '<div class="dropdown-header">Notificaciones recientes</div>';
                        menu.appendChild(header);
                        
                        notificaciones.forEach((notificacion, index) => {
                            if (index < 5) { // Mostrar solo las 5 más recientes
                                const item = document.createElement('li');
                                item.innerHTML = notificacion.innerHTML;
                                menu.appendChild(item);
                            }
                        });
                        
                        const divider = document.createElement('li');
                        divider.innerHTML = '<hr class="dropdown-divider">';
                        menu.appendChild(divider);
                        
                        const verTodas = document.createElement('li');
                        verTodas.innerHTML = '<a class="dropdown-item text-center" href="<?= BASE_URL ?>notificaciones">Ver todas</a>';
                        menu.appendChild(verTodas);
                    } else {
                        const item = document.createElement('li');
                        item.innerHTML = '<div class="dropdown-item">No tienes notificaciones</div>';
                        menu.appendChild(item);
                    }
                });
        }

        // Cargar notificaciones al iniciar y cada 30 segundos
        document.addEventListener('DOMContentLoaded', function() {
            cargarNotificaciones();
            setInterval(cargarNotificaciones, 30000);
        });
    </script>
    <?php endif; ?>
</body>
</html>