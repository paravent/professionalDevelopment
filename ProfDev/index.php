<?php
require_once "pdo.php";
session_start();


$stmt = $pdo->prepare('SELECT * FROM mytable LIMIT 10');
$stmt->execute();

$mainPageMoviesArr = array();

while ($dbResults = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    $mainPageMoviesArr[] = $dbResults;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Movie DB</title>
        <meta name="description" contents="Just a test website for MOVIES DB Project">
        <link rel="stylesheet" href="style.css" type="text/css">
                <div id="dynamic-button">
                    <?php
                        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                        
                            echo " <a href=\"logout.php\">LOGOUT</a>";
                            echo "<b>           </b>"; 
                            echo htmlspecialchars($_SESSION["username"]);
                            
                
                }
                else {
                    echo " <a href=\"login.php\">LOGIN</a>";
                }
                ?>
    </head>
    <body>
        <header>
            <nav id="main-navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="page2.php">Page 2</a></li>
                    <li><a href="page3.php">Page 3</a></li>
                </ul>
            </nav>
        </header>
        <div id="main-contents">
            <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[0]['show_id']);?>">
                <article id="main-movie">
                    <div><img src="600x400dummy.png" alt="dummy movie poster" /></div>
                    <div>
                        <h2><?php echo htmlentities($mainPageMoviesArr[0]['title']); ?></h2>
                        <p><?php echo htmlentities($mainPageMoviesArr[0]['rating']); ?></p>
                        <p><?php echo htmlentities($mainPageMoviesArr[0]['description']); ?></p>
                    </div>
                </article>
            </a>
            
            <div id="movie-wall">
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[1]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[1]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[1]['rating']); ?></p>
                    </article>
                </a>
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[2]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[2]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[2]['rating']); ?></p>
                    </article>
                </a>
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[3]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[3]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[3]['rating']); ?></p>
                    </article>
                </a>
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[4]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[4]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[4]['rating']); ?></p>
                    </article>
                </a>
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[5]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[5]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[5]['rating']); ?></p>
                    </article>
                </a>
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[6]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[6]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[6]['rating']); ?></p>
                    </article>
                </a>
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[7]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[7]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[7]['rating']); ?></p>
                    </article>
                </a>
                <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[8]['show_id']);?>">
                    <article>
                        <img src="150x200dummy.png" alt="dummy movie poster">
                        <h3><?php echo htmlentities($mainPageMoviesArr[8]['title']); ?></h3>
                        <p><?php echo htmlentities($mainPageMoviesArr[8]['rating']); ?></p>
                    </article>
                </a>
            </div>
            
            <a href="moviePage.php?show_id=<?php echo htmlentities($mainPageMoviesArr[9]['show_id']);?>">
                <article id="recommendations">
                    <div><img src="250x300dummy.png" alt="dummy movie poster" /></div>
                    <div>
                        <h2><?php echo htmlentities($mainPageMoviesArr[9]['title']); ?></h2>
                        <p><?php echo htmlentities($mainPageMoviesArr[9]['rating']); ?></p>
                        <p><?php echo htmlentities($mainPageMoviesArr[9]['description']); ?></p>
                    </div>
                </article>
            </a>
        </div>
        <footer>
            All rights reserved &#169;
        </footer>
    </body>
</html>
