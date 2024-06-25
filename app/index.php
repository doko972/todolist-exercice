<?php
include "include/_config.php";
// include "include/_token.php";

session_start(); // important pour demarrer $_SESSION
//stocker la donnée coté serveur pour chaque utilisateur
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = md5(uniqid(mt_rand(), true));
}

// tokenCreate($SERVER);

// $query = $dbConnect->prepare("SELECT priority, description, creation_date, done FROM task ORDER BY priority DESC;");
// $query->execute();


$addRequest = '<p>La tâche à été ajouté</p>';

/*INSERT*/
if (!empty($_POST)) {
    // L'user est'il bien chez moi
    if (isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'http://localhost:8080')) {
        // var_dump('http referer ok');
        if (isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']) {
            if (
                isset($_POST['description'])
                && strlen($_POST['description']) > 0
                && strlen($_POST['description']) <= 50
            ) {
                $insert = $dbConnect->prepare("INSERT INTO `task` (`priority`, `description`, `creation_date`, `done`) 
            VALUES (:priority, :description, NOW(), 0);");

                $insert->bindValue(':priority', htmlspecialchars($_POST['priority']));
                $insert->bindValue(':description', htmlspecialchars($_POST['description']));
                $isInsertOk = $insert->execute();
                $nb = $insert->rowCount();

                if ($isInsertOk) {
                    echo $addRequest;
                } else {
                    echo "<p>Erreur lors de l'ajout d'une tâche</p>";
                }
            }
        }

    } else {
        echo '<div class="no-tasks">Pas de tâches</div>';
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
        <div class="container">
            <h2>Créer une tâche</h2>
            <form method="POST" action="">
                <div class="task__list__create">
                    <input class="container__post--text" type="text" name="description"
                        placeholder="Ajouter chose(s) à faire" required>
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <select class="select__priority" name="priority">
                        <option value="High">Haut</option>
                        <option value="Medium">Moyen</option>
                        <option value="Low">Bas</option>
                    </select>
                </div>
                <button type="submit" name="button__add" class="button__add-task">+</button>
            </form>
            <div class="task__list">

                <div class="task-item">
                    <?php 
                    $query = $dbConnect->prepare("SELECT priority, description, creation_date, done FROM task ORDER BY priority DESC;");
                    $query->execute();
                    $result = $query->fetchAll();
                    foreach ($result as $product) {
                        echo '<ul class="task-title">';
                        echo '<li class="container__post--text ">' . $product['priority'] . ' - ' . $product['description'] . ' ' . $product['creation_date'] . '<button class="button__remove">x</button>' . '</li>';
                        echo '<div>';
                        echo '<label for="done">Fait</label>';
                        echo '<input class="done" type="checkbox" id="done" name="done" checked />';
                        echo '</div>';
                        echo '</ul>';
                    } ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>




<!-- <section>
    <div class="alert-task" id="alert-task">
            <div class="alert-task-content">
                <div class="alert-task-header">
                    <h2>La tâche à bien été ajouté</h2>
                </div>
                <div class="alert-task-body">
                    <button class="alert-task-button" id="validButton">Ok</button>
                </div>
            </div>
        </div>
        <div class="alert-task" id="alert-task">
            <div class="alert-task-content">
                <div class="alert-task-header">
                    <h2>Supprimer cette tâche?</h2>
                </div>
                <div class="alert-task-body">
                    <button class="alert-task-button" id="yesButton">Oui</button>
                    <button class="alert-task-button" id="noButton">Non</button>
                </div>
            </div>
        </div>
    </section> -->