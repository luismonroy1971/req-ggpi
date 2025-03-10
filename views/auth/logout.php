<?php
// views/auth/logout.php
// Este archivo simplemente redirige a la acción de logout, no es una vista como tal
header('Location: ' . BASE_URL . 'logout');
exit();
?>