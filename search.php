
<?php 
include "pdo.php";
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


$movie = $_GET['test'];
$_SESSION["searchQUERY"] = $movie;

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




echo $budget;
echo $revenue;
echo $year;
echo $genre;

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


//build a query based on ifElse statements


// pick out random movies from the $fullMoviesArray dataset
$resultArray = array_merge($fullMoviesArray, $fulltvSeriesArray );
if (isset($_GET['Filter'])){
    $filterSTMT = $pdo->prepare("SELECT * FROM movies WHERE  movieTitle LIKE '%$movie%'
                                     AND
                                     movieRevenue > '%$revenue%' 
                                     AND
                                     movieBudget > '%$budget%'");

    $filterSTMT->execute();
    $filteredArr = array();
    while ($dbResults3 = $filterSTMT->fetch(PDO::FETCH_ASSOC)) {
        $finalResultArr[] = $dbResults3;
    }
    $resultArray2 = array_merge($finalResultArr, $fullMoviesArray, $fulltvSeriesArray );
    $fullArrayLength = count($resultArray2); // used to generate random index within bounds of the array
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
                <h1 class="colour-primary">Search results for: <span class="search-name"><?php echo $movie; ?>
                            </span></h1>
                <form action="" method="get">
                    <div class="row justify-content-between">

                        <select class="btn colour-primary dropdown" id="budget" name="budget">
                            <option>Sort movie by budget</option>
                            <option value="10000000">Over 10 million</option>
                            <option value="20000000">Over 20 million</option>
                            <option value="30000000">Over 30 million</option>
                            <option value="50000000">Over 50 million</option>
                            <option value="100000000">Over 100 million</option>
                            <option value="200000000">Over 200 million</option>
                            <option value="500000000">Over 500 million</option>
                        </select>

                        <select class="btn colour-primary  dropdown" id="revenue" name="revenue">
                            <option>Sort movie by Revenue</option>
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

                        <select class="btn colour-primary dropdown" id="year" name="year">
                            <option>Sort movie by year</option>
                            <option value="1940">1940</option>
                            <option value="1941">1941</option>
                            <option value="1942">1942</option>
                            <option value="1943">1943</option>
                            <option value="1944">1944</option>
                            <option value="1945">1945</option>
                            <option value="1946">1946</option>
                            <option value="1947">1947</option>
                            <option value="1948">1948</option>
                            <option value="1949">1949</option>
                            <option value="1950">1950</option>
                            <option value="1951">1951</option>
                            <option value="1952">1952</option>
                            <option value="1953">1953</option>
                            <option value="1954">1954</option>
                            <option value="1955">1955</option>
                            <option value="1956">1956</option>
                            <option value="1957">1957</option>
                            <option value="1958">1958</option>
                            <option value="1959">1959</option>
                            <option value="1960">1960</option>
                            <option value="1961">1961</option>
                            <option value="1962">1962</option>
                            <option value="1963">1963</option>
                            <option value="1964">1964</option>
                            <option value="1965">1965</option>
                            <option value="1966">1966</option>
                            <option value="1967">1967</option>
                            <option value="1968">1968</option>
                            <option value="1969">1969</option>
                            <option value="1970">1970</option>
                            <option value="1971">1971</option>
                            <option value="1972">1972</option>
                            <option value="1973">1973</option>
                            <option value="1974">1974</option>
                            <option value="1975">1975</option>
                            <option value="1976">1976</option>
                            <option value="1977">1977</option>
                            <option value="1978">1978</option>
                            <option value="1979">1979</option>
                            <option value="1980">1980</option>
                            <option value="1981">1981</option>
                            <option value="1982">1982</option>
                            <option value="1983">1983</option>
                            <option value="1984">1984</option>
                            <option value="1985">1985</option>
                            <option value="1986">1986</option>
                            <option value="1987">1987</option>
                            <option value="1988">1988</option>
                            <option value="1989">1989</option>
                            <option value="1990">1990</option>
                            <option value="1991">1991</option>
                            <option value="1992">1992</option>
                            <option value="1993">1993</option>
                            <option value="1994">1994</option>
                            <option value="1995">1995</option>
                            <option value="1996">1996</option>
                            <option value="1997">1997</option>
                            <option value="1998">1998</option>
                            <option value="1999">1999</option>
                            <option value="2000">2000</option>
                            <option value="2001">2001</option>
                            <option value="2002">2002</option>
                            <option value="2003">2003</option>
                            <option value="2004">2004</option>
                            <option value="2005">2005</option>
                            <option value="2006">2006</option>
                            <option value="2007">2007</option>
                            <option value="2008">2008</option>
                            <option value="2009">2009</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                        </select>

                        <select class="btn colour-primary dropdown" id="genre" name="genre">
                            <option>Sort movie by genre</option>
                            <option value="Action">Action</option>
                            <option value="Animation">Animation</option>
                            <option value="Comedy">Comedy</option>
                            <option value="Crime">Crime</option>
                            <option value="Drama">Drama</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="Historical">Historical</option>
                            <option value="Horror">Horror</option>
                            <option value="Romance">Romance</option>
                            <option value="Triller">Triller</option>
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
                    for($x = 0; $x<$fullArrayLength; $x++) {
                        echo createMovieTvShowArtefact($resultArray[$x]);
                    }

                    for($x = 0; $x<$fullActorOrDirectorArrayLength; $x++) {
                        echo createActorOrDirectorArtefact($fullActorOrDirectorArray[$x]);
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