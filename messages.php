<?php
    session_start();
    require_once 'database_connection.php';
    $today = new DateTime();
    $today_string = $today->format('Y-m-d');
    $today_messages = 'SELECT messages.contents, messages.date_start, messages.date_end, users.name, messages.active, messages.rank 
    FROM messages INNER JOIN users ON messages.id_user=users.id 
    WHERE messages.date_end >= "'.$today_string.'" 
    ORDER BY messages.rank DESC, messages.date_end ASC';
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
                        <li class="nav-item"><a class="nav-link" href="index.php">Terminy</a></li>
                        <li class="nav-item"><a class="nav-link active" href="messages.php">Wiadomości</a></li>
                        <li class="nav-item"><a class="nav-link" href="log_in.php">Zaloguj</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <article>
                <div class="container-fluid">
                    <header>Wiadomości:</header>
                    <div class="row">
                            <?php
                                $result = $db->query($today_messages);
                                if ($result->rowCount() > 0)
                                {
                                    foreach($result as $row) {
                                        echo '<div class="col-sm-12 message">';
                                        echo '<div class="message_bar">';
                                        echo '<div class="who">Napisał(a): ';
                                        print_r($row['name']);
                                        echo '</div>';
                                        echo '<div class="time">Obowiązuje od ';
                                        print_r($row['date_start']);
                                        echo ' do ';
                                        print_r($row['date_end']);
                                        echo '</div>';
                                        echo '<div class="rank">';
                                        if ($row['rank']>=3) echo '<div style="color:red;text-transform: uppercase;">Bardzo ważna wiadomość!</div>';
                                        if ($row['rank']==2) echo '<div style="color:#ff6666;">Ważna wiadomość!</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        print_r($row['contents']);
                                        echo '</div>';
                                    }
                                }
                                else
                                {
                                    echo '<div class="col-sm-12">';
                                    echo "Brak wiadomości!";
                                    echo '</div>';
                                }
                            ?>
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