<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    $string_form_product_search = '
        <div class="adding_date_form">
            <form method="POST" action="adding_date.php">
                <ul>
                <li><input type="text" name="product_name" placeholder="Wpisz nazwę produktu" /></li>
                <li><input type="submit" value="Wyszukaj" /></li>
                </ul>
            </form>
        </div>';
    if (isset($_POST['product_name']))
    {
        $product_name = filter_input(INPUT_POST, 'product_name');
        require_once 'database_connection.php';
        $product_query = $db->prepare('SELECT products.id, products.name 
        FROM products 
        WHERE products.name LIKE "%'.$product_name.'%"');
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Dodawanie terminu</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Damian Zamroczynski" />

    <link rel="stylesheet" href="main.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                <li><a href="adding_date.php" class="active">Dodaj terminy</a></li>
                <li><a href="#">Edytuj terminy</a></li>
                <li><a href="#">Dodaj produkty</a></li>
                <li><a href="#">Edytuj produkty</a></li>
                <li><a href="#">Dodaj wiadomości</a></li>
                <li><a href="#">Edytuj wiadomości</a></li>
                <li><a href="#">Profil</a></li>
                <li class="last"><a href="log_out.php">Wyloguj się</a></li>
            </ul>
        </div>
        
        <?php
        if (isset($_POST['product_name']))
        {
            $product_query->execute();
            $products = $product_query->fetchAll();
            if ($products)
            {
                echo '<ol class="result">';
                foreach($products as $row)
                {
                    echo '<form method="post" action="adding_date.php"><li>';
                    echo '<input type="radio" name="'.$row['id'].'" />';
                    echo $row['name'];
                    echo '</li>';
                }
                echo '<input type="submit" /></form>';
                echo "</ol>";
            }
            else
            {
                echo '<h2>Brak Wyniku!</h2>';
                echo $string_form_product_search;
            }
            echo '</ol>';
        }
        else
        {
        echo '<h2>Dodawanie Terminu</h2>';
        echo $string_form_product_search;
        }
        
        ?>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
    <?php unset($_POST['product_name']); ?>
</body>
</html>
