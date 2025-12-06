<?php

require_once MODELS_PATH . 'Docente.php';


class DocentesController {
    
    private $docente_model;
    
    public function __construct() {
        // Verifica permisos y existencia de Auth
        if (!class_exists('Auth') || !Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            $_SESSION['error'] = 'Acceso no autorizado';
            header('Location: index.php');
            exit;
        }
        $this->docente_model = new Docente();
    }
    
    public function index() {
        $docentes = $this->docente_model->getAll();
        $this->loadView('docentes/index', ['docentes' => $docentes]);
    }
    
    public function crear() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->loadView('docentes/crear');
            return;
        }


        // 1. Recolección de datos del Docente
        $datos = [
            'numero_empleado' => strtoupper(trim($_POST['numero_empleado'] ?? '')),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido_paterno' => trim($_POST['apellido_paterno'] ?? ''),
            'apellido_materno' => trim($_POST['apellido_materno'] ?? ''),
            'email' => strtolower(trim($_POST['email'] ?? '')),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'tipo' => $_POST['tipo'] ?? 'tiempo_completo',
            'horas_max_semana' => (int)($_POST['horas_max_semana'] ?? 40)
        ];

        // 2. Validaciones Básicas del Docente
        if (empty($datos['numero_empleado']) || empty($datos['nombre']) || empty($datos['apellido_paterno']) || empty($datos['email'])) {
            $_SESSION['error'] = 'Todos los campos obligatorios del docente son requeridos';
            header('Location: index.php?c=docentes&a=crear');
            exit;
        }

        // 3. Verifica si el empleado ya existe en BD
        if ($this->docente_model->getByNumeroEmpleado($datos['numero_empleado'])) {
            $_SESSION['error'] = "El número de empleado {$datos['numero_empleado']} ya existe en la base de datos.";
            header('Location: index.php?c=docentes&a=crear');
            exit;
        }

   
        // 4. VALIDACIÓN DE CUENTA DE USUARIO (Archivos secuenciales)
       
        $crear_cuenta = isset($_POST['crear_cuenta']);
        $auth_user = '';
        $auth_pass = '';

        if ($crear_cuenta) {
          
            $input_user = trim($_POST['usuario_login'] ?? '');
            $auth_user = !empty($input_user) ? $input_user : $datos['numero_empleado'];
            
            $auth_pass = $_POST['password'] ?? '';

            
            if (empty($auth_pass) || strlen($auth_pass) < 6) {
                $_SESSION['error'] = 'Para crear la cuenta de usuario, la contraseña es obligatoria (mínimo 6 caracteres).';
                header('Location: index.php?c=docentes&a=crear');
                exit; 
            }

            // Verifica si el usuario YA existe en el sistema de archivos
            if (Auth::getUserByUsername($auth_user)) {
                $_SESSION['error'] = "El usuario de sistema '{$auth_user}' ya existe. Por favor use otro nombre de usuario.";
                header('Location: index.php?c=docentes&a=crear');
                exit; 
            }
        }

        
        // 5. CREACIÓN EN BASE DE DATOS 
       
        $result = $this->docente_model->create($datos);
        
        if (!$result['success']) {
            $_SESSION['error'] = 'Error crítico: No se pudo registrar al docente en la base de datos.';
            header('Location: index.php?c=docentes&a=crear');
            exit;
        }

        
        // 6. CREACIÓN DE USUARIO EN ARCHIVOS (AUTH)
        
        $mensaje_login = "";
        
        if ($crear_cuenta) {
            $rol_docente = defined('ROLE_DOCENTE') ? ROLE_DOCENTE : 'docente';
            
            $createAuth = Auth::createUser([
                'usuario' => $auth_user,
                'password' => $auth_pass,
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellido_paterno'] . ' ' . $datos['apellido_materno'],
                'email' => $datos['email'],
                'rol' => $rol_docente,
                'numero_empleado' => $datos['numero_empleado']
            ]);

            if ($createAuth['success']) {
                $mensaje_login = " y cuenta de acceso creada (Usuario: $auth_user).";
            } else {
               
                $_SESSION['warning'] = "Docente registrado, pero falló la creación de cuenta: " . $createAuth['message'];
                header('Location: index.php?c=docentes');
                exit;
            }
        }

        $_SESSION['success'] = "Docente registrado correctamente" . $mensaje_login;
        header('Location: index.php?c=docentes');
        exit;
    }
    

    public function editar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID inválido';
            header('Location: index.php?c=docentes');
            exit;
        }

        $docente = $this->docente_model->getById($id);
        if (!$docente) {
            $_SESSION['error'] = 'Docente no encontrado';
            header('Location: index.php?c=docentes');
            exit;
        }

        
        $usuario_asociado = Auth::getUserByUsername($docente['numero_empleado']) 
                          ?? Auth::getUserByUsername($docente['email']) 
                          ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->loadView('docentes/editar', [
                'docente' => $docente,
                'usuario_asociado' => $usuario_asociado
            ]);
            return;
        }

        $datos = [
            'numero_empleado' => strtoupper(trim($_POST['numero_empleado'] ?? '')),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido_paterno' => trim($_POST['apellido_paterno'] ?? ''),
            'apellido_materno' => trim($_POST['apellido_materno'] ?? ''),
            'email' => strtolower(trim($_POST['email'] ?? '')),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'tipo' => $_POST['tipo'] ?? 'tiempo_completo',
            'horas_max_semana' => (int)($_POST['horas_max_semana'] ?? 40)
        ];

        
        $otro = $this->docente_model->getByNumeroEmpleado($datos['numero_empleado']);
        if ($otro && $otro['id'] != $id) {
            $_SESSION['error'] = "El número de empleado ya está en uso por otro docente";
            header("Location: index.php?c=docentes&a=editar&id=$id");
            exit;
        }

        $this->docente_model->update($id, $datos);

        // Actualizar/Crear Cuenta Auth
        $auth_user = trim($_POST['usuario_login'] ?? $datos['numero_empleado']);
        $auth_pass = $_POST['password'] ?? '';

        if (isset($_POST['modificar_cuenta']) || isset($_POST['crear_cuenta'])) {
            if ($usuario_asociado) {
                // Actualizar existente
                Auth::updateUser($usuario_asociado['usuario'], [
                    'nombre' => $datos['nombre'],
                    'apellidos' => $datos['apellido_paterno'] . ' ' . $datos['apellido_materno'],
                    'email' => $datos['email'],
                    'password' => !empty($auth_pass) ? $auth_pass : null
                ]);
            } else if (!empty($auth_pass)) {
                // Crear nueva si no existía y puso password
                Auth::createUser([
                    'usuario' => $auth_user,
                    'password' => $auth_pass,
                    'nombre' => $datos['nombre'],
                    'apellidos' => $datos['apellido_paterno'] . ' ' . $datos['apellido_materno'],
                    'email' => $datos['email'],
                    'rol' => defined('ROLE_DOCENTE') ? ROLE_DOCENTE : 'docente',
                    'numero_empleado' => $datos['numero_empleado']
                ]);
            }
        }

        $_SESSION['success'] = 'Docente actualizado correctamente';
        header('Location: index.php?c=docentes');
        exit;
    }

    public function eliminar() {
        $id = $_POST['id'] ?? null;
        if ($id && $this->docente_model->delete($id)) {
            $_SESSION['success'] = 'Docente eliminado';
        } else {
            $_SESSION['error'] = 'Error al eliminar';
        }
        header('Location: index.php?c=docentes');
        exit;
    }

    private function loadView($view, $data = []) {
        extract($data);
        $header = __DIR__ . '/../views/layout/header.php';
        $footer = __DIR__ . '/../views/layout/footer.php';
        $content = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($header)) require_once $header;
        if (file_exists($content)) require_once $content;
        if (file_exists($footer)) require_once $footer;
    }
}
?>