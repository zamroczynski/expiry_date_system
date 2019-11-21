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
    $string_form_product_adding = '
        <div>
            <form method="POST">
                <input type="text" name="product_name" placeholder="Wpisz nazwę produktu" />
                <input type="submit" value="Szukaj" />
            </form>
        </div>';
    if (isset($_POST['product_name']))
    {
        $product_name = filter_input(INPUT_POST, 'product_name');
        $product_query = $db->prepare('SELECT products.id, products.name 
        FROM products WHERE products.name LIKE "%'.$product_name.'%" ORDER BY id DESC');
    }
    if (isset($_POST['product_to_delete']))
    {
        $product_to_delete = $_POST['radio_name'];
        $check_query_product = $db->query('SELECT expiry_date.id, expiry_date.date, products.name 
        FROM expiry_date INNER JOIN products ON products.id=expiry_date.id_product 
        WHERE expiry_date.id_product="'.$product_to_delete.'"'); 
        if ($check_query_product->rowCount()>0)
        {
            $_SESSION['product_error'] = '<div class="error">Dla podanego produktu istnieją terminy, usuń najpierw terminy dla tego produktu!</div>';
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
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">
                                Terminy
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="adding_date.php">Dodaj Termin</a>
                                <a class="dropdown-item" href="edit_date.php">Edytuj Termin</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="generate_report.php">Generuj raport</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown" role="button">Produkty</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="adding_product.php">Dodaj Produkt</a>
                                <a class="dropdown-item active" href="edit_product.php">Edytuj Produkt</a>
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
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Grafik</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="work_schedule.php">Grafik</a>
                                <a class="dropdown-item" href="preferences.php">Preferencje</a>
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
                        Edycja produktu
                    </header>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                                if (isset($_POST['product_name']))
                                {
                                    $product_query->execute();
                                    $products = $product_query->fetchAll();
                                    if ($products)
                                    {
                                        
                                        echo '<form method="post">';
                                        echo '<div class="form-group row">';
                                        foreach($products as $row)
                                        {
                                            echo '<label class="col-sm-12 col-md-6 col-lg-4 col-form-label">';
                                            echo '<input type="radio" name="radio_name" value="'.$row['id'].'">';
                                            echo '<div>';
                                            echo $row['name'];
                                            echo '</div></label>';
                                        }
                                        echo '</div><input type="text" name="edit_product_text" placeholder="Wprowadź nową nazwę" /> <input type="submit" value="Edytuj produkt" />';
                                        echo '<input type="submit" name="product_to_delete" value="USUŃ PRODUKT" onclick="return  confirm(\'Czy napewno usunąć? \')" /></form>';
                                    }
                                    else
                                    {
                                        echo '<h2>Brak Wyniku!</h2>';
                                    }
                                }
                                else
                                {
                                    echo $string_form_product_adding;
                                }
                                if (isset($_SESSION['product_deleted']))
                                {
                                    echo '<div class="col-sm-12">';
                                    echo $_SESSION['product_deleted'];
                                    echo '</div>';
                                    unset($_SESSION['product_deleted']);
                                }
                                if (isset($_SESSION['product_edited']))
                                {
                                    echo '<div class="col-sm-12">';
                                    echo $_SESSION['product_edited']; 
                                    echo '</div>';
                                    unset($_SESSION['product_edited']);
                                }
                                if (isset($_SESSION['product_deleted']))
                                {
                                    echo '<div class="col-sm-12">';
                                    echo $_SESSION['product_error'];
                                    echo '</div>';
                                    unset($_SESSION['product_error']);
                                }
                            ?>
                        </div>
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