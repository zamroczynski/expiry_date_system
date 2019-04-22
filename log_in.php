<?php
    session_start();
    require_once 'database_connection.php';
    if (isset($_SESSION['logged']))
    {
        header('Location: user_profile.php');
        exit();
    }
    if ((isset($_POST['login'])) || (isset($_POST['password'])))
    {
        $login = filter_input(INPUT_POST, 'login');
        $password = $_POST['password'];
        require_once 'database_connection.php';
        $user_query = $db->prepare('SELECT users.id, users.login, users.password, users.email, users.name, users.power, users.last_login 
        FROM users 
        WHERE users.login=:login');
        $user_query->bindValue(':login', $login, PDO::PARAM_STR);
        $user_query->execute();
        $user = $user_query->fetch();
        if(password_verify($password, $user['password']))
        {
            if ($user)
            {
                $_SESSION['logged'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_pass'] = $user['password'];
                $_SESSION['user_power'] = $user['power'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_last_login'] = $user['last_login'];
                $today = new DateTime();
                $today_string = $today->format('Y-m-d H:i:s');
                $db->query('UPDATE users SET last_login="'.$today_string.'"');
                header('Location: user_profile.php');
                exit();
            }
        }
        else
        {
            $_SESSION['error'] = '<div class="error">Nie prawidłowy login lub hasło</div>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
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
                        <li class="nav-item"><a class="nav-link active" href="index.php">Terminy</a></li>
                        <li class="nav-item"><a class="nav-link" href="messages.php">Wiadomości</a></li>
                        <li class="nav-item"><a class="nav-link" href="log_in.php">Zaloguj</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <article>
                <div class="container-fluid">
                    <header>Logowanie</header>
                    <div class="row">
                        <div class="col-sm-12 login">
                            <?php
                                if(isset($_SESSION['error']))
                                {
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                }
                            ?>
                            <form method="POST">
                                <div><input type="text" name="login" placeholder="Twój login..." required></div>
                                <div><input type="password" name="password" placeholder="Twoje hasło..." required></div>
                                <input type="submit" value="Zaloguj się">
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