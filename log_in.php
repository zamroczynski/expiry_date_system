<?php
    session_start();
    if (isset($_SESSION['logged']))
    {
        header('Location: control_panel.php');
        exit();
    }
    if ((isset($_POST['login'])) || (isset($_POST['password'])))
    {
        $login = filter_input(INPUT_POST, 'login');
        $password = filter_input(INPUT_POST, 'password');
        require_once 'database_connection.php';
        $user_query = $db->prepare('SELECT users.login, users.password, users.email, users.name, users.power, users.last_login 
        FROM users 
        WHERE users.login=:login AND users.password=:password');
        $user_query->bindValue(':login', $login, PDO::PARAM_STR);
        $user_query->bindValue(':password', $password, PDO::PARAM_STR);
        $user_query->execute();
        $user = $user_query->fetch();
        echo $login . ' ';
        echo $password;
        echo $user_query->rowCount();
        if ($user)
        {
            $_SESSION['logged'] = true;
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_power'] = $user['power'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_last_login'] = $user['last_login'];
            header('Location: control_panel.php');
            exit();
        }
        else
        {
            $_SESSION['login_error'] = "Nie prawidłowy login lub hasło";
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Zaloguj</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Damian Zamroczynski" />

    <link rel="stylesheet" href="main.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"</scripts>
    <![endif]-->
</head>
<body>
    <div class="content">
        <div class="logo">
            <a href="index.php"><h1>Terminy <span style="color:green;"> ONLINE </span>- Stacja 4449</h1></a>
        </div>
        <div class="main_bar">
            <ul class="nav">
                <li><a href="index.php">Terminy</a></li>
                <li><a href="messages.php">Wiadomości</a></li>
                <li class="last"><a href="log_in.php" class="active">Logowanie</a></li>
            </ul>
        </div>
        <div class="login_area">
            <ul>
                <div style="margin-left:auto; margin-right:auto; margin-bottom:5px;">LOGOWANIE</div>
                    <form action="log_in.php" method="post">
                        <li><input type="text" name="login" placeholder="Twój login..." /></li>
                        <li><input type="password" name="password" placeholder="Twoje hasło..." /></li>
                        <li><input type="submit" value="Zaloguj się!" /></li>
                    </form>
            </ul>
            <div class="error_div"><?= isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '' ?></div>
        </div>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
    <?php unset($_SESSION['login_error']) ?>
</body>
</html>
