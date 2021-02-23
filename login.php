<?php
session_start();
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">
        <title>Login</title>
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
            <h3 class="panel-title">Sign In</h3>
        </div>
        <div class="panel-body">
            <form role="form" method="post" action="login.php">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="E-mail" name="email" type="email"
                               autofocus>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Password" name="pass" type="password"
                               value="">
                    </div>
                    <input class="btn btn-lg btn-success btn-block" type="submit" value="Login"
                           name="login">
                </fieldset>
            </form>
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

if (isset($_POST['login'])) {
    $sth = $pdo->prepare('SELECT id FROM users WHERE email = :email AND password=:pass');
    $sth->setFetchMode(\PDO::FETCH_ASSOC);
    $sth->execute(['email' => $_POST['email'], 'pass' => md5($_POST['pass'])]);
    $user_id = $sth->fetch();
    if ($user_id) {
        $_SESSION['user_id'] = $user_id['id'];
        header('Location: index.php');
    } else {
        echo "<script>alert('Email or password is incorrect!')</script>";
    }
}
