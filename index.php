<?php
/**
 * Connect to DB
 */
/** @var \PDO $pdo */
require_once './pdo_ini.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
}
if (!isset($_SESSION['todo_list_id'])) {
    $sth = $pdo->prepare("
			INSERT INTO todo_lists (created_at)
			VALUES (NOW())
		");
    $sth->execute();
    $_SESSION['todo_list_id'] = $pdo->lastInsertId();
}
$sth = $pdo->prepare('SELECT id, is_done, title, user_id FROM todo_tasks WHERE todo_list_id = :todo_list_id');
$sth->setFetchMode(\PDO::FETCH_ASSOC);
$sth->execute(['todo_list_id' => $_SESSION['todo_list_id']]);
$tasks = $sth->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<header class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-body border-bottom shadow-sm">
    <a class="btn btn-outline-primary" href="manage.php?as=logout&task">Sign out</a>
</header>
<main role="main" class="container">
    <h1 class="mt-5">To do list</h1>
    <?php if (!empty($tasks)): ?>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Task</th>
                <th scope="col">Action</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php foreach ($tasks as $task): ?>
            <tr>
                <th scope="col">
                    <p> <?php echo $task['title']; ?></p>
                </th>
                <th scope="col">
                    <a class="delete-button" href="manage.php?as=delete&task=<?php echo $task['id']; ?>">Delete</a>
                </th>
                <th scope="col">
                    <?php if (!$task['is_done']): ?>
                        <a class="done-button" href="manage.php?as=done&task=<?php echo $task['id']; ?>">Make it done</a>
                    <?php else: ?>
                        <p>Done</p>
                    <?php endif; ?>
                </th>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't added any tasks yet.</p>
    <?php endif; ?>
    <form action="manage.php" method="POST">
        <input type="text" name="task" placeholder="Type a new task here." class="input" autocomplete="off"
               maxlength="30" required>
        <input type="submit" value="Add" class="submit">
    </form>
</main>
</body>
</html>