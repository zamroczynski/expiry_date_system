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
    $images = 'SELECT images.file_name, messages.id FROM images INNER JOIN messages ON images.id_message=messages.id';
    if($_SESSION['user_power']>6)
    {
        $all_messages = $db->query('SELECT messages.contents, messages.date_start, messages.date_end, messages.rank, 
                                    users.name, messages.id 
                                    FROM messages INNER JOIN users ON users.id=messages.id_user 
                                    ORDER BY messages.date_end');
    }
    if($_SESSION['user_power']<=6 && $_SESSION['user_power']>=2)
    {
        $user_messages = $db->query('SELECT messages.contents, messages.date_start, messages.date_end, messages.rank, 
                                    users.name, messages.id 
                                    FROM messages INNER JOIN users ON users.id=messages.id_user 
                                    WHERE users.id='.$_SESSION['user_id'].' 
                                    ORDER BY messages.date_end');
    }
    $images = $db->query('SELECT images.file_name, messages.id FROM images INNER JOIN messages ON images.id_message=messages.id');
    if (isset($_POST['delete_message']))
    {
        $message = filter_input(INPUT_POST, 'message_id');
        if($_SESSION['image'])
        {
            $db->query('DELETE FROM images WHERE id_message="'.$message.'"');
            unset($_SESSION['image']);
        }
        $db->query('DELETE FROM messages WHERE messages.id="'.$message.'"');
        header('Location: edit_message.php');
    }
    if (isset($_POST['edit_message']))
    {
        $message = filter_input(INPUT_POST, 'message_id');
        $edit_message = $db->query('SELECT messages.contents, messages.date_start, messages.date_end 
                                    FROM messages WHERE messages.id="'.$message.'"');
        $row = $edit_message->fetch();
        $string_form_message_adding = '
        <div>
            <form method="POST">
                <div>Obowiązuje od <input type="date" name="first_date" value="'.$row['date_start'].'">
                 do <input type="date" name="last_date" value="'.$row['date_end'].'"></div>
                <div><textarea name="new_message" class="form-control" >'.$row['contents'].'</textarea></div>
                <div>Jak ważna to wiadomość?
                <select name="rank">
                <option value="1">Zwykła wiadomość</option>
                <option value="2">Ważna wiadmość!</option>
                <option value="3">BARDZO WAŻNA WIADOMOŚĆ!</option>
                </select></div>
                <input type="hidden" name="id_message" value="'.$message.'">
                <input type="submit" value="Zmień">
            </form>
        </div>';
        $_SESSION['edit_string'] = true;
        unset($_SESSION['image']);
    }
    if (isset($_POST['id_message']))
    {
        $rank = filter_input(INPUT_POST,'rank');
        $id_message = filter_input(INPUT_POST, 'id_message');
        $message = filter_input(INPUT_POST, 'new_message');
        $new_message = nl2br($message);
        $first_date = filter_input(INPUT_POST, 'first_date');
        $last_date = filter_input(INPUT_POST, 'last_date');
        if($_SESSION['user_power']>=6)
        {
            $message_query=$db->prepare('UPDATE messages 
            SET contents=:message, date_start="'.$first_date.'", date_end="'.$last_date.'", rank="'.$rank.'" 
            WHERE id='.$id_message);
            $message_query->bindValue(':message', $new_message, PDO::PARAM_STR);
            $message_query->execute();
            unset($_SESSION['image']);
            header('Location: edit_message.php');
        }
        else if($_SESSION['user_power']<=2 && $rank<=1)
        {
            $message_query=$db->prepare('UPDATE messages 
            SET contents=:message, date_start="'.$first_date.'", date_end="'.$last_date.'", rank="'.$rank.'" 
            WHERE id='.$id_message);
            $message_query->bindValue(':message', $new_message, PDO::PARAM_STR);
            $message_query->execute();
            unset($_SESSION['image']);
            header('Location: edit_message.php');
        }
        else if ($_SESSION['user_power']<6 && $_SESSION['user_power']>2)
        {
            $message_query=$db->prepare('UPDATE messages 
            SET contents=:message, date_start="'.$first_date.'", date_end="'.$last_date.'", rank="'.$rank.'" 
            WHERE id='.$id_message);
            $message_query->bindValue(':message', $new_message, PDO::PARAM_STR);
            $message_query->execute();
            unset($_SESSION['image']);
            header('Location: edit_message.php');
        }
        else
        {
            unset($_SESSION['image']);
            $_SESSION['message_error'] = '<div class="error">Brak uprawnień!</div>';
        }
    }
    unset($_SESSION['image']);
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css">
        <link rel="stylesheet" href="css/main.css" />
        
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
                integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" 
                crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>
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
                                <a class="dropdown-item" href="index.php">Dzisiejsze Terminy</a>
                                <div class="dropdown-divider"></div>
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
                                <a class="dropdown-item" href="adding_message.php">Dodaj Wiadomość</a>
                                <a class="dropdown-item active" href="edit_message.php">Edytuj Wiadomość</a>
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
                        <li class="nav-item"><a class="nav-link" href="log_out.php">Wyloguj</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <article>
                <div class="container-fluid">
                    <?php
                        if (isset($_SESSION['message_error']))
                        {
                            echo $_SESSION['message_error'];
                            unset($_SESSION['message_error']);
                        }
                        if(isset($_SESSION['edit_string']))
                        {
                            echo $string_form_message_adding;
                            unset($_SESSION['edit_string']);
                        }
                    ?>
                    <header>
                        <?php
                        if($_SESSION['user_power']>6)
                        {
                            echo 'Wszystkie wiadomości:';
                        }
                        else
                        {
                            echo 'Wszystkie Twoje wiadomości:';
                        }
                        ?>
                    </header>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                                if ($_SESSION['user_power']>6)
                                {
                                    if ($all_messages->rowCount()>0)
                                    {
                                        foreach ($all_messages as $row)
                                        {
                                            echo '<form method="POST">';
                                            echo '<div class="col-sm-12 message">';
                                                echo '<div class="message_bar">';
                                                    echo '<input type="hidden" name="message_id" value="'.$row['id'].'">';
                                                    echo '<div class="who">Napisał(a): ';
                                                        print_r($row['name']);
                                                    echo '</div>';
                                                    echo '<div class="time">Obowiązuje od ';
                                                        print_r($row['date_start']);
                                                        echo " do ";
                                                        print_r($row['date_end']);
                                                    echo '</div>';
                                                    if ($row['rank']>=3) echo '<div style="color:red;text-transform: uppercase;">Bardzo ważna wiadomość!</div>';
                                                    if ($row['rank']==2) echo '<div style="color:#ff6666;">Ważna wiadomość!</div>';
                                                echo '</div>';
                                                print_r($row['contents']);
                                                echo '<div class="tz-gallery">';
                                                echo '<div class="row mb-3">';
                                                $images = $db->query('SELECT images.file_name, messages.id FROM images INNER JOIN messages ON images.id_message=messages.id');
                                                foreach($images as $img)
                                                {
                                                    if($row['id']==$img['id'])
                                                    {
                                                        echo '<div class="col-md-4">';
                                                        echo '<div class="card">';
                                                        echo '<a class="lightbox" href="img/uploads/'.$img['file_name'].'">';
                                                        echo '<img src="img/uploads/'.$img['file_name'].'" class="card-img-top" />';
                                                        echo '</a>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        $_SESSION['image'] = true;
                                                    }
                                                }
                                                echo '</div>';
                                                echo '</div>';
                                            echo '</div>';
                                            echo '<div><input type="submit" name="delete_message" value="USUŃ" />';
                                            echo '<input type="submit" name="edit_message" value="EDYTUJ" /></div>';
                                            echo "</form>";
                                        }
                                    }
                                    else
                                    {
                                        echo '<h2>Brak wiadomości!</h2>';
                                    }
                                }
                                else
                                {
                                    if ($user_messages->rowCount()>0)
                                    {
                                        
                                        foreach ($user_messages as $row)
                                        {
                                            echo '<form method="POST">';
                                            echo '<div class="col-sm-12 message">';
                                            echo '<div class="message_bar">';
                                            echo '<input type="hidden" name="message_id" value="'.$row['id'].'">';
                                            echo '<div class="who">Napisał(a): ';
                                            print_r($row['name']);
                                            echo '</div>';
                                            echo '<div class="time">Obowiązuje od ';
                                            print_r($row['date_start']);
                                            echo " do ";
                                            print_r($row['date_end']);
                                            echo '</div>';
                                            if ($row['rank']>=3) echo '<div style="color:red;text-transform: uppercase;">Bardzo ważna wiadomość!</div>';
                                            if ($row['rank']==2) echo '<div style="color:#ff6666;">Ważna wiadomość!</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            print_r($row['contents']);
                                            echo '<div><input type="submit" name="delete_message" value="USUŃ" />';
                                            echo '<input type="submit" name="edit_message" value="EDYTUJ" /></div>';
                                            echo "</form>";
                                        }
                                    }
                                    else
                                    {
                                        echo '<h2>Brak wiadomości!</h2>';
                                    }
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
        
        <script>
            baguetteBox.run('.tz-gallery');
        </script>
    </body>
</html>