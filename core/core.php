<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

function is_admin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 1;
}

function base_url()
{
    return ($_SERVER['HTTP_HOST'] === 'localhost')
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

?>