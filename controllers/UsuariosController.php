<?php
// =====================================================
// controllers/UsuariosController.php
// =====================================================

class UsuariosController {
    
    public function __construct() {
        // Solo el subdirector puede gestionar usuarios
        if (!Auth::hasRole(ROLE_SUBDIRECTOR)) {
            header('Location: index.php?c=dashboard&error=access');
            exit;
        }
    }
    
    public function index() {
        $usuarios = Auth::loadUsers();
        
        // Filtrar usuarios activos
        $usuarios_activos = array_filter($usuarios, function($user) {
            return $user['activo'] === true;
        });
        
        $data = ['usuarios' => $usuarios_activos];
        $this->loadView('usuarios/index', $data);
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->loadView('usuarios/crear');
            return;
        }
        
        // POST - Procesar creación
        $datos = [
            'usuario' => $_POST['usuario'] ?? '',
            'password' => $_POST['password'] ?? '',
            'rol' => $_POST['rol'] ?? '',
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'email' => $_POST['email'] ?? '',
            'departamento' => $_POST['departamento'] ?? null,
            'numero_empleado' => $_POST['numero_empleado'] ?? null
        ];
        
        // Validar campos obligatorios
        if (empty($datos['usuario']) || empty($datos['password']) || empty($datos['nombre'])) {
            $_SESSION['error'] = 'Campos obligatorios incompletos';
            header('Location: index.php?c=usuarios&a=crear');
            exit;
        }
        
        $result = Auth::createUser($datos);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            header('Location: index.php?c=usuarios');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: index.php?c=usuarios&a=crear');
        }
        exit;
    }
    
    public function editar() {
        $usuario = $_GET['usuario'] ?? null;
        
        if (!$usuario) {
            header('Location: index.php?c=usuarios');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $user_data = Auth::getUserByUsername($usuario);
            
            if (!$user_data) {
                $_SESSION['error'] = 'Usuario no encontrado';
                header('Location: index.php?c=usuarios');
                exit;
            }
            
            $data = ['usuario_data' => $user_data];
            $this->loadView('usuarios/editar', $data);
            return;
        }
        
        // POST - Procesar actualización
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'activo' => isset($_POST['activo']) ? true : false
        ];
        
        $result = Auth::updateUser($usuario, $datos);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header('Location: index.php?c=usuarios');
        exit;
    }
    
    public function eliminar() {
        $usuario = $_POST['usuario'] ?? null;
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no especificado';
            header('Location: index.php?c=usuarios');
            exit;
        }
        
        $result = Auth::deleteUser($usuario);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header('Location: index.php?c=usuarios');
        exit;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . 'layout/header.php';
        require_once VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layout/footer.php';
    }
}
