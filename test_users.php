<?php
// test_users.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Prueba de usuarios</h2>";

try {
    $db = new Database();
    $sql = "SELECT id, nombre, correo, password, area_id, rol FROM usuarios";
    $stmt = $db->query($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Usuarios en la base de datos:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Área</th><th>Rol</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['nombre']}</td>";
        echo "<td>{$user['correo']}</td>";
        echo "<td>{$user['area_id']}</td>";
        echo "<td>{$user['rol']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Probar un hash específico
    echo "<h3>Prueba de contraseña:</h3>";
    $test_password = "usuario123";
    $correct_hash = password_hash($test_password, PASSWORD_DEFAULT);
    echo "Hash generado ahora: {$correct_hash}<br>";
    
    // Intentar verificar la contraseña con los hashes existentes
    foreach ($users as $user) {
        $verify = password_verify($test_password, $user['password']);
        echo "Usuario: {$user['correo']} - Verificación: " . ($verify ? "CORRECTA" : "INCORRECTA") . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>