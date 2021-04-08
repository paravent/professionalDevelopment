
<?php
require_once "pdo.php";
require_once "functions.php";
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$tableName = "profdev";
$mysqli = new mysqli($servername, $username, $password, $tableName);


// Check connection
if ($mysqli->connect_error) {
    die();
}

$movie = "";
if (isset($_GET['test'])) {
    $movie = $_GET['test'];
    $_SESSION["searchQUERY"] = $movie;

}


// used for searching actors and directors
// lets user type in firstName and lastName, just firstName, or just lastName
$nameSplitArr = explode(" ", $movie);

$firstName = "";
$lastName = "";

// if there's at least 1 word
if (count($nameSplitArr) > 0) {
    $firstName = $nameSplitArr[0];
}

// if there's at least 2 words
if (count($nameSplitArr) > 1) {
    $lastName = $nameSplitArr[1];
}


$fullMoviesArray = array();
$fulltvSeriesArray = array();
$fullActorOrDirectorArray = array();

if ($movie != "") {
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE movieTitle LIKE '%$movie%'");
    $stmt2 = $pdo->prepare("SELECT * FROM tvSeries WHERE tvSeriesName LIKE '%$movie%' ");

    // $stmt3 = $pdo->prepare("SELECT * FROM actors WHERE (actorFirstName LIKE '%$firstName%' OR actorLastName LIKE '%$lastName%')");
    // $stmt4 = $pdo->prepare("SELECT * FROM directors WHERE (directorFirstName LIKE '%$firstName%' OR directorLastName LIKE '%$lastName%')");

    $actorQuery = "SELECT * FROM actors WHERE";
    $directorQuery = "SELECT * FROM directors WHERE";

    // first word in search
    if ($firstName != "") {
        // check first name
        $actorQuery = $actorQuery .  " (actorFirstName LIKE '%$firstName%')";
        $directorQuery = $directorQuery . " (directorFirstName LIKE '%$firstName%')";
        // check whether first word in search is the last name
        $actorQuery = $actorQuery .  " OR (actorLastName LIKE '%$firstName%')";
        $directorQuery = $directorQuery . " OR (directorLastName LIKE '%$firstName%')";
    }

    // second word in search
    if ($lastName != "") {
        $actorQuery = $actorQuery .  " OR (actorLastName LIKE '%$lastName%')";
        $directorQuery = $directorQuery . " OR (directorLastName LIKE '%$lastName%')";
        // check if last name entered is actually the first name
        $actorQuery = $actorQuery .  " OR (actorFirstName LIKE '%$lastName%')";
        $directorQuery = $directorQuery . " OR (directorFirstName LIKE '%$lastName%')";
    }

    $stmt3 = $pdo->prepare($actorQuery);
    $stmt4 = $pdo->prepare($directorQuery);


    $stmt->execute();
    $stmt2->execute();
    $stmt3->execute();
    $stmt4->execute();



    // $mainPageMoviesArr stores the movies used throughout the homepage, and is randomly generated from the dataset
    // when the homepage is loaded/reloaded

    // array of random movies that is used in the homepage




    $mainPageMoviesArr = array();

    // full movies array that is used to extract random movies from
    $fullMoviesArray = array();
    $fulltvSeriesArray = array();
    $fullActorOrDirectorArray = array();

    // add all movies dataset to $fullMoviesArray
    while ($dbResults = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $fullMoviesArray[] = $dbResults;
    }
    while ($dbResults2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $fulltvSeriesArray[] = $dbResults2;
    }

    while ($dbResults3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
        $fullActorOrDirectorArray[] = $dbResults3;
    }

    while ($dbResults4 = $stmt4->fetch(PDO::FETCH_ASSOC)) {
        $fullActorOrDirectorArray[] = $dbResults4;
    }
}




//build a query based on ifElse statements


// pick out random movies from the $fullMoviesArray dataset
$resultArray = array_merge($fullMoviesArray, $fulltvSeriesArray );
if (isset($_GET['Filter'])){

    if (isset($_GET['budget'])){
        $budget = $_GET['budget'];
    }
    if (isset($_GET['revenue'])){
        $revenue = $_GET['revenue'];
    }
    if (isset($_GET['year'])){
        $year = $_GET['year'];
    }
    if (isset($_GET['genre'])){
        $genre = $_GET['genre'];
    }

    $testing = $_SESSION["searchQUERY"];

    $filterSTMTmovieSQL = "SELECT * FROM movies
    INNER JOIN genreInstance ON genreInstance.movieID=movies.movieID
    INNER JOIN genres ON genreInstance.genreID=genres.genreID
    WHERE
    movies.movieTitle LIKE '%$testing%'";

    if ($revenue != "-1") {
        $filterSTMTmovieSQL = $filterSTMTmovieSQL . " AND movies.movieRevenue > '%$revenue%'";
    }

    if ($budget != "-1") {
        $filterSTMTmovieSQL = $filterSTMTmovieSQL . " AND movies.movieBudget > '%$budget%'";
    }

    if ($genre != "-1") {
        $filterSTMTmovieSQL = $filterSTMTmovieSQL . " AND genres.genreID = '$genre'";
    }


    $filterSTMTmovie = $pdo->prepare($filterSTMTmovieSQL);

    //-----------------------------------------------------------------------------------

    $filterSTMTtvseriesSQL = "SELECT * FROM tvseries
    INNER JOIN genreInstance ON genreInstance.tvSeriesID=tvseries.tvSeriesID
    INNER JOIN genres ON genreInstance.genreID=genres.genreID
    WHERE
    tvseries.tvSeriesName LIKE '%$testing%'";

    if ($revenue != "-1") {
        $filterSTMTtvseriesSQL = $filterSTMTtvseriesSQL . " AND movies.movieRevenue > '%$revenue%'";
    }

    if ($budget != "-1") {
        $filterSTMTtvseriesSQL = $filterSTMTtvseriesSQL . " AND movies.movieBudget > '%$budget%'";
    }

    if ($genre != "-1") {
        $filterSTMTtvseriesSQL = $filterSTMTtvseriesSQL . " AND genres.genreID = '$genre'";
    }



    $filterSTMTtvseries = $pdo->prepare($filterSTMTtvseriesSQL);

    //Filter protocol

    $filterSTMTmovie->execute();
    $filterSTMTtvseries->execute();
    $filteredArr = array();
    $finalResultArr = array();
    $finalResultArr2 = array();
    while ($dbResults5 = $filterSTMTmovie->fetch(PDO::FETCH_ASSOC)) {
        $finalResultArr[] = $dbResults5;
    }
    while ($dbResults6 = $filterSTMTtvseries->fetch(PDO::FETCH_ASSOC)) {
        $finalResultArr2[] = $dbResults6;
    }
    $resultArray = array_merge($finalResultArr, $fullMoviesArray, $fulltvSeriesArray, $finalResultArr2 );
    $fullArrayLength = count($resultArray); // used to generate random index within bounds of the array
}

else {
    $fullArrayLength = count($resultArray);}




$numbersUsedArray = array(); // random indexes already used so movies don't duplicate on homepage

$fullActorOrDirectorArrayLength = count($fullActorOrDirectorArray); // used to loop through the array




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Grayscale - Start Bootstrap Theme</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/style.css" rel="stylesheet" />
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body id="page-top">


<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light ">
    <a class="navbar-brand" href="#">
        <img src="img/iMovieIcon.png" style="width: 50px;" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link colour-primary" href="index.php">Home</a>

            </li>
            <li class="nav-item">
                <a class="nav-link colour-primary" href="wishlist.html">Wishlist</a>
            </li>
            <li class="nav-item">
                <?php
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

                    echo " <a class=\"nav-link colour-primary\"  </a> ";
                    echo htmlspecialchars($_SESSION["username"]);
                    echo " <a class=\"nav-link colour-primary\" href=\"logout.php\">Logout </a> ";


                }
                else {
                    echo " <a class=\"nav-link colour-primary\" href=\"login.php\">Login </a> ";
                }
                ?>
            </li>
        </ul>
        <!-- <form class="form-inline my-2 my-lg-0"> -->
        <form class="form-inline" action="search.php" method="get">
            <input class="form-control mr-sm-2"  type="text" name="test"  placeholder="Search" aria-label="Search">
            <button class="search btn-search btn my-2 my-sm-0" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</nav>






<!-- container -->
<section class="search">

    <div class="container">
        <!-- search header -->
        <section class="search-header">
            <h1 class="colour-primary">Search results for: <span class="search-name"><?php echo $movie;
                    if (isset($_GET['Filter'])){
                       echo $testing;
                    }?>
                            </span></h1>
            <form action="" method="get">
                <div class="row justify-content-between">

                    <select class="btn colour-primary dropdown" id="budget" name="budget">
                        <option value="-1">Sort movie by budget</option>
                        <option value="10000000">Over 10 million</option>
                        <option value="20000000">Over 20 million</option>
                        <option value="30000000">Over 30 million</option>
                        <option value="50000000">Over 50 million</option>
                        <option value="100000000">Over 100 million</option>
                        <option value="200000000">Over 200 million</option>
                        <option value="500000000">Over 500 million</option>
                    </select>

                    <select class="btn colour-primary  dropdown" id="revenue" name="revenue">
                        <option value="-1">Sort movie by Revenue</option>
                        <option value="10000000">Over 10 million</option>
                        <option value="20000000">Over 20 million</option>
                        <option value="30000000">Over 30 million</option>
                        <option value="50000000">Over 50 million</option>
                        <option value="100000000">Over 100 million</option>
                        <option value="200000000">Over 200 million</option>
                        <option value="300000000">Over 300 million</option>
                        <option value="500000000">Over 500 million</option>
                        <option value="1000000000">Over 1 billion</option>
                    </select>


                    <select class="btn colour-primary dropdown" id="genre" name="genre">
                        <option value="-1">Sort movie by genre</option>
                        <option value="1">Action</option>
                        <option value="10">Animation</option>
                        <option value="13">Comedy</option>
                        <option value="17">Crime</option>
                        <option value="11">Drama</option>
                        <option value="9">Fantasy</option>
                        <option value="14">Historical</option>
                        <option value="12">Horror</option>
                        <option value="3">Romance</option>
                        <option value="16">Thriller</option>
                    </select>

                    <select class="btn colour-primary dropdown" id="searchQUERY1" name="searchQUERY1">
                        <option value="<?php '%movie%' ?>">CONSTANT </option>

                    </select>

                    <input class="btn btn-customised colour-primary " type="submit" value="Filter" name="Filter">

                </div>

            </form>


            <hr>

            <p class="colour-primary">Related searches: <span class="search-results-number"> <?php echo htmlentities($fullArrayLength + $fullActorOrDirectorArrayLength);  ?> </span></p>
            <hr>
            <div class="row">
                <?php
                if (count($resultArray) > 0) {
                    for($x = 0; $x<$fullArrayLength; $x++) {
                        echo createMovieTvShowArtefact($resultArray[$x]);
                    }
                }

                if (count($fullActorOrDirectorArray) > 0) {
                    for($x = 0; $x<$fullActorOrDirectorArrayLength; $x++) {
                        echo createActorOrDirectorArtefact($fullActorOrDirectorArray[$x]);
                    }
                }


                ?>

            </div>
        </section>
        <!-- wishlist body -->
        <section class="search-body">
            <!-- row / movie instance-->
            <div class="row justify-content-around">
                <!-- wishlist-instance-img -->
                <div class="align-self-center wishlist-instance-image col-md-3 mb-3 mb-md-0 card-container">
                    <!-- <div class="card h-100"> -->

                    <!-- </div> -->
                </div>
                <!-- wishlist-instance-body -->
                <!-- <div class="wishlist-instance-body col-md-8 mb-3 mb-md-0">
                    <button type="button" class="btn colour-primary align-self-center btn-remove-wishlist"> -->
                <!-- <i class="fas fa-minus fa-2x colour-primary"></i>  -->
                <!-- Remove from wishlist
            </button>
        </div> -->
            </div>
            <hr>


        </section>

    </div>

</section>
</div>
</section>








<!-- Footer-->
<footer class="footer bg-black small text-center text-white-50">
    <div class="container">
        <div class="social d-flex justify-content-center">
            <a class="mx-2" href="#!"><i class="fab fa-twitter fa-2x colour-primary"></i></a>
            <a class="mx-2" href="#!"><i class="fab fa-facebook-f fa-2x colour-primary"></i></a>
            <a class="mx-2" href="#!"><i class="fab fa-instagram fa-2x colour-primary"></i></a>
            <a class="mx-2" href="#!"><i class="fab fa-pinterest fa-2x colour-primary"></i></a>
        </div>


        <div class="footer-options row justify-content-md-center">
            <div class="col-sm-2">
                Home
            </div>
            <div class="col-sm-2">
                Wishlist
            </div>
            <div class="col-sm-2">
                Login
            </div>
        </div>



        <p>Website by Andrei Frandes</p>
    </div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Third party plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

<script>
    // controlling the carousel
    $('.carousel').carousel({
        interval: 3000
    })
</script>

</body>

</html>
© 2021 GitHub, Inc.
Terms
Privacy
Security
Status
Docs
Contact GitHub
Pricing
API
Training
Blog
About
Loading complete