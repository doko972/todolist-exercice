<?php
session_start();
include "include/_config.php";
include "include/_functions.php";

if (!empty($_REQUEST['description']) && isset($_REQUEST['buttonAdd'])) {
    refererHttp('index.php');
    errorCsrf();

    if (strlen($_REQUEST['description']) <= 150) {
        $query = $dbConnect->prepare("SELECT COUNT(*) AS total_tasks FROM task WHERE done = 0");
        $query->execute();
        $result = $query->fetch();
        $totalTasks = $result['total_tasks'];
        $newPriority = $totalTasks + 1;
        
        $rememberDate = !empty($_REQUEST['remember_date']) ? $_REQUEST['remember_date'] : null;

        $insert = $dbConnect->prepare("INSERT INTO `task` (`priority`, `description`, `creation_date`, `done`, `remember`) 
            VALUES (:priority, :description, NOW(), 0, :remember);");
        $insert->bindValue(':priority', $newPriority, PDO::PARAM_INT);
        $insert->bindValue(':description', htmlspecialchars($_REQUEST['description']));
        $insert->bindValue(':remember', $rememberDate);
        if ($insert->execute()) {
            $_SESSION['msg'] = 'insert_ok';
            header('Location: index.php');
            exit;
        }
    }
    $_SESSION['error'] = 'insert_ko';
    header('Location: index.php');
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
    if ($_REQUEST['action'] === 'done') {
        $update = $dbConnect->prepare("UPDATE task SET done = 1 WHERE id_task = :task_id");
        $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($update->execute()) {
            $_SESSION['msg'] = 'done_success';
            header("Location: index.php");
            exit;
        }
        $_SESSION['error'] = 'update_failure';
        header("Location: index.php");
        exit;
    }
    if ($_REQUEST['action'] === 'increase_priority') {
        $query = $dbConnect->prepare("SELECT priority FROM task WHERE id_task = :task_id");
        $query->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();

        if ($result && $result['priority'] > 1) {
            $currentPriority = $result['priority'];
            $newPriority = $currentPriority - 1;

            $update = $dbConnect->prepare("UPDATE task SET priority = priority + 1 WHERE priority >= :newPriority AND priority < :currentPriority");
            $update->bindParam(':newPriority', $newPriority, PDO::PARAM_INT);
            $update->bindParam(':currentPriority', $currentPriority, PDO::PARAM_INT);
            $update->execute();

            $update = $dbConnect->prepare("UPDATE task SET priority = :newPriority WHERE id_task = :task_id");
            $update->bindParam(':newPriority', $newPriority, PDO::PARAM_INT);
            $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
            if ($update->execute()) {
                $_SESSION['msg'] = 'priority_increased';
                header("Location: index.php");
                exit;
            }
            $_SESSION['error'] = 'priority_increase_failure';
            header("Location: index.php");
            exit;
        }
    }
    if ($_REQUEST['action'] === 'decrease_priority') {
        $query = $dbConnect->prepare("SELECT priority FROM task WHERE id_task = :task_id");
        $query->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();

        if ($result) {
            $currentPriority = $result['priority'];
            $newPriority = $currentPriority + 1;

            $update = $dbConnect->prepare("UPDATE task SET priority = priority - 1 WHERE priority <= :newPriority AND priority > :currentPriority");
            $update->bindParam(':newPriority', $newPriority, PDO::PARAM_INT);
            $update->bindParam(':currentPriority', $currentPriority, PDO::PARAM_INT);
            $update->execute();

            $update = $dbConnect->prepare("UPDATE task SET priority = :newPriority WHERE id_task = :task_id");
            $update->bindParam(':newPriority', $newPriority, PDO::PARAM_INT);
            $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
            if ($update->execute()) {
                $_SESSION['msg'] = 'priority_decreased';
                header("Location: index.php");
                exit;
            }
            $_SESSION['error'] = 'priority_decrease_failure';
            header("Location: index.php");
            exit;
        }
    }
    if ($_REQUEST['action'] === 'edit' && !empty($_REQUEST['new_description'])) {
        $newDescription = htmlspecialchars($_REQUEST['new_description']);
        $newRememberDate = !empty($_REQUEST['new_remember_date']) ? $_REQUEST['new_remember_date'] : null;

        $update = $dbConnect->prepare("UPDATE task SET description = :description, remember = :remember WHERE id_task = :task_id");
        $update->bindParam(':description', $newDescription, PDO::PARAM_STR);
        $update->bindParam(':remember', $newRememberDate);
        $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($update->execute()) {
            $_SESSION['msg'] = 'edit_success';
            header("Location: index.php");
            exit;
        }
        $_SESSION['error'] = 'edit_failure';
        header("Location: index.php");
        exit;
    }
}
?>