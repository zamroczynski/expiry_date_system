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
    $today = new DateTime();
    $today_string = $today->format('Y-m-d');
    $string_form_product_search = '
        <div class="adding_date_form">
            <form method="POST" action="adding_date.php">
                <ul class="adding">
                <li><input type="text" name="product_name" placeholder="Wpisz nazwę produktu" /></li>
                <li><input type="submit" value="Wyszukaj" /></li>
                </ul>
            </form>
        </div>';
    if (isset($_POST['product_name']))
    {
        $product_name = filter_input(INPUT_POST, 'product_name');
        $product_query = $db->prepare('SELECT products.id, products.name 
        FROM products 
        WHERE products.name LIKE "%'.$product_name.'%"');
    }
    if (isset($_POST['expiry_date']))
    {
        $expiry_date = filter_input(INPUT_POST, 'expiry_date');
        $radio_choose = $_POST['radio_name'];
        $expiry_test_query = $db->prepare('SELECT products.id, expiry_date.id_product, expiry_date.date 
        FROM expiry_date INNER JOIN products ON products.id=expiry_date.id_product 
        WHERE expiry_date.date="'.$expiry_date.'" AND products.id="'.$radio_choose.'"');
        $expiry_test_query->execute();
        if ($expiry_test_query->rowCount()>0)
        {
            $_SESSION['error'] = '<div class="error_div">Istnieje już taki termin!</div>';
        }
        else
        {
            
            $expiry_date_insert_query = $db->query('INSERT INTO expiry_date VALUES (null, '.$radio_choose.', "'.$expiry_date.'", null)');
            $_SESSION['output_message'] = '<div class="ok">Pomyślnie dodano nowy termin</div>';
        }
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
            <li>
                    <a href="#" class="active">Terminy</a>
                    <ul>
                        <li><a href="adding_date.php">Dodaj terminy</a></li>
                        <li><a href="edit_date.php">Edytuj terminy</a></li>
                        <li><a href="generate_report.php">Generuj Raport</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="#">Produkty</a>
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
            $product_query->execute();
            $products = $product_query->fetchAll();
            if ($products)
            {
                
                echo '<form method="post" action="adding_date.php">';
                echo '<div class="result_grid">';
                foreach($products as $row)
                {
                    echo '<label class="my_radio_inputs">';
                    echo '<input type="radio" name="radio_name" value="'.$row['id'].'" />';
                    echo $row['name'];
                    echo '<span class="checkmark"></span></label>';
                }
                echo '';
                echo '<div style="margin: 0 10px;"></div>';
                echo '</div><input type="date" name="expiry_date" value="'.$today_string.'" /><input type="submit" value="Zapisz termin" /></form>';
                echo "";
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
        if (isset($_SESSION['error']))
        {
            echo '<div class="error_div">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['output_message']))
        {
            echo '<div class="ok">'.$_SESSION['output_message'].'</div>';
            unset($_SESSION['output_message']);
        }
        ?>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
    <?php unset($_POST['product_name']); unset($_POST['expiry_date']); ?>
</body>
</html>
