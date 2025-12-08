<?php

require_once MODELS_PATH . 'Docente.php';
require_once MODELS_PATH . 'DocenteMateria.php';

class DocentesController {
    
    private $docente_model;
    private $docente_materia_model;
    
    public function __construct() {
        if (!class_exists('Auth') || !Auth::hasAnyRole([ROLE_SUBDIRECTOR, ROLE_JEFE_DEPTO])) {
            $_SESSION['error'] = 'Acceso no autorizado';
            header('Location: index.php');
            exit;
        }
        $this->docente_model = new Docente();
        $this->docente_materia_model = new DocenteMateria();
    }
    
    public function index() {
        $docentes = $this->docente_model->getAll();
        $this->loadView('docentes/index', ['docentes' => $docentes]);
    }
    
    public function crear() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Obtener materias disponibles para asignación
            $materias = $this->docente_materia_model->getMateriasDisponibles();
            $this->loadView('docentes/crear', ['materias' => $materias]);
            return;
        }

        // POST - Crear docente
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

        // Validaciones
        if (empty($datos['numero_empleado']) || empty($datos['nombre']) || empty($datos['apellido_paterno']) || empty($datos['email'])) {
            $_SESSION['error'] = 'Todos los campos obligatorios son requeridos';
            header('Location: index.php?c=docentes&a=crear');
            exit;
        }

        if ($this->docente_model->getByNumeroEmpleado($datos['numero_empleado'])) {
            $_SESSION['error'] = "El número de empleado {$datos['numero_empleado']} ya existe";
            header('Location: index.php?c=docentes&a=crear');
            exit;
        }

        // Validación de cuenta de usuario
        $crear_cuenta = isset($_POST['crear_cuenta']);
        $auth_user = '';
        $auth_pass = '';

        if ($crear_cuenta) {
            $input_user = trim($_POST['usuario_login'] ?? '');
            $auth_user = !empty($input_user) ? $input_user : $datos['numero_empleado'];
            $auth_pass = $_POST['password'] ?? '';

            if (empty($auth_pass) || strlen($auth_pass) < 6) {
                $_SESSION['error'] = 'Para crear cuenta, la contraseña es obligatoria (mínimo 6 caracteres)';
                header('Location: index.php?c=docentes&a=crear');
                exit;
            }

            if (Auth::getUserByUsername($auth_user)) {
                $_SESSION['error'] = "El usuario '{$auth_user}' ya existe";
                header('Location: index.php?c=docentes&a=crear');
                exit;
            }
        }

        // Crear docente en BD
        $result = $this->docente_model->create($datos);
        
        if (!$result['success']) {
            $_SESSION['error'] = 'Error al registrar docente';
            header('Location: index.php?c=docentes&a=crear');
            exit;
        }

        $docente_id = $result['id'];

        // Asignar materias al docente
        $materias_seleccionadas = $_POST['materias'] ?? [];
        if (!empty($materias_seleccionadas)) {
            $this->docente_materia_model->asignarMaterias($docente_id, $materias_seleccionadas);
        }

        // Crear cuenta de usuario si se solicitó
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
                $mensaje_login = " y cuenta de acceso creada (Usuario: $auth_user)";
            } else {
                $_SESSION['warning'] = "Docente registrado, pero falló creación de cuenta: " . $createAuth['message'];
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
            // Obtener materias asignadas y disponibles
            $materias_asignadas = $this->docente_materia_model->getMateriasPorDocente($id);
            $materias_disponibles = $this->docente_materia_model->getMateriasDisponibles();
            
            // IDs de materias asignadas
            $materias_ids = array_column($materias_asignadas, 'id');
            
            $this->loadView('docentes/editar', [
                'docente' => $docente,
                'usuario_asociado' => $usuario_asociado,
                'materias_disponibles' => $materias_disponibles,
                'materias_asignadas_ids' => $materias_ids
            ]);
            return;
        }

        // POST - Actualizar
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
            $_SESSION['error'] = "El número de empleado ya está en uso";
            header("Location: index.php?c=docentes&a=editar&id=$id");
            exit;
        }

        $this->docente_model->update($id, $datos);

        // Actualizar materias asignadas
        $materias_seleccionadas = $_POST['materias'] ?? [];
        $this->docente_materia_model->asignarMaterias($id, $materias_seleccionadas);

        // Actualizar/Crear Cuenta Auth
        $auth_user = trim($_POST['usuario_login'] ?? $datos['numero_empleado']);
        $auth_pass = $_POST['password'] ?? '';

        if (isset($_POST['modificar_cuenta']) || isset($_POST['crear_cuenta'])) {
            if ($usuario_asociado) {
                Auth::updateUser($usuario_asociado['usuario'], [
                    'nombre' => $datos['nombre'],
                    'apellidos' => $datos['apellido_paterno'] . ' ' . $datos['apellido_materno'],
                    'email' => $datos['email'],
                    'password' => !empty($auth_pass) ? $auth_pass : null
                ]);
            } else if (!empty($auth_pass)) {
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