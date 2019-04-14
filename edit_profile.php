<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    require_once 'database_connection.php';
    if (isset($_POST['OLDname']))
    {
        $name = filter_input(INPUT_POST, 'OLDname');
        $search_user = $db->prepare('SELECT * FROM users WHERE name LIKE :name');
        $search_user->bindValue(':name', $name, PDO::PARAM_STR);
        $search_user->execute();
        if($search_user->rowCount()>0)
        {
            $user = $search_user->fetch();
            $choose = $_POST['choose'];
        }
        else
        {
            $_SESSION['search_error'] = '<div class="error_div">Nie znaleziono użytkownika!</div>';
        }
    }
    if (isset($_POST['NEWlogin']))
    {
        $new_login = filter_input(INPUT_POST, 'NEWlogin');
        $check_login = $db->prepare('SELECT login from users WHERE login=:login');
        $check_login->bindValue(':login', $new_login, PDO::PARAM_STR);
        $check_login->execute();
        if ($check_login->rowCount()>0)
        {
            $_SESSION['login_error'] = '<div class="error_div">Istnieje już taki login w bazie, wybierz inny!</div>';
        }
        else
        {
            $new_password = password_hash($_POST['NEWpassword'], PASSWORD_DEFAULT);
            $id = filter_input(INPUT_POST, 'id');
            $update_user = $db->prepare('UPDATE users SET login=:newLogin, password=:newPassword WHERE id='.$id);
            $update_user->bindValue(':newLogin', $new_login, PDO::PARAM_STR);
            $update_user->bindValue(':newPassword', $new_password, PDO::PARAM_STR);
            $update_user->execute();
            $_SESSION['updated'] = '<div class="ok">Login i hasło zmienione!</div>';
        }
    }
    if (isset($_POST['NEWname']))
    {
        $new_name = filter_input(INPUT_POST, 'NEWname');
        $id = filter_input(INPUT_POST, 'id');
        $update_user = $db->prepare('UPDATE users SET name=:newName WHERE id='.$id);
        $update_user->bindValue(':newName', $new_name, PDO::PARAM_STR);
        $update_user->execute();
        $_SESSION['updated'] = '<div class="ok">Imię i nazwisko zmienione!</div>';
    }
    if (isset($_POST['NEWpower']))
    {
        $new_power = filter_input(INPUT_POST, 'NEWpower');
        $id = filter_input(INPUT_POST, 'id');
        $update_user = $db->prepare('UPDATE users SET power=:newPower WHERE id='.$id);
        $update_user->bindValue(':newPower', $new_power, PDO::PARAM_STR);
        $update_user->execute();
        $_SESSION['updated'] = '<div class="ok">Uprawnienia zmienione!</div>';
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Edycja pracownika</title>
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
            <a href="user_profile.php"><h1>Edycja <span style="color:green;"> Pracownika </span>- Stacja 4449</h1></a>
        </div>
        <div style="border-top: rgb(110, 1, 1) solid 2px; margin-bottom: 20px;"></div>
        
        <div class="search_user">
            <?php
            if(isset($_SESSION['search_error']))
            {
                echo $_SESSION['search_error'];
                unset($_SESSION['search_error']);
            }
            if(isset($_SESSION['updated']))
            {
                echo $_SESSION['updated'];
                unset($_SESSION['updated']);
            }
            if(isset($_SESSION['login_error']))
            {
                echo $_SESSION['login_error'];
                unset($_SESSION['login_error']);
            }
            ?>
            <form method="POST">
            <label>Co chcesz zmienić?
                <select name="choose">
                    <option value="login">Login i hasło</option>
                    <option value="name">Imię i nazwikso</option>
                    <option value="power">Prawa</option>
                </select></label><br />
                <input type="text" name="OLDname" placeholder="Wpisz poprawne imię i nazwisko" required>
                
                <input type="submit" value="Szukaj">
            </form>
        </div>
        
        <?php
            if(isset($user))
            {
                if($choose == "login")
                {
                    echo 'Edycja loginu i hasła:
                    <form method="POST">
                    <ul>
                    <li><input type="text" placeholder="LOGIN" name="NEWlogin" value="'.$user['login'].'" required></li>
                    <li><input type="text" placeholder="HASŁO" name="NEWpassword" required></li>
                    <li><input type="submit" value="Edytuj"></li>
                    </ul><input type="hidden" name="id" value="'.$user['id'].'"></form>
                    ';
                }
                if($choose == "name")
                {
                    echo 'Edycja imienia i nazwiska:
                    <form method="POST">
                    <ul>
                    <li><input type="text" placeholder="IMIĘ I NAZWISKO" name="NEWname" value="'.$user['name'].'" required></li>
                    <li><input type="submit" value="Edytuj"></li>
                    </ul><input type="hidden" name="id" value="'.$user['id'].'"></form>
                    ';
                }
                if($choose == "power")
                {
                    echo 'Uprawnienia '.$user['name'].' to: ';
                    if($user['power'] == 10) echo 'Administrator';
                    if($user['power'] == 8) echo 'Prowadzący Stacje';
                    if($user['power'] == 7) echo 'Zastępca PSP';
                    if($user['power'] == 6) echo 'Instruktor';
                    if($user['power'] == 4) echo 'Prowadzący zmianę';
                    if($user['power'] == 2) echo 'Pracownik';
                    if($user['power'] == 1) echo 'Nowy Pracownik';
                    if($user['power'] == 0) echo 'Gość';
                    echo '<br/>Edycja uprawnień:
                    <form method="POST">
                    <select name="NEWpower">
                        <option value="0">Gość</option>
                        <option value="1">Nowy Pracownik</option>
                        <option value="2">Pracownik</option>
                        <option value="4">Prowadzacy zmianę</option>
                        <option value="6">Instruktor</option>
                        <option value="7">Zastępca PSP</option>
                        <option value="8" disabled>Prowadzący Stacje</option>
                        <option value="10" disabled>Administrator</option>
                    </select>
                    <input type="submit" value="Edytuj">
                    <input type="hidden" name="id" value="'.$user['id'].'"></form>
                    ';
                }
            }
            ?>

        <div class="footer">Terminy <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
