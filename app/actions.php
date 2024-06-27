<?php
session_start();

include "include/_config.php";
include "include/_functions.php";

// create new task
if (!empty($_POST['description']) && isset($_POST['buttonAdd'])) {
    refererHttp('index.php');
    errorCsrf();

    if (strlen($_POST['description']) <= 150) {
        $insert = $dbConnect->prepare("INSERT INTO `task` (`priority`, `description`, `creation_date`, `done`) 
            VALUES (:priority, :description, NOW(), 0);");
        $insert->bindValue(':priority', htmlspecialchars($_POST['priority']));
        $insert->bindValue(':description', htmlspecialchars($_POST['description']));
        if ($insert->execute()) {
            header('Location: index.php?msg=insert_ok');
            exit;
        }
    }
    header('Location: index.php?msg=insert_ko');
    exit;
}
// mofify priority
if (!empty(isset($_POST['modifyPriority']))) {
    refererHttp('index.php');
    errorCsrf();

    if (strlen($_POST['modifyPriority'])) {
        $insert = $dbConnect->prepare("INSERT INTO `task` (`priority`, `description`, `creation_date`, `done`) 
            VALUES (:priority);");
        $insert->bindValue(':priority', htmlspecialchars($_POST['priority']));
        if ($insert->execute()) {
            header('Location: index.php?msg=insert_ok');
            exit;
        }
    }
    header('Location: index.php?msg=insert_ko');
    exit;
}
if (!empty($_POST['task_id']) && !empty($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
    $taskId = intval($_POST['task_id']);
    if ($_POST['action'] === 'delete') {
        $delete = $dbConnect->prepare("DELETE FROM task WHERE id_task = :task_id");
        $delete->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($delete->execute()) {
            header("Location: index.php?msg=delete_success");
            exit;
        }
        header("Location: index.php?msg=delete_failure");
        exit;
    }
    if ($_POST['action'] === 'done') {
        $update = $dbConnect->prepare("UPDATE task SET done = 1 WHERE id_task = :task_id");
        $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($update->execute()) {
            header("Location: index.php?msg=done_success");
            exit;
        }
        header("Location: index.php?msg=update_failure");
        exit;
    }
    if ($_POST['action'] === 'update' && !empty($_POST['new_description'])) {
        $update = $dbConnect->prepare("UPDATE task SET description = :description WHERE id_task = :task_id");
        $update->bindValue(':description', htmlspecialchars($_POST['new_description']), PDO::PARAM_STR);
        $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($update->execute()) {
            header("Location: index.php?msg=update_success");
            exit;
        }
        header("Location: index.php?msg=update_failure");
        exit;
    }
}