<?php
session_start();

include "include/_config.php";
include "include/_functions.php";

if (!empty($_REQUEST['description']) && isset($_REQUEST['buttonAdd'])) {
    refererHttp('index.php');
    errorCsrf();

    if (strlen($_REQUEST['description']) <= 150) {
        $insert = $dbConnect->prepare("INSERT INTO `task` (`priority`, `description`, `creation_date`, `done`) 
            VALUES (:priority, :description, NOW(), 0);");
        $insert->bindValue(':priority', htmlspecialchars($_REQUEST['priority']));
        $insert->bindValue(':description', htmlspecialchars($_REQUEST['description']));
        if ($insert->execute()) {
            header('Location: index.php?msg=insert_ok');
            exit;
        }
    }
    header('Location: index.php?msg=insert_ko');
    exit;
}

if (!empty($_REQUEST['task_id']) && !empty($_REQUEST['token']) && $_REQUEST['token'] === $_SESSION['token'] && isset($_REQUEST['action'])) {
    $taskId = intval($_REQUEST['task_id']);
    if ($_REQUEST['action'] === 'delete') {
        $delete = $dbConnect->prepare("DELETE FROM task WHERE id_task = :task_id");
        $delete->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($delete->execute()) {
            header("Location: index.php?msg=delete_success");
            exit;
        }
        header("Location: index.php?msg=delete_failure");
        exit;
    }
    if ($_REQUEST['action'] === 'done') {
        $update = $dbConnect->prepare("UPDATE task SET done = 1 WHERE id_task = :task_id");
        $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($update->execute()) {
            header("Location: index.php?msg=done_success");
            exit;
        }
        header("Location: index.php?msg=update_failure");
        exit;
    }
    if ($_REQUEST['action'] === 'modify' && !empty($_REQUEST['newDescription']) && isset($_REQUEST['newPriority'])) {
        $update = $dbConnect->prepare("UPDATE task SET description = :description, priority = :priority WHERE id_task = :task_id");
        $update->bindValue(':description', htmlspecialchars($_REQUEST['newDescription']), PDO::PARAM_STR);
        $update->bindValue(':priority', htmlspecialchars($_REQUEST['newPriority']), PDO::PARAM_INT);
        $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($update->execute()) {
            header("Location: index.php?msg=update_success");
            exit;
        }
        header("Location: index.php?msg=update_failure");
        exit;
    }
}
?>