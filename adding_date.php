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
    
    if(isset($_POST['download']))
    {
        $query = 'SELECT * FROM products';
        $json_result = $db->query($query);
        $json = $json_result->fetchAll(PDO::FETCH_ASSOC);
        $fp = fopen('towary.json', 'w');
        fwrite($fp, json_encode($json));
        fclose($fp);
    }

    if(isset($_POST['choose']))
    {
        $sql = 'SELECT * FROM expiry_date WHERE date="'.$_POST['date'].'" AND id_product='.$_POST['choose'].';';
        $result = $db->query($sql);
        if($result->rowCount() > 0)
        {
            $result->rowCount();
            $_SESSION['error'] = '<div class="error">Istnieje już taki termin!</div>';
        }
        else
        {
            $sql = 'INSERT INTO expiry_date VALUES (null, '.$_POST['choose'].', "'.$_POST['date'].'", null);';
            $db->query($sql);
            $_SESSION['succes'] = '<div class="ok">Pomyślnie dodano nowy termin!</div>';
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

        <script src="js/jquery-3.4.1.min.js"></script>
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
                            <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown" role="button">
                                Terminy
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="index.php">Dzisiejsze Terminy</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item active" href="adding_date.php">Dodaj Termin</a>
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
                    <header>
                        Dodawanie Terminu
                    </header>
                    <div class="row">
                        <div class="col-sm-12 center">
                            <?php
                            if(isset($_SESSION['error']))
                            {
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                            }
                            if(isset($_SESSION['succes']))
                            {
                                echo $_SESSION['succes'];
                                unset($_SESSION['succes']);
                            }
                            ?>
                            <form method="POST">
                                <input type="text" name="search" id="search" placeholder="Szukaj towaru" />
                                <input type="submit" name="download" value="Pobierz produkty" />
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 center">
                            <form method="POST">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nazwa</th>
                                            <th scope="col">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result"></tbody>
                                </table>
                                <input type="date" value="<?= $today_string ?>" name="date" />
                                <input type="submit" value="Wyślij" />
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        </main>
        <footer>
            Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com
        </footer>
        
        <script>
            $(document).ready(function(){
                $.ajaxSetup({ cache: false});
                $('#search').keyup(function(){
                    $('#result').html('');
                    $('#state').val('');
                    var searchField = $('#search').val();
                    var expression = new RegExp(searchField, "i");
                    $.getJSON('towary.json', function(data){
                        $.each(data, function(key, value){
                            if (value.name.search(expression) != -1)
                            {
                                var input = document.createElement("input");
                                input.type = "radio";
                                input.value = value.id;
                                input.name = "choose";
                                input.class = "dym";
                                var $tr = $('<tr>').append(
                                    $('<label>').append(
                                    $('<td>').text(value.id),
                                    $('<td>').text(value.name),
                                    $('<td>').append(input))
                                ).appendTo('#result');
                                
                                //console.log($tr.wrap('<p>').html());
                            }
                        });
                    });
                });
            });
        </script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>