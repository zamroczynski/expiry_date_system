<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    if($_SESSION['user_power']<5)
    {
        $_SESSION['acces_denied'] = '<div class="error_div">Brak dostępu!</div>';
        header('Location: user_profile.php');
        exit();
    }
    if (isset($_POST['login']))
    {
        require_once 'database_connection.php';
        $login = filter_input(INPUT_POST, 'login');
        $check_login = $db->prepare('SELECT login FROM users WHERE login=:login');
        $check_login->bindValue(':login', $login, PDO::PARAM_STR);
        $check_login->execute();
        if ($check_login->rowCount()>0)
        {
            $_SESSION['login_error'] = '<div class="error_div">Istnieje już taki login!</div>';
        }
        else
        {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $name = filter_input(INPUT_POST, 'name');
            $email = filter_input(INPUT_POST, 'email');
            $new_user = $db->prepare('INSERT INTO users VALUES (null, :login, :password, :email, :name, 1, null)');
            $new_user->bindValue(':login', $login, PDO::PARAM_STR);
            $new_user->bindValue(':password', $password, PDO::PARAM_STR);
            $new_user->bindValue(':email', $email, PDO::PARAM_STR);
            $new_user->bindValue(':name', $name, PDO::PARAM_STR);
            $new_user->execute();
            $_SESSION['new_user'] = '<div class="ok">Dodano nowego pracownika</div>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Rejestracja</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Damian Zamroczynski" />

    <link rel="stylesheet" href="css/old.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"</scripts>
    <![endif]-->

</head>
<body>
    <div class="content">
        <div class="logo">
            <a href="user_profile.php"><h1>Rejestracja <span style="color:green;"> ONLINE </span>- Stacja 4449</h1></a>
        </div>
        <div style="border-top: rgb(110, 1, 1) solid 2px; margin-bottom: 20px;"></div>
        <div class="notice">
            <div style="color:red; text-align:center;">Uwaga!</div> Rejestracja nowego konta jest zarezerowana tylko dla osób nie posiadających konta w witrynie.
            Jeżeli utraciłeś(łaś) dostęp do swojego konta, to skontaktuj się pilnie z PSP Dawidem albo Damianem. 
        </div>
        <div class="registration" >
        <?php
        if (isset($_SESSION['login_error']))
        {
            echo $_SESSION['login_error'];
            unset($_SESSION['login_error']);
        }
        if (isset($_SESSION['new_user']))
        {
            echo $_SESSION['new_user'];
            unset($_SESSION['new_user']);
        }
        ?>
        <ul>
            <form method="POST">
                <li><input type="text" placeholder="LOGIN" name="login" required></li>
                <li><input type="text" placeholder="HASŁO" name="password" required></li>
                <li><input type="text" placeholder="IMIĘ I NAZWISKO" name="name" required></li>
                <li><input type="email" placeholder="EMAIL" name="email"></li>
                <li><input type="submit" value="Załóż nowe konto!"></li>
            </form>
        <ul>
        </div>
        <div class="footer">Terminy <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
