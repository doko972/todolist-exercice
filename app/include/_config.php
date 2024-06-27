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
?>