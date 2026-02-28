<?php 
session_start();
require_once __DIR__ . '/../config.php';

if (isset($_POST['submit'])) {
    if (!empty($_POST['mail']) AND !empty($_POST['pass'])) {
        $mail = htmlspecialchars($_POST['mail']);
        $pass = $_POST['pass'];

        $getUser = $db->prepare('SELECT * FROM users WHERE mail = ?');
        $getUser->execute([$mail]);
        $user = $getUser->fetch();

        if ($user) {
            if (password_verify($pass, $user['pass'])) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['mail'] = $user['mail'];
                $_SESSION['id'] = $user['id'];

                header('Location: /web');
                exit;
            } else {
                echo 'Email ou mot de passe incorrect.';
            }
        } else {
            echo 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziven</title>
</head>
<body>
    <form action="" method="POST">
        <input type="email" name="mail" placeholder="E-Mail" required>
        <br/>
        <input type="password" name="pass" placeholder="Password" required>
        <br/>
        <input type="submit" name="submit">
    </form>
</body>
</html>