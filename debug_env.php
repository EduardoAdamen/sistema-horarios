<?php
// debug_env.php - elimina después
$keys = ['MYSQL_URL','DATABASE_URL','MYSQLHOST','MYSQL_HOST','MYSQLDATABASE','MYSQL_DATABASE','MYSQLUSER','MYSQL_USER','MYSQLPASSWORD','MYSQL_PASSWORD','MYSQLPORT','MYSQL_PORT'];
echo "<pre>";
foreach ($keys as $k) {
    $v = getenv($k);
    $sv = isset($_SERVER[$k]) ? $_SERVER[$k] : '(no)';
    $ev = isset($_ENV[$k]) ? $_ENV[$k] : '(no)';
    echo "$k\n getenv: " . var_export($v, true) . "\n \$_SERVER: " . var_export($sv, true) . "\n \$_ENV: " . var_export($ev, true) . "\n\n";
}
echo "</pre>";
