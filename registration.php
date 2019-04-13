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
            <a href="index.php"><h1>Rejestracja <span style="color:green;"> ONLINE </span>- Stacja 4449</h1></a>
        </div>
        <div style="border-top: rgb(110, 1, 1) solid 2px; margin-bottom: 20px;"></div>
        <div class="notice">
            <div style="color:red; text-align:center;">Uwaga!</div> Rejestracja nowego konta jest zarezerowana tylko dla osób nie posiadających konta w witrynie.
            Jeżeli utraciłeś(łaś) dostęp do swojego konta, to skontaktuj się pilnie z PSP Dawidem albo Damianem. 
        </div>
        <div class="registration" >
        <ul>
            <form method="POST">
                <li><input type="text" placeholder="LOGIN"></li>
                <li><input type="text" placeholder="HASŁO"></li>
                <li><input type="text" placeholder="IMIĘ I NAZWISKO"></li>
                <li><input type="email" placeholder="EMAIL"></li>
                <li><input type="submit" value="Załóż nowe konto!"></li>
            </form>
        <ul>
        </div>
        <div class="footer">Terminy <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
