<?php
// fix_users.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Reparación de usuarios</h2>";

try {
    $db = new Database();
    
    // Recrear usuario administrador
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET password = :password WHERE correo = 'admin@litoclean.com'";
    $stmt = $db->query($sql);
    $params = [':password' => $admin_password];
    $result = $stmt->execute($params);
    echo "Usuario admin@litoclean.com actualizado: " . ($result ? "OK" : "ERROR") . "<br>";
    
    // Recrear usuarios normales
    $user_password = password_hash('usuario123', PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET password = :password WHERE correo != 'admin@litoclean.com'";
    $stmt = $db->query($sql);
    $params = [':password' => $user_password];
    $result = $stmt->execute($params);
    echo "Usuarios normales actualizados: " . ($result ? "OK" : "ERROR") . "<br>";
    
    echo "<p>Contraseñas actualizadas. Ahora puede iniciar sesión con:</p>";
    echo "<ul>";
    echo "<li>admin@litoclean.com / admin123</li>";
    echo "<li>administracion@litoclean.com / usuario123</li>";
    echo "<li>Resto de usuarios / usuario123</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>