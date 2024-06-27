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
        <?php
        echo getHtmlErrors($errors);
        echo getHtmlMessages($messages);
        ?>
        <div class="container">
            <h2>Créer une tâche</h2>
            <form method="POST" action="actions.php">
                <div class="task__list__create">
                    <input class="container__post--add" type="text" name="description" placeholder="Ajouter chose(s) à faire" required>
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
                    $query = $dbConnect->prepare("SELECT id_task, priority, description, creation_date, done FROM task WHERE done = 0 ORDER BY priority;");
                    $query->execute();
                    $result = $query->fetchAll();
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
                            . '</li>'
                            . '</ul>'
                            . '</ul>';

                        echo '<li">'
                            . '<form method="POST" action="actions.php" class="">'
                            . '<input type="hidden" name="task_id" value="'
                            . htmlspecialchars($product['id_task']) . '">'
                            . '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">'
                            . '<input type="hidden" name="action" value="modify">'
                            . '<div class="task-title">'
                            . '<input type="text" name="newDescription" placeholder="Nouvelle tâche" class="container__post--text">'
                            . '<button type="submit" class="button__remove button__remove--mg">Modifier taches</button>'
                            . '<select name="newPriority" class="select-priority--md">'
                            . '<option value="1"' . ($product['priority'] == 1 ? ' selected' : '') . '>Haut</option>'
                            . '<option value="2"' . ($product['priority'] == 2 ? ' selected' : '') . '>Moyen</option>'
                            . '<option value="3"' . ($product['priority'] == 3 ? ' selected' : '') . '>Bas</option>'
                            . '</select>'
                            . '</div>'
                            . '</form>'
                            . '</li>';

                    } ?>
                </div>
            </div>
        </div>
        </div>
    </section>
</body>

</html>