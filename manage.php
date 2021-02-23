<?php

/**
 * Connect to DB
 */
/** @var \PDO $pdo */
require_once './pdo_ini.php';
session_start();

if (isset($_POST['task']) && !empty(trim($_POST['task']))) {
    $sth = $pdo->prepare('
			INSERT INTO todo_tasks (todo_list_id, is_done, title, user_id, created_at)
			VALUES (:todo_list_id, 0, :title, :user_id, NOW())
		');
    $sth->execute([
        'todo_list_id' => $_SESSION['todo_list_id'],
        'title' => trim($_POST['task']),
        'user_id' => $_SESSION['user_id'],
    ]);
}

if (isset($_GET['as'], $_GET['task'])) {
    switch ($_GET['as']) {
        case 'done':
            $sth = $pdo->prepare("
				UPDATE todo_tasks 
				SET is_done = 1
				WHERE id = :id
				AND todo_list_id = :todo_list_id
			");
            $sth->execute([
                'id' => $_GET['task'],
                'todo_list_id' => $_SESSION['todo_list_id']
            ]);
            break;
        case 'delete':
            $sth = $pdo->prepare('
				DELETE FROM todo_tasks
				WHERE id = :id
				AND todo_list_id = :todo_list_id
			');
            $sth->execute([
                'id' => $_GET['task'],
                'todo_list_id' => $_SESSION['todo_list_id']
            ]);
            break;
        case 'logout':
            session_destroy();
            header('Location: login.php');
            break;
    }
}
header('Location: index.php');