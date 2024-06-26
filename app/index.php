<?php
session_start();
include "include/_config.php";
include "include/_functions.php";

// Génération ou vérification de token CSRF pour sécuriser les formulaires
generateToken();

// Ajouter une nouvelle tâche
if (!empty($_POST['description']) && isset($_POST['button__add'])) {
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

// Gérer les actions sur les tâches existantes
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
    } elseif ($_POST['action'] === 'done') {
        $update = $dbConnect->prepare("UPDATE task SET done = 1 WHERE id_task = :task_id");
        $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        if ($update->execute()) {
            header("Location: index.php?msg=done_success");
            exit;
        }
        header("Location: index.php?msg=update_failure");
        exit;
    }
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
        $errors = [
            'csrf' => 'Session invalide ou modifiée',
            'referer' => 'Référent non autorisé',
            'insert_ko' => 'Échec de l\'insertion de la tâche',
            'delete_failure' => 'Échec de la suppression de la tâche',
            'update_failure' => 'Échec de la mise à jour de la tâche'
        ];
        $messages = [
            'insert_ok' => 'La tâche ajoutée.',
            'delete_success' => 'La tâche supprimée.',
            'done_success' => 'Tâche faites.'
        ];

        if (isset($_GET['msg'])) {
            $msgType = isset($errors[$_GET['msg']]) ? $errors : $messages;
            echo '<p>' . $msgType[$_GET['msg']] . '</p>';
        }
        ?>
        <div class="container">
            <h2>Créer une tâche</h2>
            <form method="POST" action="">
                <div class="task__list__create">
                    <input class="container__post--text" type="text" name="description"
                        placeholder="Ajouter chose(s) à faire" required>
                    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                    <select class="select__priority" name="priority">
                        <option value="1">Haut</option>
                        <option value="2">Moyen</option>
                        <option value="3">Bas</option>
                    </select>
                    <button type="submit" name="button__add" class="button__add-task">Ajouter une tâche</button>
            </form>
            <div class="task__list">
                <div class="task-item">
                    <?php
                    $query = $dbConnect->prepare("SELECT id_task, priority, description, creation_date, done 
                                                      FROM task ORDER BY priority;");
                    $query->execute();
                    $result = $query->fetchAll();
                    foreach ($result as $product) {
                        echo '<ul class="task-title">'
                            . '<li class="container__post--text">'
                            . htmlspecialchars($product['priority']) . ' - '
                            . htmlspecialchars($product['description']) . ' '
                            . htmlspecialchars($product['creation_date']);

                        //button delete
                        echo '<form method="POST" action="" style="display: inline;">'
                            . '<input type="hidden" name="task_id" value="'
                            . htmlspecialchars($product['id_task']) . '">'
                            . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                            . '<input type="hidden" name="action" value="delete">'
                            . '<button type="submit" class="button__remove">x</button>'
                            . '</form>';
                        //button done
                        echo '<form method="POST" action="" style="display: inline;">'
                        . '<input type="hidden" name="task_id" value="'
                            . htmlspecialchars($product['id_task']) . '">'
                        . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                        . '<input type="hidden" name="action" value="done">'
                        . '<button type="submit" class="button__done"></button>'
                        . '</form></li>'
                        . '</ul>';
                    } ?>
                </div>

            </div>
        </div>
        </div>
    </section>
</body>

</html>