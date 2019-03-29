<?php
    session_start();
    require_once 'database_connection.php';
    $today = new DateTime();
    $today_string = $today->format('Y-m-d');
    $today_messages = 'SELECT messages.contents, messages.date_start, messages.date_end, users.name, messages.active, messages.rank 
    FROM messages, users WHERE 
    messages.id_user=users.id AND messages.date_end >= "'.$today_string.'" 
    ORDER BY messages.rank DESC, messages.date_end ASC';
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Wiadomości</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Damian Zamroczynski" />

    <link rel="stylesheet" href="main.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

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
                <li><a href="index.php">Terminy</a></li>
                <li><a href="messages.php" class="active">Wiadomości</a></li>
                <li class="last"><a href="log_in.php">Logowanie</a></li>
            </ul>
        </div>
        <div class="result">
            <ol class="messages">
                <?php
                    echo "<h2>Wiadomości:</h2>";
                    $result = $db->query($today_messages);
                    if ($result->rowCount() > 0)
                    {
                        foreach($result as $row) {
                            if ($row['active'] > 0)
                            {
                                echo "<li>";
                                echo '<div class="messages_bar">';
                                echo "<ul>";
                                echo "<li>";
                                echo "Napisał: ";
                                print_r($row['name']);
                                echo "</li>";
                                echo "<li>";
                                echo "Obowiązuje od: ";
                                print_r($row['date_start']);
                                echo " do ";
                                print_r($row['date_end']);
                                echo "</li>";
                                echo "<li>";
                                if ($row['rank']>=3) echo '<div style="color:red;text-transform: uppercase;">Bardzo ważna wiadomość!</div>';
                                if ($row['rank']==2) echo '<div style="color:#ff6666;">Ważna wiadomość!</div>';
                                echo "</li>";
                                echo "</ul></div>";
                                print_r($row['contents']);
                                echo "</li>";
                            }
                            
                        }
                    }
                    else
                    {
                        echo "Brak wiadomości!";
                    }
                ?>
            </ol>
        </div>
        <div class="main_page_form">
            <form method="post" action="search_expiry_products.php" >
                Podaj datę: <input type="date" name="date_to_search" value="<?= $today_string ?>" />
                <input type="submit" value="Pokaż" />
            </form>
        </div>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
