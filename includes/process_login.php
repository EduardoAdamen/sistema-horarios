
<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once 'auth.php';

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

var_dump(function_exists('logAccion')); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($usuario) || empty($password)) {
        header('Location: ../login.php?error=empty');
        exit;
    }
    
    if (Auth::login($usuario, $password)) {
        header('Location: ../index.php');
        exit;
    } else {
        header('Location: ../login.php?error=invalid');
        exit;
    }
} else {
    header('Location: ../login.php');
    exit;
}
?>
