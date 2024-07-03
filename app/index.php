<?php
session_start();
include "include/_config.php";
include "include/_functions.php";

generateToken();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de tâches</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
    <script>
        function showEditForm(taskId) {
            document.getElementById('editForm-' + taskId).classList.remove('hidden');
        }
    </script>
</head>

<body>
    <header class="header">
        <nav class="navbar">
            <div class="container__nav">
                <input type="checkbox" id="menu-toggle">
                <div class="hamburger-lines">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </div>
                <ul class="menu-items">
                    <li><a href="index.php">Accueil</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <section>
        <h1>Fiche de tâches</h1>
        <div class="container">
            <h2>Créer une tâche</h2>
            <?php
            echo getHtmlErrors($errors);
            echo getHtmlMessages($messages);
            ?>
            <form method="POST" action="actions.php">
                <div class="task__list__create">
                    <input class="container__post--add" type="text" name="description"
                        placeholder="Ajouter chose(s) à faire" required>
                    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                </div>
                <button type="submit" name="buttonAdd" class="button__add-task">Ajouter une tâche</button>
            </form>
            <div class="task__list">
                <div class="task-item">
                    <?php
                    $query = $dbConnect->prepare("SELECT id_task, priority, description, creation_date, done FROM task WHERE done = 0 ORDER BY priority ASC;");
                    $query->execute();
                    $result = $query->fetchAll();
                    if (empty($result)) {
                        echo "<p>Aucune tâche affichée.</p>";
                    } else {
                        foreach ($result as $product) {
                            $date = new DateTime($product['creation_date']);
                            $formattedDate = $date->format('d/m/Y');

                            echo '<ul class="container-action">'
                                . '<li class="container__post--task">'
                                . htmlspecialchars($product['priority']) . ' - '
                                . htmlspecialchars($product['description']) . ' '
                                . '<p>'
                                . $formattedDate
                                . '</p>'
                                . '</li>';

                            echo '<ul class="container-action--arrow">'
                                . '<li>'
                                . '<form method="POST" action="actions.php" style="display: inline;">'
                                . '<input type="hidden" name="task_id" value="'
                                . htmlspecialchars($product['id_task']) . '">'
                                . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                                . '<input type="hidden" name="action" value="increase_priority">'
                                . '<button type="submit" class="button__remove">↑</button>'
                                . '</form>'
                                . '<form method="POST" action="actions.php" style="display: inline;">'
                                . '<input type="hidden" name="task_id" value="'
                                . htmlspecialchars($product['id_task']) . '">'
                                . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                                . '<input type="hidden" name="action" value="decrease_priority">'
                                . '<button type="submit" class="button__remove">↓</button>'
                                . '</form>'
                                . '</li>'
                                . '</ul>';


                            echo '<ul class="action-btn">'
                                . '<li>'
                                . '<form method="POST" action="actions.php" style="display: inline;">'
                                . '<input type="hidden" name="task_id" value="'
                                . htmlspecialchars($product['id_task']) . '">'
                                . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                                . '<input type="hidden" name="action" value="delete">'
                                . '<button type="submit" class="button__remove">Supprimer</button>'
                                . '</form>'
                                . '</li>';

                            echo '<li>'
                                . '<form method="POST" action="actions.php" style="display: inline;">'
                                . '<input type="hidden" name="task_id" value="'
                                . htmlspecialchars($product['id_task']) . '">'
                                . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                                . '<input type="hidden" name="action" value="done">'
                                . '<button type="submit" class="button__remove">Fait</button>'
                                . '</form>'
                                . '<button onclick="showEditForm(' . htmlspecialchars($product['id_task']) . ')" class="button__remove">Modifier</button>'
                                . '</li>';

                            echo '<li>'
                                
                                . '</li>'
                                . '</ul>'
                                . '</ul>';

                            echo '<div id="editForm-' . htmlspecialchars($product['id_task']) . '" class="hidden">'
                                . '<form method="POST" action="actions.php">'
                                . '<input type="hidden" name="task_id" value="' . htmlspecialchars($product['id_task']) . '">'
                                . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                                . '<input type="hidden" name="action" value="edit">'
                                . '<input type="text" name="new_description" value="' . htmlspecialchars($product['description']) . '" required>'
                                . '<button type="submit" class="button__submit-edit">Valider la modification</button>'
                                . '</form>'
                                . '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>

</html>