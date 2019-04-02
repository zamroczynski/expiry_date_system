<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Panel kontrolny</title>
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
                <li><a href="adding_date.php">Dodaj terminy</a></li>
                <li><a href="edit_date.php">Edytuj terminy</a></li>
                <li><a href="#">Dodaj produkty</a></li>
                <li><a href="#">Edytuj produkty</a></li>
                <li><a href="#">Dodaj wiadomości</a></li>
                <li><a href="#">Edytuj wiadomości</a></li>
                <li><a href="#">Profil</a></li>
                <li class="last"><a href="log_out.php">Wyloguj się</a></li>
            </ul>
        </div>
        <div class="hello_div">
            <h2>Witaj <?= $_SESSION['user_name'] ?> </h2>
            <p>Ranga: 
            <?php
                if($_SESSION['user_power'] == 10) echo 'Admin';
                if($_SESSION['user_power'] == 8) echo 'Prowadzący Stacje';
                if($_SESSION['user_power'] == 6) echo 'Instruktor';
                if($_SESSION['user_power'] == 4) echo 'Prowadzący zmianę';
                if($_SESSION['user_power'] == 2) echo 'Pracownik';
                if($_SESSION['user_power'] == 1) echo 'Nowy';
                if($_SESSION['user_power'] == 0) echo 'Gość';
            ?>
            </p>
            Ostatnie logowanie <?= $_SESSION['user_last_login'] ?>
        </div>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
