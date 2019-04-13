<?php
    session_start();
    require_once 'database_connection.php';
    $date_to_search = $_POST['date_to_search'];
    $my_query = 'SELECT expiry_date.id, expiry_date.date, products.name FROM expiry_date INNER JOIN products ON 
    products.id=expiry_date.id_product WHERE expiry_date.date="'.$date_to_search.'" ORDER BY expiry_date.id';
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Terminy</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Damian Zamroczynski" />

    <link rel="stylesheet" href="css/old.css" type="text/css" />
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
                <li><a href="messages.php">Wiadomości</a></li>
                <li class="last"><a href="log_in.php">Logowanie</a></li>
            </ul>
        </div>
        <div class="result">
            <ol>
                <?php
                    echo "<h2>Produkty z datą przydatności do $date_to_search </h2>";
                    $result = $db->query($my_query);
                    if ($result->rowCount() > 0)
                    {
                        foreach($result as $row) {
                            echo "<li>";
                            print_r($row['name']);
                            echo "</li>";
                        }
                    }
                    else
                    {
                        echo "Brak produktów, które się terminują!";
                    }
                ?>
            </ol>
        </div>
        <div class="main_page_form">
            <form method="post">
                Podaj datę: <input type="date" name="date_to_search" value="<?= $date_to_search ?>" />
                <input type="submit" value="Pokaż" />
            </form>
        </div>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
