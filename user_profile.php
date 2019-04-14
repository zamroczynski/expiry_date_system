<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    if(isset($_POST['old_password']))
    {
        $old_password = filter_input(INPUT_POST, 'old_password');
        require_once 'database_connection.php';
        $change_pass_query = $db->prepare('SELECT password FROM users WHERE password=:oldPassword AND id='.$_SESSION['user_id']);
        $change_pass_query->bindValue(':oldPassword', $old_password, PDO::PARAM_STR);
        $change_pass_query->execute();
        if($change_pass_query->rowCount()>0)
        {
            if($_POST['new1_password']==$_POST['new2_password'])
            {
                $new_password = filter_input(INPUT_POST, 'new2_password');
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $change_pass_query = $db->prepare('UPDATE users SET password=:newPassword WHERE id='.$_SESSION['user_id']);
                $change_pass_query->bindValue(':newPassword', $password_hash, PDO::PARAM_STR);
                $change_pass_query->execute();
                $_SESSION['change_pass_success'] = '<div class="ok">Hasło zmienione</div>';
            }
            else
            {
                $_SESSION['new_error'] = '<div class="error_div">Hasła nie są takie same!</div>';
            }
        }
        else
        {
            $_SESSION['old_error'] = '<div class="error_div">Błędne stare hasło!</div>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Mój profil</title>
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
            <a href="index.php"><h1>Terminy <span style="color:green;"> ONLINE </span>- Stacja 4449</h1></a>
        </div>
        <div class="main_bar">
            <ul class="nav">
                <li>
                    <a href="#">Terminy</a>
                    <ul>
                        <li><a href="adding_date.php">Dodaj terminy</a></li>
                        <li><a href="edit_date.php">Edytuj terminy</a></li>
                        <li><a href="generate_report.php">Generuj Raport</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="#">Produkty</a>
                    <ul>
                        <li><a href="adding_products.php">Dodaj produkty</a></li>
                        <li><a href="edit_products.php">Edytuj produkty</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Wiadomości</a>
                    <ul>
                        <li><a href="adding_messages.php">Dodaj wiadomości</a></li>
                        <li><a href="edit_messages.php">Edytuj wiadomości</a></li>
                    </ul>
                </li>
                <li><a href="user_profile.php" class="active">Profil</a></li>
                <li class="last"><a href="log_out.php">Wyloguj się</a></li>
            </ul>
        </div>
        <div class="hello_div">
            <?php
            if (isset($_SESSION['acces_denied']))
            {
                echo $_SESSION['acces_denied'];
                unset($_SESSION['acces_denied']);
            }
            ?>
            <h2>Witaj <?= $_SESSION['user_name'] ?> </h2>
            <p>Ranga: 
            <?php
                if($_SESSION['user_power'] == 10) echo 'Admin';
                if($_SESSION['user_power'] == 8) echo 'Prowadzący Stacje';
                if($_SESSION['user_power'] == 7) echo 'Zastępca PSP';
                if($_SESSION['user_power'] == 6) echo 'Instruktor';
                if($_SESSION['user_power'] == 4) echo 'Prowadzący zmianę';
                if($_SESSION['user_power'] == 2) echo 'Pracownik';
                if($_SESSION['user_power'] == 1) echo 'Nowy';
                if($_SESSION['user_power'] == 0) echo 'Gość';
            ?>
            </p>
            Ostatnie logowanie <?= $_SESSION['user_last_login'] ?>
            </div>
        <div class="change_password">
            <h2>Zmiana hasła</h2>
            <ol>
                <form method="POST">
                    <li><input type="password" name="old_password" placeholder="Stare hasło" required/></li>
                    <li><input type="password" name="new1_password" placeholder="Nowe hasło" required/></li>
                    <li><input type="password" name="new2_password" placeholder="Powtórz hasło" required/></li>
                    <li><input type="submit" value="Zmień hasło" /></li>
                </form>
            </ol>
            <?php
            if(isset($_SESSION['old_error']))
            {
                echo $_SESSION['old_error'];
                unset($_SESSION['old_error']);
            }
            if(isset($_SESSION['new_error']))
            {
                echo $_SESSION['new_error'];
                unset($_SESSION['new_error']);
            }
            if(isset($_SESSION['change_pass_success']))
            {
                echo $_SESSION['change_pass_success'];
                unset($_SESSION['change_pass_success']);
            }
            ?>
        </div>
        <?php
        if($_SESSION['user_power']>5)
        {
            echo '<div style="border-top: rgb(110, 1, 1) solid 2px;"></div>';
            echo '<h2><a href="registration.php">Utwórz nowe konto</a></h2>';
        }
        ?>
        <div class="footer">Terminy <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
