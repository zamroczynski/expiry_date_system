<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    require_once 'database_connection.php';
    $today = new DateTime();
    $today_string = $today->format('Y-m-d');

    if(isset($_POST['date']))
    {
        $employee_date = filter_input(INPUT_POST, 'date');
        $employee_reason = filter_input(INPUT_POST, 'reason');
        $check_query = $db->prepare('SELECT * FROM preferences WHERE date="'.$employee_date.'" AND id_user='.$_SESSION['user_id'].'');
        $check_query->execute();
        if($check_query->rowCount()>0)
        {
            $_SESSION['error'] = '<div class="error">Masz już ustawioną prośbę na ten dzień</div>';
        }
        else
        {
            $insert_query = $db->prepare('INSERT INTO preferences VALUES (null, '.$_SESSION['user_id'].', "'.$employee_date.'", "'.$employee_reason.'")');
            $insert_query->execute();
            $_SESSION['output_message'] = '<div class="ok">Pomyślnie dodano nową prośbę!</div>';
        }
    }

    if(isset($_POST['id']))
    {
        $delete_query = $db->query('DELETE FROM preferences WHERE id='.$_POST['id']);
        $_SESSION['output_message'] = '<div class="ok">Pomyślnie usunięto prośbę!</div>';
    }

    $my_date = $db->query('SELECT id, date, reason FROM preferences WHERE id_user='.$_SESSION['user_id'].' ORDER BY date');
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
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Terminy</a>
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
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Podręcznik stacji</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="manual.php">Istniejące instrukcje</a>
                                <a class="dropdown-item" href="edit_manual.php">Dodaj/usuń instrukcje</a>
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
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Profil</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="user_profile.php">Mój profil</a>
                                <a class="dropdown-item" href="change_password.php">Zmień hasło</a>
                                <a class="dropdown-item" href="#">###</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown" role="button">Grafik</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="work_schedule.php">Grafik</a>
                                <a class="dropdown-item active" href="preferences.php">Preferencje</a>
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
                            if (isset($_SESSION['error']))
                            {
                                echo '<div class="col-sm-12">';
                                echo $_SESSION['error'];
                                echo '</div>';
                                unset($_SESSION['error']);
                            }
                            if (isset($_SESSION['output_message']))
                            {
                                echo '<div class="col-sm-12">';
                                echo $_SESSION['output_message'];
                                echo '</div>';
                                unset($_SESSION['output_message']);
                            }
                        ?>
                        <div style="margin-top: 20px;"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-secondary" href="preferences.php" role="button">Powrót</a>
                                <header class="hello">
                                    Wprowadź dni, w które możesz przyjść do pracy
                                </header>
                        </div>
                    </div>
                </div>
                <section>
                    <div class="container-fluid">
                        <header class="hello">
                            <h1>Moje dyspozycje:</h1>
                        </header>
                        <div class="row">
                            <?php
                                if($my_date->rowCount()>0)
                                {
                                    echo '<table class="table">';
                                    echo '<thead>';
                                    echo '<tr>';
                                    echo '<th scope="col">Data</th>';
                                    echo '<th scope="col">Powód</th>';
                                    echo '<th scope="col">#</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';
                                    foreach($my_date as $row)
                                    {
                                        
                                        echo '<tr><td>';
                                        print_r($row['date']);
                                        echo '</td>';
                                        echo '<td>';
                                        print_r($row['reason']);
                                        echo '</td>';
                                        echo '<td>';
                                        echo '<form method="POST"><input type="hidden" name="id" value="';
                                        print_r($row['id']);
                                        echo '"/> <input type="submit" value="Usuń" /></form>';
                                    }
                                    echo '</tbody>';
                                    echo '</table>';
                                }
                                else
                                {
                                    echo '<div class="col-sm-12 hello">Brak</div>';
                                }
                            ?>
                        </div>
                    </div>
                </section>
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