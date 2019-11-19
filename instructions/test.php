<?php
    session_start();
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <title>Stacja Paliw 4449</title>
        <link rel="icon" href="../img/icon.png">
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="author" content="Damian Zamroczynski" />
        <link rel="stylesheet" href="../css/fontello.css">
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" href="../css/main.css" />
        
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"</scripts>
        <![endif]-->
    </head>
    <body>
        <header>
            <nav class="navbar navbar-dark navbar-expand-md">
                <a class="navbar-brand" href="../index.php">
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
                        <li class="nav-item"><a class="nav-link" href="../index.php">Terminy</a></li>
                        <li class="nav-item"><a class="nav-link" href="../messages.php">Wiadomości</a></li>
                        <li class="nav-item"><a class="nav-link active" href="../manual.php">Podręcznik stacji</a></li>
                        <li class="nav-item"><a class="nav-link" href="../log_in.php">
                        <?php
                            if(isset($_SESSION['logged']))
                            {
                                echo 'Panel Stacji';
                            }
                            else
                            {
                                echo 'Zaloguj';
                            }
                        ?>
                        </a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <article>
                <div class="container-fluid">
                    <header>Test dwa</header>
                    <div class="row">
                    <p class="lead">
                            

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris non nunc felis. Morbi pellentesque fringilla nunc et congue. Morbi eu lorem a mi maximus gravida sit amet eget mauris. Etiam tincidunt volutpat magna, eget cursus tellus mattis ut. Etiam sit amet orci nisl. Donec porta non ex eget sodales. Sed eget volutpat lacus. Cras ornare bibendum tortor. Pellentesque id euismod odio. Quisque eget enim non nibh sollicitudin congue sit amet sed enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam at felis eu ex tristique faucibus. Pellentesque porta id dui eu iaculis. Mauris suscipit sodales turpis, vitae pulvinar nisl finibus quis. Proin non elit venenatis, semper massa ut, scelerisque ex.

Suspendisse at imperdiet leo. Phasellus tempus arcu ut dui dictum, sed consectetur urna tristique. Suspendisse imperdiet ligula tortor, ut ultricies leo rhoncus ut. Aliquam erat volutpat. Proin at eros non felis congue mollis. Aliquam auctor et massa ut auctor. Curabitur feugiat ligula non nulla sagittis posuere. Vestibulum ac fringilla sapien. Donec hendrerit auctor tincidunt. Etiam ut pellentesque purus, porta consequat mauris. Suspendisse vitae neque cursus, feugiat nisl vel, scelerisque purus.

Praesent sollicitudin rutrum nibh vitae efficitur. Vestibulum non diam vel nibh maximus ullamcorper vel faucibus libero. Sed in tellus nec urna faucibus tincidunt. Integer commodo varius ultrices. In nec lacus aliquam, elementum purus sit amet, tincidunt metus. Pellentesque fermentum condimentum iaculis. Aenean augue purus, viverra sed tortor ac, eleifend ultrices nisl. Aliquam eu sem nec est imperdiet accumsan id quis ligula. Praesent viverra eu nulla vitae volutpat. Aliquam in ipsum sagittis, egestas dolor vel, tincidunt nisi. Nunc risus felis, vestibulum ac justo id, laoreet porta justo. Pellentesque varius orci libero, at faucibus eros ultricies aliquam.

Vestibulum ultricies blandit semper. Maecenas vitae tortor ullamcorper lacus vestibulum rutrum eget a arcu. Fusce lacinia venenatis neque, vitae elementum risus finibus eget. Cras a libero in diam dictum volutpat. Ut id libero efficitur, suscipit dui in, elementum mi. Quisque et dignissim orci. Praesent lobortis lacinia rutrum. Morbi eget tortor id dolor faucibus euismod viverra tincidunt mi. Nam egestas urna vestibulum, pellentesque elit eget, lobortis risus. Phasellus massa felis, porta at nisl sit amet, rhoncus volutpat turpis. Sed viverra eleifend risus sit amet mattis. Nam a mi justo. Aenean quis mollis nulla. Duis molestie massa cursus, lacinia orci at, sollicitudin nisi.

Aliquam vel urna id risus faucibus tristique. Nullam sagittis turpis lectus, a aliquam odio volutpat suscipit. Pellentesque augue ex, aliquam accumsan dolor quis, finibus faucibus arcu. Sed sit amet nisi nibh. Nam ac nisi tincidunt, porta augue vitae, aliquet sem. Praesent erat ex, aliquet nec ante nec, imperdiet tempor urna. Fusce pellentesque luctus neque ac tincidunt. Praesent non pulvinar eros. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin placerat vel urna eu tincidunt. Aenean fermentum sem quis risus mattis porttitor. Ut vitae sagittis nunc. Nunc in vulputate sem. Aliquam erat volutpat. Maecenas tempus accumsan libero, quis tempus velit mollis vel. 
                        </p>
                        <a href="../manual.php" class="btn btn-secondary btn-lg btn-block">Powrót</a>
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
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>