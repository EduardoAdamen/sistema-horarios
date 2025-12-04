<?php
require_once 'config/config.php';
require_once 'includes/auth.php';

session_start();
Auth::logout();

header('Location: login.php?success=logout');
exit;
?>