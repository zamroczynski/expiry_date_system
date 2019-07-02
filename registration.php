<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    if($_SESSION['user_power']<5)
    {
        $_SESSION['acces_denied'] = '<div class="error">Brak dostępu!</div>';
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
            $_SESSION['login_error'] = '<div class="error">Istnieje już taki login!</div>';
        }
        else
        {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $name = filter_input(INPUT_POST, 'name');
            $email = filter_input(INPUT_POST, 'email');
            $new_user = $db->prepare('INSERT INTO users VALUES (null, :login, "'.$password.'", :email, :name, 1, null)');
            $new_user->bindValue(':login', $login, PDO::PARAM_STR);
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
                        <?php
                            if (isset($_SESSION['acces_denied']))
                            {
                                echo $_SESSION['acces_denied'];
                                unset($_SESSION['acces_denied']);
                            }
                        ?>
                    <header class="hello">
                        Dodawanie nowego pracownika
                    </header>
                    <div class="row">
                        <div class="col-sm-12">
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
                        <form method="POST">
                            <div><input type="text" placeholder="login" name="login" required></div>
                            <div><input type="text" placeholder="hasło" name="password" required></div>
                            <div><input type="text" placeholder="imię i nazwisko" name="name" required></div>
                            <div><input type="email" placeholder="email" name="email" disabled></div>
                            <div><input type="tel" placeholder="numer tel" name="telphone" disabled></div>
                            <div><input type="submit" value="Załóż nowe konto!"></div>
                        </form>
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