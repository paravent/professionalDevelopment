<?php
require_once "pdo.php";
session_start();
if (!isset($_GET['show_id'])) {
    die("ID parameter missing");
}

// Set movie IMG id variable if GET parameter set, to be used in loading a poster for the movie from local storage
// added onto file name dynamically. Adds empty string if not set.
$movieImgId = "";

if (isset($_GET['movieImgId'])) {
    $movieImgId = $_GET['movieImgId'];
}

// Set movie TRAILER IMG id variable if GET parameter set, to be used in loading a poster for the movie from local storage
// added onto file name dynamically. Adds empty string if not set.
$trailerImgId = "";

if (isset($_GET['trailerImgId'])) {
    $trailerImgId = $_GET['trailerImgId'];
}


$movieShowId = $_GET['show_id'];


$stmt = $pdo->prepare('SELECT * FROM mytable WHERE show_id=:movieId');
$stmt->execute(array(
    ':movieId' => $movieShowId
));


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
            <?php
                // add img id dynamically based on GET parameters according to which movie is being viewed
                // image files are going to be using name syntax such as: movieImg + movieId + .png, e.g. "movieImg10530.png"
                // for testing purposes, $movieImgId variable doesn't add any ID, just an empty string.
                echo '<img src="movieImg' . $movieImgId .  '.png" alt="movie poster">';
                echo '<table border="1" id="movie-data">';
                echo '<tr><th>Title</th><th>Country</th><th>Release Year</th><th>Rating</th></tr>';

                while ($dbResults = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr><td>';
                    echo htmlentities($dbResults['title']);
                    echo '</td><td>';
                    echo htmlentities($dbResults['country']);
                    echo '</td><td>';
                    echo htmlentities($dbResults['release_year']);
                    echo '</td><td>';
                    echo htmlentities($dbResults['rating']);
                    echo '</td></tr>';
                }
                echo '</table>';
                
                // same as movieImgId above, just a placeholder for now, need to think of a way to
                // dynamically add trailer from youtube if possible
                echo '<img src="trailerImg' . $trailerImgId .  '.png" alt="trailer poster">';
                //echo '<pre>'; print_r($dbResults); echo '</pre>';
            ?>
        </div>
        <footer>
            All rights reserved &#169;
        </footer>
    </body>
</html>
