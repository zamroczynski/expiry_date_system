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
    $string_form_product_edit = '
        <div class="adding_date_form">
            <form method="POST">
                <ul class="adding">
                <li><input type="text" name="product_name" placeholder="Wpisz nazwę produktu" /></li>
                <li><input type="submit" value="Szukaj" /></li>
                </ul>
            </form>
        </div>';
    if (isset($_POST['product_name']))
    {
        $product_name = filter_input(INPUT_POST, 'product_name');
        $product_query = $db->prepare('SELECT products.id, products.name 
        FROM products WHERE products.name LIKE "%'.$product_name.'%"');
    }
    if (isset($_POST['product_to_delete']))
    {
        $product_to_delete = $_POST['radio_name'];
        $check_query_product = $db->query('SELECT expiry_date.id, expiry_date.date, products.name 
        FROM expiry_date INNER JOIN products ON products.id=expiry_date.id_product 
        WHERE expiry_date.id_product="'.$product_to_delete.'"'); 
        if ($check_query_product->rowCount()>0)
        {
            $_SESSION['product_error'] = '<div class="error_div">Dla podanego produktu istnieją terminy, usuń najpierw terminy dla tego produktu!</div>';
        }
        else
        {
            $delete_query = $db->query('DELETE FROM products WHERE products.id="'.$product_to_delete.'"');
            $_SESSION['product_deleted'] = '<div class="ok">Produkt został usunięty</div>';
        }
    }
    if (isset($_POST['edit_product_text']) && strlen($_POST['edit_product_text']) > 0)
    {
        $new_product_name = filter_input(INPUT_POST, 'edit_product_text');
        $old_product_name = $_POST['radio_name'];
        $edit_query = $db->query('UPDATE products
        SET name="'.$new_product_name.'" 
        WHERE products.id="'.$old_product_name.'"');
        $_SESSION['product_edited'] = '<div class="ok">Nazwa produktu została zmieniona!</div>';
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Edycja produktów</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Damian Zamroczynski" />

    <link rel="stylesheet" href="css/old.css" type="text/css" />
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
                <li><a href="#">Profil</a></li>
                <li class="last"><a href="log_out.php">Wyloguj się</a></li>
            </ul>
        </div>
        <?php
        if (isset($_SESSION['product_deleted']))
        {
            echo '<h2>'.$_SESSION['product_deleted'].'</h2>';
            unset($_SESSION['product_deleted']);
        }
        if (isset($_SESSION['product_edited']))
        {
            echo '<h2>'.$_SESSION['product_edited'].'</h2>';
            unset($_SESSION['product_edited']);
        }
        if (isset($_POST['product_name']))
        {
            $product_query->execute();
            $products = $product_query->fetchAll();
            if ($products)
                {
                    
                    echo '<form method="post">';
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
                    echo '</div>';
                    echo '<div></div>';
                    echo '<input type="text" name="edit_product_text" placeholder="Wprowadź nową nazwę" /> <input type="submit" value="Edytuj produkt" />';
                    echo '<div class="separator"></div>';
                    echo '<input type="submit" name="product_to_delete" value="USUŃ PRODUKT" onclick="return  confirm(\'Czy napewno usunąć? \')" /></form>';
                }
                else
                {
                    echo '<h2>Brak Wyniku!</h2>';
                    echo $string_form_product_search;
                }
        }
        else
        {
            echo '<h2>Wyszukaj produkt dla którego chcesz zmienić nazwę</h2>';
            echo $string_form_product_edit;
        }
        if (isset($_SESSION['product_deleted']))
        {
            echo '<h2>'.$_SESSION['product_error'].'</h2>';
            unset($_SESSION['product_error']);
        }
        ?>
        <div class="footer">Terminy <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
