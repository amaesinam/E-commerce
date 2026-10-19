
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Return the client's IP address.
function get_ip()
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

function is_admin()
{
    return isset($_SESSION['user_role'])
        && (int) $_SESSION['user_role'] === 1;
}

function base_url()
{
    $host = $_SERVER['HTTP_HOST'] ?? '';

    return ($host === 'localhost' || strpos($host, 'localhost:') === 0)
        ? '/shoppn'
        : '/~victoria.nyonator/ecommerce/shoppn';
}

function require_login()
{
    if (!is_logged_in()) {
        header("Location: " . base_url() . "/views/login.php");
        exit;
    }
}

function require_admin()
{
    if (!is_admin()) {
        header("Location: " . base_url() . "/index.php");
        exit;
    }
}
