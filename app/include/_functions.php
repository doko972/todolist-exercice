<?php
function generateToken()
{
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    }
}
function errorCsrf()
{
    if (!isset($_SESSION['token']) || !isset($_POST['token']) || $_SESSION['token'] !== $_POST['token']) {
        $_SESSION['error'] = 'csrf';
        header('Location: index.php');
        exit;
    }
}
function refererHttp(string $refererHttp)
{
    if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], 'http://localhost:8080')) {
        $_SESSION['error'] = 'referer';
        header('Location: ' . $refererHttp);
        exit;
    }
}




?>