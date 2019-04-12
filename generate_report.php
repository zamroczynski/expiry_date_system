<?php
    session_start();
    if (!isset($_SESSION['logged']))
    {
        header('Location: log_in.php');
        exit();
    }
    $today = new DateTime();
    $today_string = $today->format('Y-m-d');
    require_once 'database_connection.php';
    if(isset($_POST['date_start']))
    {
        $date_start = filter_input(INPUT_POST, 'date_start');
        $date_end = filter_input(INPUT_POST, 'date_end');
        $report = $db->prepare('SELECT expiry_date.date, products.name 
                                FROM expiry_date INNER JOIN products ON products.id=expiry_date.id_product 
                                WHERE expiry_date.date >= :dateStart && expiry_date.date <= :dateEnd');
        $report->bindValue(':dateStart', $date_start, PDO::PARAM_STR);
        $report->bindValue(':dateEnd', $date_end, PDO::PARAM_STR);
        $report->execute();
        $_SESSION['report_ready'] = true;
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Mój profil</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Damian Zamroczynski" />

    <link rel="stylesheet" href="main.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
    <script src="js/jquery.tableToExcel.js"></script>
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
        <div class="generate_report">
            <h2>Generator terminów</h2>
            <form action="generate_report.php" method="POST">
                <label>
                    Wybierz datę początkową: 
                    <input type="date" name="date_start" value="<?= $today_string ?>" />
                </label>
                <label>
                    oraz datę końcową: 
                    <input type="date" name="date_end" value="<?= $today_string ?>" />
                </label>
                <input type="submit" value="Generuj" />
            </form>
            <?php
            if(isset($_SESSION['report_ready']))
            {
                if($report->rowCount()>0)
                {
                    echo '
                    <table id="date_table">
                        <tr>
                            <td>Data</td>
                            <td>Nazwa</td>
                            <td>Ilosc</td>
                        </tr>';
                    foreach ($report as $row)
                    {
                        echo '
                        
                        <tr>
                            <td>'.$row['date'].'</td>
                            <td>'.$row['name'].'</td>
                            <td></td>
                        </tr>';
                    }
                    
                    echo '</table>';
                    echo '
                    <button id="excel">Generuj w excelu</button>
                    ';
                }
                else
                {
                    echo 'Brak wyników!';
                }
                unset($_SESSION['report_ready']);
            }
            ?>
        </div>
        <div class="footer">Termin <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
    <script>
        $('button').click(function (){
            $('table').tblToExcel();
        });
    </script>
</body>
</html>