<?php
// session_start();
require "include/_config.php";
require "include/_functions.php";

if (!empty($_POST['task_id']) && !empty($_POST['token'])) {
    if ($_POST['token'] !== $_SESSION['token']) {
        header("Location: index.php?msg=csrf_error");
        exit;
    }

    $taskId = intval($_POST['task_id']);

    $delete = $dbConnect->prepare("DELETE FROM task WHERE id = :task_id");
    $delete->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    if ($delete->execute()) {
        header("Location: index.php?msg=delete_success");
    } else {
        header("Location: index.php?msg=delete_failure");
    }
} else {
    header("Location: index.php?msg=missing_data");
}
exit;
?>