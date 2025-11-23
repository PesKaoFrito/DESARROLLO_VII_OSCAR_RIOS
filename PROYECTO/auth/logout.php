<?php
require_once '../config.php';
require_once '../includes/auth.php';

logout();
header('Location: ' . BASE_URL . 'auth/login.php');
exit;
