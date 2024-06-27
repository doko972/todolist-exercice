<?php
session_start();
include "include/_config.php";
include "include/_functions.php";

generateToken();

// if (!empty($_POST['task_id']) && !empty($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
//     $taskId = intval($_POST['task_id']);
//     if ($_POST['action'] === 'delete') {
//         $delete = $dbConnect->prepare("DELETE FROM task WHERE id_task = :task_id");
//         $delete->bindParam(':task_id', $taskId, PDO::PARAM_INT);
//         if ($delete->execute()) {
//             header("Location: index.php?msg=delete_success");
//             exit;
//         }
//         header("Location: index.php?msg=delete_failure");
//         exit;
//     }
//     if ($_POST['action'] === 'done') {
//         $update = $dbConnect->prepare("UPDATE task SET done = 1 WHERE id_task = :task_id");
//         $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
//         if ($update->execute()) {
//             header("Location: index.php?msg=done_success");
//             exit;
//         }
//         header("Location: index.php?msg=update_failure");
//         exit;
//     }
//     if ($_POST['action'] === 'update' && !empty($_POST['new_description'])) {
//         $update = $dbConnect->prepare("UPDATE task SET description = :description WHERE id_task = :task_id");
//         $update->bindValue(':description', htmlspecialchars($_POST['new_description']), PDO::PARAM_STR);
//         $update->bindParam(':task_id', $taskId, PDO::PARAM_INT);
//         if ($update->execute()) {
//             header("Location: index.php?msg=update_success");
//             exit;
//         }
//         header("Location: index.php?msg=update_failure");
//         exit;
//     }
// }
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de tâches</title>
    <link rel="stylesheet" href="styles.css">
</head>
<header>
    <nav class="navbar">
        <div class="container__nav">
            <input type="checkbox" name="" id="">
            <div class="hamburger-lines">
                <span class="line line1"></span>
                <span class="line line2"></span>
                <span class="line line3"></span>
            </div>
            <ul class="menu-items">
                <li><a href="index.php">Accueil</a></button></li>
            </ul>
        </div>
    </nav>
</header>

<body>
    <section>
        <h1>Fiche de tâches</h1>
        <?php
        echo getHtmlErrors($errors);
        echo getHtmlMessages($messages);
        ?>
        <div class="container">
            <h2>Créer une tâche</h2>
            <form method="POST" action="actions.php">
                <div class="task__list__create">
                    <input class="container__post--text" type="text" name="description"
                        placeholder="Ajouter chose(s) à faire" required>
                    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                    <select class="select__priority" name="priority">
                        <option value="1">Haut</option>
                        <option value="2">Moyen</option>
                        <option value="3">Bas</option>
                    </select>
                </div>
                <button type="submit" name="buttonAdd" class="button__add-task">Ajouter une tâche</button>
            </form>
            <div class="task__list">
                <div class="task-item">
                    <?php
                    $query = $dbConnect->prepare("SELECT id_task, priority, description, creation_date, done 
                                                      FROM task WHERE done = 0 ORDER BY priority;");
                    $query->execute();
                    $result = $query->fetchAll();
                    foreach ($result as $product) {
                        echo '<ul class="task-title">'
                            . '<li class="container__post--text">'
                            . htmlspecialchars($product['priority']) . ' - '
                            . htmlspecialchars($product['description']) . ' '
                            . htmlspecialchars($product['creation_date'])
                            . '</li>';
                        //update text
                        // echo '<li>'
                        //     . '<form method="POST" action="actions.php">'
                        //     . '<input type="hidden" name="task_id" value="'
                        //     . htmlspecialchars($product['id_task'])
                        //     . '">'
                        //     . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                        //     . '<input type="hidden" name="action" value="update">'
                        //     . '<input type="text" class="container__post--updatetext" name="new_description" value="'
                        //     . htmlspecialchars($product['description'])
                        //     . '" required>'
                        //     . '<button type="submit" class="button__done">Enregistrer</button>'
                        //     . '</form></li>';
                        //button delete
                        echo '<li><form method="POST" action="actions.php" style="display: inline;">'
                            . '<input type="hidden" name="task_id" value="'
                            . htmlspecialchars($product['id_task']) . '">'
                            . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                            . '<input type="hidden" name="action" value="delete">'
                            . '<button type="submit" class="button__done">Supprimer</button>'
                            . '</form>';

                        //button done
                        echo '<form method="POST" action="actions.php" style="display: inline;">'
                            . '<input type="hidden" name="task_id" value="'
                            . htmlspecialchars($product['id_task']) . '">'
                            . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                            . '<input type="hidden" name="action" value="done">'
                            . '<button type="submit" class="button__done">Tâche faite</button>'
                            . '</form>';

                        //button update text
                        echo '<form method="POST" action="actions.php" style="display: inline;">'
                            . '<input type="hidden" name="task_id" value="'
                            . htmlspecialchars($product['id_task']) . '">'
                            . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                            . '<input type="hidden" name="action" value="updatetext">'
                            . '<button type="submit" class="button__done">Modifier</button>'
                            . '</form></li>';
                        echo '</ul>';
                    } ?>
                </div>

            </div>
        </div>
        </div>
    </section>
</body>

</html>