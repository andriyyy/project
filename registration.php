<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Registration</title>
</head>
<style>
    .login-panel {
        margin: 0 auto;
        margin-top: 150px;
        width: 300px;
    }
</style>
<body class="text-center">
<div class="login-panel panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Registration</h3>
    </div>
    <div class="panel-body">
        <form role="form" method="post" action="registration.php">
            <fieldset>
                <div class="form-group">
                    <input class="form-control" placeholder="Username" name="name" type="text" autofocus>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Password" name="pass" type="password" value="">
                </div>
                <input class="btn btn-lg btn-success btn-block" type="submit" value="Register" name="register">
            </fieldset>
        </form>
        <b>Already registered ?</b> <br></b><a href="login.php">Login here</a>
    </div>
</div>
</body>
</html>

<?php
/**
 * Connect to DB
 */
/** @var \PDO $pdo */
require_once './pdo_ini.php';
if (isset($_POST['register'])) {
    $user_name = $_POST['name'];
    $user_pass = $_POST['pass'];
    $user_email = $_POST['email'];

    if ($user_name == '') {
        echo "<script>alert('Please enter the name')</script>";
        exit();
    }

    if ($user_pass == '') {
        echo "<script>alert('Please enter the password')</script>";
        exit();
    }

    if ($user_email == '') {
        echo "<script>alert('Please enter the email')</script>";
        exit();
    }
    $sth = $pdo->prepare('SELECT id FROM users WHERE email = :user_email');
    $sth->setFetchMode(\PDO::FETCH_ASSOC);
    $sth->execute(['user_email' => $user_email]);

    if ($sth->rowCount() > 0) {
        echo "<script>alert('Email $user_email is already exist in our database, Please try another one!')</script>";
        exit();
    }
    $sth = $pdo->prepare('
			INSERT INTO users (username,password,email)
			VALUES (:username,:password, :email)
		');
    if ($sth->execute(['username' => $user_name, 'email' => $user_email, 'password' => md5($user_pass)])) {
        header('Location: login.php');
    }
}
?>
