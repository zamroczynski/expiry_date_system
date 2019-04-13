<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    if($_SESSION['user_power']<2)
    {
        $_SESSION['acces_denied'] = '<div class="error_div">Brak dostępu!</div>';
        header('Location: user_profile.php');
        exit();
    }
    require_once 'database_connection.php';
    $today = new DateTime();
    $today_string = $today->format('Y-m-d');

    $string_form_message_adding = '
        <div class="adding_message_form">
            <form method="POST" action="adding_messages.php">
                <ul class="adding">
                <li class="date">Obowiązuje od <input type="date" name="first_date" value="'.$today_string.'" ></li>
                <li class="date"> do <input type="date" name="last_date" value="'.$today_string.'" ></li>
                <li><textarea name="message" ></textarea></li>
                <li>Jak ważna to wiadomość?
                <select name="rank">
                <option value="3">BARDZO WAŻNA WIADOMOŚĆ!</option>
                <option value="2">Ważna wiadmość!</option>
                <option value="1">Zwykła wiadomość</option>
                </select>
                </li>
                <li><input type="submit" value="Dodaj" /></li>
                </ul>
            </form>
        </div>';
    if (isset($_POST['message']))
    {

        $message = nl2br(filter_input(INPUT_POST, 'message'));
        $first_date = filter_input(INPUT_POST, 'first_date');
        $last_date = filter_input(INPUT_POST, 'last_date');
        $user = $_SESSION['user_id'];
        $rank = filter_input(INPUT_POST,'rank');
        if($_SESSION['user_power']>=6)
        {
            $message_query=$db->prepare('INSERT INTO messages VALUES 
            (null, :message, "'.$first_date.'", "'.$last_date.'", "'.$user.'", 1, '.$rank.')');
            $message_query->bindValue(':message', $message, PDO::PARAM_STR);
            $message_query->execute();
            $_SESSION['message_sent'] = '<div class="ok">Wiadomość wysłana!</div>';
        }
        else if($_SESSION['user_power']<=2 && $rank<=1)
        {
            $message_query=$db->prepare('INSERT INTO messages VALUES 
            (null, :message, "'.$first_date.'", "'.$last_date.'", "'.$user.'", 1, '.$rank.')');
            $message_query->bindValue(':message', $message, PDO::PARAM_STR);
            $message_query->execute();
            $_SESSION['message_sent'] = '<div class="ok">Wiadomość wysłana!</div>';
        }
        else if ($_SESSION['user_power']<6 && $_SESSION['user_power']>2 && $rank<3)
        {
            $message_query=$db->prepare('INSERT INTO messages VALUES 
            (null, :message, "'.$first_date.'", "'.$last_date.'", "'.$user.'", 1, '.$rank.')');
            $message_query->bindValue(':message', $message, PDO::PARAM_STR);
            $message_query->execute();
            $_SESSION['message_sent'] = '<div class="ok">Wiadomość wysłana!</div>';
        }
        else
        {
            $_SESSION['message_error'] = '<div class="error_div">Brak uprawnień!</div>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Dodawanie Wiadomości</title>
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
                    <a href="#" class="active">Wiadomości</a>
                    <ul>
                        <li><a href="adding_messages.php">Dodaj wiadomości</a></li>
                        <li><a href="edit_messages.php">Edytuj wiadomości</a></li>
                    </ul>
                </li>
                <li><a href="user_profile.php">Profil</a></li>
                <li class="last"><a href="log_out.php">Wyloguj się</a></li>
            </ul>
        </div>
        <?php
        if (isset($_SESSION['message_sent']))
        {
            echo '<h2>'.$_SESSION['message_sent'].'</h2>';
            unset($_SESSION['message_sent']);
        }
        if (isset($_SESSION['message_error']))
        {
            echo '<h2>'.$_SESSION['message_error'].'</h2>';
            unset($_SESSION['message_error']);
        }
        echo '<h2>Dodawanie nowej wiadomości</h2>';
        echo $string_form_message_adding;
        ?>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
