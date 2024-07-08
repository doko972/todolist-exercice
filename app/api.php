<?php
session_start();

include 'includes/_config.php';
include 'includes/_functions.php';
include 'includes/_delete.php';
include 'includes/_database.php';

header('Content-type:application/json');

if (!isset($_REQUEST['action'])) {
    var_dump('NO ACTION');
    exit;
}

if (!empty($_REQUEST['task_id']) && !empty($_REQUEST['token']) && $_REQUEST['token'] === $_SESSION['token'] && isset($_REQUEST['action'])) {
    $taskId = intval($_REQUEST['task_id']);
    
    if ($_REQUEST['action'] === 'delete') {
        $delete = $dbConnect->prepare("DELETE FROM task WHERE id_task = :task_id");
        $delete->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($delete->execute()) {
            $_SESSION['msg'] = 'delete_success';
            header("Location: index.php");
            exit;
        }
        $_SESSION['error'] = 'delete_failure';
        header("Location: index.php");
        exit;
    }
}