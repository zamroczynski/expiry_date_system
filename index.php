<?php
    session_start();
    require_once 'database_connection.php';
    $today = new DateTime();
    $date_string = $today->format('Y-m-d');
    if(isset($_POST['date_to_search']))
    {
        $date_string = filter_input(INPUT_POST, 'date_to_search');
        $date_query = 'SELECT expiry_date.id, expiry_date.date, products.name FROM expiry_date INNER JOIN products ON 
        products.id=expiry_date.id_product WHERE expiry_date.date="'.$date_string.'" ORDER BY expiry_date.id';
    }
    else
    {
        $date_query = 'SELECT expiry_date.id, expiry_date.date, products.name FROM expiry_date INNER JOIN products ON
        products.id=expiry_date.id_product WHERE expiry_date.date="'.$date_string.'" ORDER BY expiry_date.id';
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
                    <header>Produkty z datą przydatności do <?= $date_string ?></header>
                    <div class="row">
                            <?php
                                $result = $db->query($date_query);
                                if ($result->rowCount() > 0)
                                {
                                    foreach($result as $row) {
                                        echo '<div class="col-sm-12 product">';
                                        print_r($row['name']);
                                        echo "</div>";
                                    }
                                }
                                else
                                {
                                    echo '<div class="col-sm-12">';
                                    echo "Brak produktów, które się terminują!";
                                    echo '</div>';
                                }
                            ?>
                    </div>
                </div>
                <div class="small_search">
                    <form method="post">
                        Podaj datę: <input type="date" name="date_to_search" value="<?= $date_string ?>" />
                        <input type="submit" value="Pokaż" />
                    </form>
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