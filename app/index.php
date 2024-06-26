<?php
session_start();
include "include/_config.php";
include "include/_functions.php";

// var_dump($_SERVER['REQUEST_METHOD']);
generateToken();

if (!empty($_POST['description'])) {

    // if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], 'http://localhost:8080')) {
    //     $_SESSION['error'] = 'referer';
    //     header('Location: index.php');
    //     exit;
    // }
    // if (!isset($_SESSION['token']) || !isset($_POST['token']) || $_SESSION['token'] !== $_POST['token']) {
    //     $_SESSION['error'] = 'csrf';
    //     header('Location: index.php'); //generation d'un token
    //     exit;
    // }
    $newUrl = 'http://localhost:8080/index.php';
    refererHttp($newUrl);
    errorCsrf();


    if (strlen($_POST['description']) > 0 && strlen($_POST['description']) <= 150) {
        $insert = $dbConnect->prepare("INSERT INTO `task` (`priority`, `description`, `creation_date`, `done`) 
            VALUES (:priority, :description, NOW(), 0);");
        $insert->bindValue(':priority', htmlspecialchars($_POST['priority']));
        $insert->bindValue(':description', htmlspecialchars($_POST['description']));
        if ($insert->execute()) {
            header('Location: index.php?msg=insert_ok');
        } else {
            header('Location: index.php?msg=insert_ko');
        }
        exit;
    }
}


if (!empty($_POST['task_id']) && !empty($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
    $taskId = intval($_POST['task_id']);
    $delete = $dbConnect->prepare("DELETE FROM task WHERE id_task = :task_id");
    $delete->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    if ($delete->execute()) {
        header("Location: index.php?msg=delete_success");
    } else {
        header("Location: index.php?msg=delete_failure");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de tâches</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <section>
        <h1>Fiche de tâches</h1>
        <?php
        $error = [
            'csrf' => 'Session invalide ou modifiée',
            'referer' => 'Référent non autorisé',
            'insert_ko' => 'Échec de l\'insertion de la tâche',
            'delete_failure' => 'Échec de la suppression de la tâche'
        ];
        $message = ['insert_ok' => 'Tâche ajoutée avec succès.', 'delete_success' => 'Tâche supprimée avec succès.'];

        if (isset($_GET['msg'])) {
            $msgType = isset($error[$_GET['msg']]) ? $error : $message;
            echo '<p>' . $msgType[$_GET['msg']] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <div class="container">
            <h2>Créer une tâche</h2>
            <form method="POST" action="">
                <div class="task__list__create">
                    <input class="container__post--text" type="text" name="description"
                        placeholder="Ajouter chose(s) à faire" required>
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <select class="select__priority" name="priority">
                        <option value="Haut">Haut</option>
                        <option value="Moyen">Moyen</option>
                        <option value="Bas">Bas</option>
                    </select>
                    <button type="submit" name="button__add" class="button__add-task">Ajouter une tâche</button>
            </form>
            <div class="task__list">
                <div class="task-item">
                    <?php
                    $query = $dbConnect->prepare("SELECT id_task, priority, description, creation_date, done 
                    FROM task ORDER BY priority DESC;");
                    $query->execute();
                    $result = $query->fetchAll();
                    foreach ($result as $product) {
                        echo '<ul class="task-title">';
                        echo '<li class="container__post--text">'
                            . htmlspecialchars($product['priority']) . ' - '
                            . htmlspecialchars($product['description']) . ' '
                            . htmlspecialchars($product['creation_date']);
                        echo '<form method="POST" action="" style="display: inline;">';
                        echo '<input type="hidden" name="task_id" value="'
                            . htmlspecialchars($product['id_task']) . '">';
                        echo '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">';
                        echo '<button type="submit" class="button__remove">x</button>';
                        echo '</form></li>';
                        echo '</ul>';
                    } ?>
                </div>
            </div>
        </div>
    </section>
</body>

</html>