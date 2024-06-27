<?php
function tokenCreate(array &$_SESSION): string
{
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    }
    return $_SESSION['token'];
}
?>