<?php

// Sistema de autenticación con archivos secuenciales

require_once __DIR__ . '/logger.php';

// Asegurar sesión disponible
if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

class Auth {
    
    // Verifica si el archivo de usuarios existe sino lo crea con datos iniciales
     
    public static function initUsersFile() {
        if (!defined('USERS_FILE')) {
           
            define('USERS_FILE', __DIR__ . '/users.db'); 
        }

        if (!file_exists(USERS_FILE)) {
            $usuarios_iniciales = [
                [
                    'usuario' => 'subdirector',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'rol' => defined('ROLE_SUBDIRECTOR') ? ROLE_SUBDIRECTOR : 'SUBDIRECTOR',
                    'nombre' => 'Subdirector',
                    'apellidos' => 'Académico',
                    'email' => 'subdirector@instituto.edu.mx',
                    'activo' => true,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'usuario' => 'jefe_sistemas',
                    'password' => password_hash('jefe123', PASSWORD_DEFAULT),
                    'rol' => defined('ROLE_JEFE_DEPTO') ? ROLE_JEFE_DEPTO : 'JEFE_DEPTO',
                    'nombre' => 'Jefe',
                    'apellidos' => 'Departamento Sistemas',
                    'email' => 'jefe.sistemas@instituto.edu.mx',
                    'departamento' => 'Sistemas Computacionales',
                    'activo' => true,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'usuario' => 'dep_usuario',
                    'password' => password_hash('dep123', PASSWORD_DEFAULT),
                    'rol' => defined('ROLE_DEP') ? ROLE_DEP : 'DEP',
                    'nombre' => 'División Estudios',
                    'apellidos' => 'Profesionales',
                    'email' => 'dep@instituto.edu.mx',
                    'activo' => true,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ];
            
          
            file_put_contents(USERS_FILE, serialize($usuarios_iniciales));
            @chmod(USERS_FILE, 0600); 
        }
    }
    
   
    public static function loadUsers() {
        self::initUsersFile();
        
        if (!file_exists(USERS_FILE)) {
            return [];
        }
        
        $content = file_get_contents(USERS_FILE);
        $users = @unserialize($content);
        
        return is_array($users) ? $users : [];
    }
    
   
    public static function saveUsers($users) {
        return file_put_contents(USERS_FILE, serialize($users)) !== false;
    }
    

    public static function login($usuario, $password) {
        $users = self::loadUsers();
        
        foreach ($users as $user) {
            if (isset($user['usuario']) && $user['usuario'] === $usuario && !empty($user['activo'])) {
                if (isset($user['password']) && password_verify($password, $user['password'])) {
                    // Crear sesión
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        @session_start();
                    }
                    session_regenerate_id(true);
                    $_SESSION['logged_in'] = true;
                    $_SESSION['usuario'] = $user['usuario'];
                    $_SESSION['rol'] = $user['rol'];
                    $_SESSION['nombre_completo'] = trim(($user['nombre'] ?? '') . ' ' . ($user['apellidos'] ?? ''));
                    $_SESSION['email'] = $user['email'] ?? null;
                    $_SESSION['login_time'] = time();
                    
                    // Log de acceso
                    logAccion($usuario, $user['rol'], 'LOGIN', null, null, 'Inicio de sesión exitoso');
                    
                    return true;
                } else {
                    // Intento fallido por contraseña
                    logAccion($usuario, $user['rol'] ?? 'unknown', 'LOGIN_FAILED', null, null, 'Contraseña incorrecta');
                    return false;
                }
            }
        }
        
        // Log de intento fallido
        logAccion($usuario, 'unknown', 'LOGIN_FAILED', null, null, 'Intento de login fallido (no encontrado o inactivo)');
        
        return false;
    }
    
   
    public static function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        if (isset($_SESSION['usuario'])) {
            logAccion($_SESSION['usuario'], $_SESSION['rol'] ?? 'unknown', 'LOGOUT', null, null, 'Cierre de sesión');
        }
        
        // Limpiar sesión
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        return true;
    }
    
 
    public static function isLoggedIn() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
   
    public static function hasRole($rol) {
        return self::isLoggedIn() && (isset($_SESSION['rol']) && $_SESSION['rol'] === $rol);
    }
    
   
    public static function hasAnyRole($roles) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        return in_array($_SESSION['rol'] ?? null, $roles, true);
    }
    
    
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return $_SESSION['usuario'] ?? null;
    }
    
   
    public static function createUser($datos) {
        if (empty($datos['usuario']) || empty($datos['password'])) {
            return ['success' => false, 'message' => 'Faltan datos obligatorios'];
        }

        $users = self::loadUsers();
        
        // Verificar si el usuario ya existe
        foreach ($users as $user) {
            if (isset($user['usuario']) && $user['usuario'] === $datos['usuario']) {
                return ['success' => false, 'message' => 'El usuario ya existe'];
            }
        }
        
        // Crear nuevo usuario
        $nuevo_usuario = [
            'usuario' => $datos['usuario'],
            'password' => password_hash($datos['password'], PASSWORD_DEFAULT),
            'rol' => $datos['rol'] ?? 'USER',
            'nombre' => $datos['nombre'] ?? '',
            'apellidos' => $datos['apellidos'] ?? '',
            'email' => $datos['email'] ?? '',
            'activo' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if (isset($datos['departamento'])) {
            $nuevo_usuario['departamento'] = $datos['departamento'];
        }
        
        if (isset($datos['numero_empleado'])) {
            $nuevo_usuario['numero_empleado'] = $datos['numero_empleado'];
        }
        
        $users[] = $nuevo_usuario;
        
        if (self::saveUsers($users)) {
            logAccion($_SESSION['usuario'] ?? 'system', $_SESSION['rol'] ?? 'system', 'CREATE_USER', 'users', null, 
                     'Usuario creado: ' . $datos['usuario']);
            return ['success' => true, 'message' => 'Usuario creado exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al guardar el usuario'];
    }
    
    
    public static function updateUser($usuario, $datos) {
        $users = self::loadUsers();
        $updated = false;
        
        foreach ($users as &$user) {
            if (isset($user['usuario']) && $user['usuario'] === $usuario) {
                if (isset($datos['nombre'])) $user['nombre'] = $datos['nombre'];
                if (isset($datos['apellidos'])) $user['apellidos'] = $datos['apellidos'];
                if (isset($datos['email'])) $user['email'] = $datos['email'];
                if (isset($datos['password']) && !empty($datos['password'])) {
                    $user['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
                }
                if (isset($datos['activo'])) $user['activo'] = (bool)$datos['activo'];
                if (isset($datos['departamento'])) $user['departamento'] = $datos['departamento'];
                
                $user['updated_at'] = date('Y-m-d H:i:s');
                $updated = true;
                break;
            }
        }
        
        if ($updated && self::saveUsers($users)) {
            logAccion($_SESSION['usuario'] ?? 'system', $_SESSION['rol'] ?? 'system', 'UPDATE_USER', 'users', null, 
                     'Usuario actualizado: ' . $usuario);
            return ['success' => true, 'message' => 'Usuario actualizado exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al actualizar el usuario'];
    }
    

    public static function deleteUser($usuario) {
        $users = self::loadUsers();
        $deleted = false;
        
        foreach ($users as &$user) {
            if (isset($user['usuario']) && $user['usuario'] === $usuario) {
                $user['activo'] = false;
                $user['deleted_at'] = date('Y-m-d H:i:s');
                $deleted = true;
                break;
            }
        }
        
        if ($deleted && self::saveUsers($users)) {
            logAccion($_SESSION['usuario'] ?? 'system', $_SESSION['rol'] ?? 'system', 'DELETE_USER', 'users', null, 
                     'Usuario desactivado: ' . $usuario);
            return ['success' => true, 'message' => 'Usuario desactivado exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al desactivar el usuario'];
    }
    
    
    public static function getUserByUsername($usuario) {
        $users = self::loadUsers();
        foreach ($users as $user) {
            if (isset($user['usuario']) && $user['usuario'] === $usuario) {
                return $user;
            }
        }
        return null;
    }
}
?>