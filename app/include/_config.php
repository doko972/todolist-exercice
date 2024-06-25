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
?>