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
    if($_SESSION['user_power']>6)
    {
        $all_messages = $db->query('SELECT messages.contents, messages.date_start, messages.date_end, messages.rank, 
                                    users.name, messages.id 
                                    FROM messages INNER JOIN users ON users.id=messages.id_user 
                                    ORDER BY messages.date_end');
    }
    if($_SESSION['user_power']<=6 && $_SESSION['user_power']>=2)
    {
        $user_messages = $db->query('SELECT messages.contents, messages.date_start, messages.date_end, messages.rank, 
                                    users.name, messages.id 
                                    FROM messages INNER JOIN users ON users.id=messages.id_user 
                                    WHERE users.id='.$_SESSION['user_id'].' 
                                    ORDER BY messages.date_end');
    }
    if (isset($_POST['delete_message']))
    {
        $message = filter_input(INPUT_POST, 'message_id');
        $db->query('DELETE FROM messages WHERE messages.id="'.$message.'"');
        header('Location: edit_messages.php');
    }
    if (isset($_POST['edit_message']))
    {
        $message = filter_input(INPUT_POST, 'message_id');
        $edit_message = $db->query('SELECT messages.contents, messages.date_start, messages.date_end 
                                    FROM messages WHERE messages.id="'.$message.'"');
        $row = $edit_message->fetch();
        $string_form_message_adding = '
        <div class="adding_message_form">
            <form method="POST">
                <ul class="adding">
                <li class="date">Obowiązuje od <input type="date" name="first_date" value="'.$row['date_start'].'" ></li>
                <li class="date"> do <input type="date" name="last_date" value="'.$row['date_end'].'" ></li>
                <li><textarea name="new_message" >'.$row['contents'].'</textarea></li>
                <li>
                Jak ważna to wiadomość?
                <select name="rank">
                <option value="3">BARDZO WAŻNA WIADOMOŚĆ!</option>
                <option value="2">Ważna wiadmość!</option>
                <option value="1">Zwykła wiadomość</option>
                </select>
                </li>
                <li><input type="hidden" name="id_message" value="'.$message.'" /></li>
                <li><input type="submit" value="Zmień" /></li>
                </ul>
            </form>
        </div>';
        $_SESSION['edit_string'] = true;
    }
    if (isset($_POST['id_message']))
    {
        $rank = filter_input(INPUT_POST,'rank');
        $id_message = filter_input(INPUT_POST, 'id_message');
        $message = filter_input(INPUT_POST, 'new_message');
        $new_message = nl2br($message);
        $first_date = filter_input(INPUT_POST, 'first_date');
        $last_date = filter_input(INPUT_POST, 'last_date');
        if($_SESSION['user_power']>=6)
        {
            $message_query=$db->prepare('UPDATE messages 
            SET contents=:message, date_start="'.$first_date.'", date_end="'.$last_date.'", rank="'.$rank.'" 
            WHERE id='.$id_message);
            $message_query->bindValue(':message', $new_message, PDO::PARAM_STR);
            $message_query->execute();
            header('Location: edit_messages.php');
        }
        else if($_SESSION['user_power']<=2 && $rank<=1)
        {
            $message_query=$db->prepare('UPDATE messages 
            SET contents=:message, date_start="'.$first_date.'", date_end="'.$last_date.'", rank="'.$rank.'" 
            WHERE id='.$id_message);
            $message_query->bindValue(':message', $new_message, PDO::PARAM_STR);
            $message_query->execute();
            header('Location: edit_messages.php');
        }
        else if ($_SESSION['user_power']<6 && $_SESSION['user_power']>2)
        {
            $message_query=$db->prepare('UPDATE messages 
            SET contents=:message, date_start="'.$first_date.'", date_end="'.$last_date.'", rank="'.$rank.'" 
            WHERE id='.$id_message);
            $message_query->bindValue(':message', $new_message, PDO::PARAM_STR);
            $message_query->execute();
            header('Location: edit_messages.php');
        }
        else
        {
            $_SESSION['message_error'] = '<div class="error_div>"Brak uprawnień!</div>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Stacja Paliw 4449 - Edycja wiadomości</title>
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
                    <a href="#">Produkty</a>
                    <ul>
                        <li><a href="adding_products.php">Dodaj produkty</a></li>
                        <li><a href="edit_products.php">Edytuj produkty</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="active">Wiadomości</a>
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
        if (isset($_SESSION['message_error']))
        {
            echo '<h2>'.$_SESSION['message_error'].'</h2>';
            unset($_SESSION['message_error']);
        }
        if(isset($_SESSION['edit_string']))
        {
            echo $string_form_message_adding;
            unset($_SESSION['edit_string']);
        }
        ?>
    <div class="result">
        <ol class="messages">
        <?php
        if ($_SESSION['user_power']>6)
        {
            if ($all_messages->rowCount()>0)
            {
                echo '<h2>Wszystkie wiadomości od najstarszych:</h2>';
                foreach ($all_messages as $row)
                {
                    echo '<li><form method="POST">';
                    echo '<div class="messages_bar">';
                    echo "<ul>";
                    echo '<li> <input type="hidden" name="message_id" value="'.$row['id'].'" />';
                    echo "Napisał(a): ";
                    print_r($row['name']);
                    echo "</li>";
                    echo "<li>";
                    echo "Obowiązuje od ";
                    print_r($row['date_start']);
                    echo " do ";
                    print_r($row['date_end']);
                    echo "</li>";
                    echo "<li>";
                    if ($row['rank']>=3) echo '<div style="color:red;text-transform: uppercase;">Bardzo ważna wiadomość!</div>';
                    if ($row['rank']==2) echo '<div style="color:#ff6666;">Ważna wiadomość!</div>';
                    echo '';
                    echo "</li>";
                    echo '<li><input type="submit" name="delete_message" value="USUŃ" /></li>';
                    echo '<li><input type="submit" name="edit_message" value="EDYTUJ" /></li>';
                    echo "</ul></div>";
                    print_r($row['contents']);
                    echo "</form></li>";
                }
            }
            else
            {
                echo '<h2>Brak wiadomości!</h2>';
            }
        }
        else
        {
            if ($user_messages->rowCount()>0)
            {
                echo '<h2>Wszystkie wiadomości od najstarszych:</h2>';
                foreach ($user_messages as $row)
                {
                    echo '<li><form method="POST">';
                    echo '<div class="messages_bar">';
                    echo "<ul>";
                    echo '<li> <input type="hidden" name="message_id" value="'.$row['id'].'" />';
                    echo "Napisał: ";
                    print_r($row['name']);
                    echo "</li>";
                    echo "<li>";
                    echo "Obowiązuje od ";
                    print_r($row['date_start']);
                    echo " do ";
                    print_r($row['date_end']);
                    echo "</li>";
                    echo "<li>";
                    if ($row['rank']>=3) echo '<div style="color:red;text-transform: uppercase;">Bardzo ważna wiadomość!</div>';
                    if ($row['rank']==2) echo '<div style="color:#ff6666;">Ważna wiadomość!</div>';
                    echo '';
                    echo "</li>";
                    echo '<li><input type="submit" name="delete_message" value="USUŃ" /></li>';
                    echo '<li><input type="submit" name="edit_message" value="EDYTUJ" /></li>';
                    echo "</ul></div>";
                    print_r($row['contents']);
                    echo "</form></li>";
                }
            }
            else
            {
                echo '<h2>Brak wiadomości!</h2>';
            }
        }
        ?>
        </ol>
    </div>
        <div class="footer">Terminy <span style="color:green;">ONLINE</span> - Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019 Kontakt: damianzamroczynski@gmail.com</div>
    </div>
</body>
</html>
