<?php
// includes/logger.php
// Función para registrar acciones del sistema en un archivo de log.

if (!function_exists('logAccion')) {
    function logAccion($usuario = null, $rol = null, $accion = null, $tabla = null, $id = null, $mensaje = null) {
        $fecha = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $host = gethostname() ?: '-';

        $entry = sprintf(
            "[%s] host=%s ip=%s user=%s role=%s action=%s table=%s id=%s msg=%s%s",
            $fecha,
            $host,
            $ip,
            $usuario ?? '-',
            $rol ?? '-',
            $accion ?? '-',
            $tabla ?? '-',
            $id ?? '-',
            $mensaje ?? '-',
            PHP_EOL
        );

        // Directorio de logs (uno arriba de includes/)
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0700, true);
        }

        $logFile = $logDir . '/acciones.log';
        // Intentar escribir; silenciar errores para no romper ejecución en producción
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
        return true;
    }
}
