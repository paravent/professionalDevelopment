<?php
require_once "pdo.php";

if ( !isset($_GET['movieID']) and !isset($_GET['tvSeriesID']) ) {
    die("ID parameter missing");
}

// Set movie IMG id variable if GET parameter set, to be used in loading a poster for the movie from local storage
// added onto file name dynamically. Adds empty string if not set.
$imageLink = "";

// delete later probably
// if (isset($_GET['movieImgId'])) {
//     $imageLink = $_GET['movieImgId'];
// }

// Set movie TRAILER IMG id variable if GET parameter set, to be used in loading a poster for the movie from local storage
// added onto file name dynamically. Adds empty string if not set.
$trailerImgId = "";

if (isset($_GET['trailerImgId'])) {
    $trailerImgId = $_GET['trailerImgId'];
}

$movieShowId = "";

if (isset($_GET['movieID'])) {
    $movieShowId = $_GET['movieID'];
} elseif (isset($_GET['tvSeriesID'])) {
    $movieShowId = $_GET['tvSeriesID'];
}


// queries to select either movie or tvSeries that had been passed in by ID
// (we don't know if it's going to be a movie or tvSeries)
// which are then put to $movieShowArr to display
$stmt = $pdo->prepare('SELECT * FROM movies WHERE movieID=:movieId');
$stmt->execute(array(
    ':movieId' => $movieShowId
));

$stmt2 = $pdo->prepare('SELECT * FROM tvSeries WHERE tvSeriesID=:tvSeriesId');
$stmt2->execute(array(
    ':tvSeriesId' => $movieShowId
));

$movieShowArr = array();

// add all movies dataset to $fullMoviesArray
while ($dbResults = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    $movieShowArr[] = $dbResults;
}

// add all tvSeries dataset to $fullMoviesArray
while ($dbResults = $stmt2->fetch(PDO::FETCH_ASSOC)) { 
    $movieShowArr[] = $dbResults;
}

if (array_key_exists("movieID", $movieShowArr[0])) {
    $imageLink = $movieShowArr[0]['movieImageLink'];
} elseif (array_key_exists("tvSeriesID", $movieShowArr[0])) {
    $imageLink = $movieShowArr[0]['tvSeriesImageLink'];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Movie DB</title>
        <meta name="description" contents="Just a test website for MOVIES DB Project">
        <link rel="stylesheet" href="css/style.css" type="text/css">
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
                // add image dynamically, and if it doesn't exist add dummy movieImg.png
                if ($imageLink != "") {
                    echo '<img src="img/movieTvShowImages' . $imageLink .  '" onerror="this.src=\'img/movieImg.png\'" alt="poster">';
                } else {
                    echo '<img src="img/movieImg.png" alt="poster">';
                }
                
                echo '<table border="1">';
                echo '<tr><th>Title</th><th>Overview</th><th>Popularity</th></tr>';

                if (array_key_exists("movieID", $movieShowArr[0])) {
                    echo '<tr><td>';
                    echo htmlentities($movieShowArr[0]['movieTitle']);
                    echo '</td><td>';
                    echo htmlentities($movieShowArr[0]['movieDescription']);
                    echo '</td><td>';
                    echo htmlentities($movieShowArr[0]['movieScore']);
                    echo '</td></tr>';
                } elseif (array_key_exists("tvSeriesID", $movieShowArr[0])) {
                    echo '<tr><td>';
                    echo htmlentities($movieShowArr[0]['tvSeriesName']);
                    echo '</td><td>';
                    echo htmlentities($movieShowArr[0]['tvSeriesDescription']);
                    echo '</td><td>';
                    echo htmlentities($movieShowArr[0]['tvSeriesScore']);
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