<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    if($_SESSION['user_power']<2)
    {
        $_SESSION['acces_denied'] = '<div class="error">Brak dostępu!</div>';
        header('Location: user_profile.php');
        exit();
    }
    require_once 'database_connection.php';
    $today = new DateTime();
    $today_string = $today->format('Y-m-d');
    $message = '';
    if (isset($_POST['message']))
    {
        $message = $_POST['message'];
    }
    $string_form_message_adding = '
        <div>
            <form method="POST" enctype="multipart/form-data">
                <div>Obowiązuje od <input type="date" name="first_date" value="'.$today_string.'" >
                 do <input type="date" name="last_date" value="'.$today_string.'" ></div>
                <div><textarea name="message" class="form-control">'.$message.'</textarea></div>
                <div>Jak ważna to wiadomość?
                <select name="rank">
                <option value="1">Zwykła wiadomość</option>
                <option value="2">Ważna wiadmość!</option>
                <option value="3">BARDZO WAŻNA WIADOMOŚĆ!</option>
                </select></div>  
                <div><label class="myfile">Załaduj obraz:
                <input type="file" name="images[]" multiple>
                </label></div>
                <input type="submit" value="Dodaj" />
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
            $_SESSION['message_error'] = '<div class="error">Brak uprawnień!</div>';
        }
        if(!empty(array_filter($_FILES['images']['name'])))
        {
            $target_dir = "img/uploads/";
            $allow_Types = array('jpg','png','jpeg','gif');
            foreach($_FILES['images']['name'] as $key=>$val)
            {
                $file_name = basename($_FILES['images']['name'][$key]);
                $target_file_patch = $target_dir . $today_string . $file_name;
                $file_type = pathinfo($target_file_patch,PATHINFO_EXTENSION);
                if(in_array($file_type, $allow_Types))
                {
                    if(move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file_patch))
                    {
                        $last_message = $db->query('SELECT MAX(Id) FROM messages');
                        $id_message = $last_message->fetch(PDO::FETCH_NUM);
                        $image_insert = 'INSERT INTO images VALUES (null, '.$id_message[0].', "'.$today_string.$file_name.'")';
                        $image_query = $db->query($image_insert);
                        
                    }
                    else
                    {
                        $_SESSION['error'] = '<div class="error">Błąd przesyłu! Spróbuj ponownie</div>';
                    }
                }
                else
                {
                    $_SESSION['error'] = '<div class="error">Nie dozwolony format pliku</div>';
                    
                }
            }
            $_SESSION['message_sent'] .= '<div class="ok">Obraz(y) dodane!</div>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <link rel="icon" href="img/icon.png">
        <title>Stacja Paliw 4449</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="author" content="Damian Zamroczynski" />
        <link rel="stylesheet" href="css/fontello.css">
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/main.css" />
        
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"</scripts>
        <![endif]-->
    </head>
    <body>
        <header>
            <nav class="navbar navbar-dark navbar-expand-md">
                <a class="navbar-brand" href="index.php">
                    <i class="icon-fuel"></i> Stacja 4449
                </a>
                <button class="navbar-toggler" type="button" 
                data-toggle="collapse" data-target="#mainmenu" 
                aria-controls="mainmenu" aria-expanded="false" 
                aria-label="Pzełącznik nawigacji">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainmenu">
                    <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Strona Główna</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">
                                Terminy
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="adding_date.php">Dodaj Termin</a>
                                <a class="dropdown-item" href="edit_date.php">Edytuj Termin</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="generate_report.php">Generuj raport</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Produkty</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="adding_product.php">Dodaj Produkt</a>
                                <a class="dropdown-item" href="edit_product.php">Edytuj Produkt</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown" role="button">Wiadomości</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item active" href="adding_message.php">Dodaj Wiadomość</a>
                                <a class="dropdown-item" href="edit_message.php">Edytuj Wiadomość</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Profil</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="user_profile.php">Mój profil</a>
                                <a class="dropdown-item" href="change_password.php">Zmień hasło</a>
                                <a class="dropdown-item" href="#">###</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Grafik</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="work_schedule.php">Grafik</a>
                                <a class="dropdown-item" href="preferences.php">Preferencje</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="log_out.php">Wyloguj</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <article>
                <div class="container-fluid">
                    <header>
                        Dodawanie nowej wiadomości
                    </header>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                                echo $string_form_message_adding;
                                if (isset($_SESSION['message_sent']))
                                {
                                    echo '<div class="col-sm-12">';
                                    echo $_SESSION['message_sent'];
                                    echo '<div>';
                                    unset($_SESSION['message_sent']);
                                }
                                if (isset($_SESSION['message_error']))
                                {
                                    echo '<div class="col-sm-12">';
                                    echo $_SESSION['message_error'];
                                    echo '<div>';
                                    unset($_SESSION['message_error']);
                                }
                                if (isset($_SESSION['error']))
                                {
                                    echo '<div class="col-sm-12">';
                                    echo $_SESSION['error'];
                                    echo '<div>';
                                    unset($_SESSION['error']);
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </article>
        </main>
        <footer>
            Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com
        </footer>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
                integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" 
                crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>