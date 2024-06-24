<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de tâches</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    try {
        $dbConnect = new PDO(
            'mysql:host=db;
        dbname=todolist;
        charset=utf8',
            'tama',
            'tekmate'
        );

        $dbConnect->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

    } catch (Exception $e) {
        die('Erreur connexion mysql' . $e->getMessage());
    }

    $query = $dbConnect->prepare("SELECT priority, description, creation_date, done FROM task;");
    $query->execute();
    $result = $query->fetchAll();
    ?>
    <section>
        <h1>Fiche de tâches</h1>
        <div class="container">
            <h2>Créer une tâche</h2>
            <form method="POST" action="">
                <div class="task__list__create">
                    <input class="container__post--text" type="text" name="title" placeholder="Ajouter chose(s) à faire"
                        required>
                    <button type="submit" name="button__add" class="button__add-task">+</button>
                </div>
            </form>
            <div class="task__list">
                <div class="no-tasks">Pas de tâches</div>
                <div class="task-item">
                    <?php foreach ($result as $product) {
                        echo '<ul class="task-title">';
                        echo '<li class="container__post--text ">' . $product['priority'] . ' - ' . $product['description'] . ' ' . $product['creation_date'] . '<button class="button__remove">x</button>' . '</li>';
                        // echo '<button class="button__remove">x</button>';
                        echo '</ul>';
                    } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- <section>
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
</body>

</html>