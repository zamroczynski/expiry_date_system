<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    if($_SESSION['user_power']<2)
    {
        $_SESSION['acces_denied'] = '<div class="error_div">Brak dostępu!</div>';
        header('Location: user_profile.php');
        exit();
    }
    require_once 'database_connection.php';
    $string_form_product_adding = '
        <div class="adding_date_form">
            <form method="POST" action="adding_products.php">
                <ul class="adding">
                <li><input type="text" name="product_name" placeholder="Wpisz nazwę produktu" /></li>
                <li><input type="submit" value="Dodaj" /></li>
                </ul>
            </form>
        </div>';
    if (isset($_POST['product_name']))
    {
        $product_name = filter_input(INPUT_POST, 'product_name');
        if (!strlen($product_name) || strlen($product_name)>60)
        {
            $_SESSION['product_error'] = '<div class="error_div">Błędna nazwa!</div>';
        }
        else
        {
            $product_check_query = $db->prepare('SELECT products.name 
            FROM products WHERE products.name="'.$product_name.'"');
            $product_check_query->execute();
            if ($product_check_query->rowCount()>0)
            {
                $_SESSION['product_error'] = '<div class="error_div">Istnieje już produkt o podanej nazwie!</div>';
            }
            else
            {
                $product_add_query = $db->prepare('INSERT INTO products (id, name, ean_code) VALUES (null, \''.$product_name.'\', null)');
                $product_add_query->execute();
                $_SESSION['product'] = '<div class="ok">Pomyślnie dodano produkt</div>';
            }
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Dodawanie produktu</title>
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
                <li>
                    <a href="#">Terminy</a>
                    <ul>
                        <li><a href="adding_date.php">Dodaj terminy</a></li>
                        <li><a href="edit_date.php">Edytuj terminy</a></li>
                        <li><a href="generate_report.php">Generuj Raport</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="active">Produkty</a>
                    <ul>
                        <li><a href="adding_products.php">Dodaj produkty</a></li>
                        <li><a href="edit_products.php">Edytuj produkty</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Wiadomości</a>
                    <ul>
                        <li><a href="adding_messages.php">Dodaj wiadomości</a></li>
                        <li><a href="edit_messages.php">Edytuj wiadomości</a></li>
                    </ul>
                </li>
                <li><a href="user_profile.php">Profil</a></li>
                <li class="last"><a href="log_out.php">Wyloguj się</a></li>
            </ul>
        </div>
        <?php
        if (isset($_POST['product_name']))
        {
            if (isset($_SESSION['product_error']))
            {
                echo $_SESSION['product_error'];
                unset($_SESSION['product_error']);
            }
            if (isset($_SESSION['product']))
            {
                echo $_SESSION['product'];
                unset($_SESSION['product']);
            }
        }
        echo '<h2>Dodawanie nowego produktu</h2>';
        echo $string_form_product_adding;
        ?>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
