<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    if($_SESSION['user_power']<7)
    {
        $_SESSION['acces_denied'] = '<div class="error">Brak dostępu!</div>';
        header('Location: user_profile.php');
        exit();
    }
    require_once 'database_connection.php';
    $select_all_users = $db->query('SELECT * FROM users');
    $all_users = $select_all_users->fetchAll();
    if(isset($_POST['user']))
    {
        $user_query = $db->query('SELECT * FROM users WHERE id='.$_POST['user']);
        $user = $user_query->fetch();
        $choose = $_POST['choose'];
    }
    if (isset($_POST['NEWlogin']))
    {
        $new_login = filter_input(INPUT_POST, 'NEWlogin');
        $check_login = $db->prepare('SELECT login from users WHERE login=:login');
        $check_login->bindValue(':login', $new_login, PDO::PARAM_STR);
        $check_login->execute();
        if ($check_login->rowCount()>0)
        {
            $_SESSION['login_error'] = '<div class="error">Istnieje już taki login w bazie, wybierz inny!</div>';
        }
        else
        {
            $pass = $_POST['NEWpassword'];
            $new_password = password_hash($pass, PASSWORD_DEFAULT);
            $id = filter_input(INPUT_POST, 'id');
            $update_user = $db->prepare('UPDATE users SET login=:newLogin, password="'.$new_password.'" WHERE id='.$id);
            $update_user->bindValue(':newLogin', $new_login, PDO::PARAM_STR);
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
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Wiadomości</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="adding_message.php">Dodaj Wiadomość</a>
                                <a class="dropdown-item" href="edit_message.php">Edytuj Wiadomość</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown" role="button">Profil</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item active" href="user_profile.php">Mój profil</a>
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
                    <header class="hello">
                        Edycja danych pracownika
                    </header>
                    <div class="row">
                        <div class="col-sm-12">
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
                            Co chcesz zmienić?
                                <div><select name="choose">
                                    <option value="login">Login i hasło</option>
                                    <option value="name">Imię i nazwikso</option>
                                    <option value="power">Prawa</option>
                                </select></div>
                                Komu?
                                <div><select name="user">
                                    <?php
                                    foreach($all_users as $row)
                                    {
                                        echo '<option value=';
                                        echo $row['id'];
                                        echo '>';
                                        echo $row['name'];
                                        echo '</option>';
                                    }
                                    ?>
                                </select></div>
                                <input type="submit" value="Pokaż">
                        </form>
                        </div>
                        <div class="col-sm-12">
                        <?php
                            if(isset($user))
                            {
                                if($choose == "login")
                                {
                                    echo 'Edycja loginu i hasła:
                                    <form method="POST">
                                    <div><input type="text" placeholder="LOGIN" name="NEWlogin" value="'.$user['login'].'" required></div>
                                    <div><input type="text" placeholder="HASŁO" name="NEWpassword" required></div>
                                    <div><input type="submit" value="Edytuj"></div>
                                    <input type="hidden" name="id" value="'.$user['id'].'"></form>
                                    ';
                                }
                                if($choose == "name")
                                {
                                    echo 'Edycja imienia i nazwiska:
                                    <div><form method="POST"></div>
                                    <div><input type="text" placeholder="IMIĘ I NAZWISKO" name="NEWname" value="'.$user['name'].'" required></div>
                                    <div><input type="submit" value="Edytuj"></div>
                                    <input type="hidden" name="id" value="'.$user['id'].'"></form>
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