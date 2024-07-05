<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv=Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// var_dump($_ENV);

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
    'update_failure' => 'Échec de la mise à jour de la tâche',
    'edit_failure' => 'Échec de la modification de la tâche'
];

$messages = [
    'insert_ok' => 'La tâche a été ajoutée avec succès.',
    'delete_success' => 'La tâche a été supprimée avec succès.',
    'done_success' => 'La tâche a été marquée comme faite.',
    'priority_increased' => 'La priorité a été augmentée.',
    'priority_increase_failure' => 'Échec de l\'augmentation de la priorité.',
    'priority_decreased' => 'La priorité a été diminuée.',
    'priority_decrease_failure' => 'Échec de la diminution de la priorité.',
    'priority_min_reached' => 'La priorité ne peut pas descendre en dessous de 1.',
    'edit_success' => 'La tâche a été modifiée avec succès.'
];
?>