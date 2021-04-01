<?php
require_once "pdo.php";
require_once "functions.php";
session_start();

// following up to line 22 for debbuging, to create specific movies to debug image display
// $stmt3 = $pdo->prepare('SELECT * FROM movies WHERE movieID=68721');
// $stmt3->execute();

// while ($dbResults = $stmt3->fetch(PDO::FETCH_ASSOC)) { 
//     $singleMovie = $dbResults;
// }

// $stmt4 = $pdo->prepare('SELECT * FROM movies WHERE movieID=122917');
// $stmt4->execute();

// while ($dbResults = $stmt4->fetch(PDO::FETCH_ASSOC)) { 
//     $singleMovieBrokenImg = $dbResults;
// }

// $stmt = $pdo->prepare('SELECT * FROM tvSeries WHERE tvSeriesID=6');
// $stmt->execute();

// queries to randomly select movies and tvSeries
// which are then all put into $fullMoviesArray
$stmt = $pdo->prepare('SELECT * FROM movies');
$stmt->execute();

$stmt2 = $pdo->prepare('SELECT * FROM tvSeries');
$stmt2->execute();

// $mainPageMoviesArr stores the movies used throughout the homepage, and is randomly generated from the dataset
// when the homepage is loaded/reloaded

// array of random movies that is used in the homepage
$mainPageMoviesArr = array();

// full movies array that is used to extract random movies from
$fullMoviesArray = array();

// add all movies dataset to $fullMoviesArray
while ($dbResults = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    $fullMoviesArray[] = $dbResults;
}

// add all tvSeries dataset to $fullMoviesArray
while ($dbResults = $stmt2->fetch(PDO::FETCH_ASSOC)) { 
    $fullMoviesArray[] = $dbResults;
}

// pick out random movies from the $fullMoviesArray dataset

$fullArrayLength = count($fullMoviesArray); // used to generate random index within bounds of the array
$numbersUsedArray = array(); // random indexes already used so movies don't duplicate on homepage

while (count($mainPageMoviesArr) < 18) {
    $randomIndex = rand(0, $fullArrayLength-1);
    // if $randomIndex is not used yet
    if (!in_array($randomIndex, $numbersUsedArray)) {
        $mainPageMoviesArr[] = $fullMoviesArray[$randomIndex];
        $numbersUsedArray[] = $randomIndex; // to make sure this index is not used again
    }
}

// for debugging specific movies
// $mainPageMoviesArr[0] = $singleMovie;
// $mainPageMoviesArr[1] = $singleMovieBrokenImg;

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
        <!-- JQUERY FILE -->
        <script
                src="https://code.jquery.com/jquery-1.11.2.min.js"
                integrity="sha256-Ls0pXSlb7AYs7evhd+VLnWsZ/AqEHcXBeMZUycz/CcA="
                crossorigin="anonymous"></script>
        <!-- JS file easy autocomplete -->
        <script src="jquery.easy-autocomplete.min.js"></script>
        <!-- CSS file -->
        <link rel="stylesheet" href="easy-autocomplete.min.css">
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/style.css" rel="stylesheet" />
        <!-- bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    </head>
    <body id="page-top">


        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light ">
            <a class="navbar-brand" href="#">
                <img src="img/iMovieIcon.png" style="width: 50px;" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                  <a class="nav-link colour-primary" href="index.html">Home</a>
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
                <form class="form-inline" action="search.php" method="get" >
                    <input id="test" class="form-control mr-sm-2"  type="text" name="test"  placeholder="Search" aria-label="Search" />
                    <script>
                        var options = {
                            url: "movies.json",
                         

                            getValue: "movieTitle",

                            list: {
                                maxNumberOfElements: 8,
                                match: {
                                    enabled: true
                                },
                                sort: {
                                    enabled: true
                                }
                            },

                            theme: "square"
                        };

                        $("#test").easyAutocomplete(options);

                    </script>
                    <button class="search btn-search btn my-2 my-sm-0" type="submit">
                        <i class="fas fa-search"></i>
                    </button>

                </form>
            </div>
        </nav>

        <!-- carousel trial  -->
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img class="d-block w-100" src="img/carousel_img_1.jpeg" alt="First slide">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="img/carousel_img_2.jpeg" alt="Second slide">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="img/carousel_img_3.jpeg" alt="Third slide">
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
          
 

        <!-- jumbotron
        <div class="jumbotron jumbotron-fluid text-center">
            <div class="container">
              <h1 class="display-4 ">iMovie</h1>
              <p class="lead">The place where you can search information abot movies</p>
            </div>
        </div> -->
        

        <div class="container line"> <hr></div>

        <!-- Sorting section -->
        <section class="sort">
            <div class="container">
        </div>
        </section>

        <!-- Contact-->
        <section class="cards">
            <div class="container">
                <div class="row">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort movies by
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Ratings</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Release Date</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Genre</label>
                        </div>
                    </div>
    
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        By genre
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Action</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Animation</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Comedy</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Crime</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Drama</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Fantasy</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Historical</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Horror</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Romance</label>
                        <label class="dropdown-item" ><input type="radio" name="optradio" checked>Triller</label>
    
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[0]); ?>
                    
                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[1]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[2]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[3]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[4]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[5]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[6]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[7]); ?>

                </div>
            </div>
        </section>
        <div class="container line"><hr></div>

        <!-- coming up on section  -->
        <section class="coming-up-on-section">
            <div class="container">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Coming up on:
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <label class="dropdown-item" ><input type="radio" name="optradio" checked>Cinema</label>
                    <label class="dropdown-item" ><input type="radio" name="optradio" checked>HBO</label>
                    <label class="dropdown-item" ><input type="radio" name="optradio" checked>Netflix</label>
                    </div>
                </div>
                <div class="row">
                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[8]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[9]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[10]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[11]); ?>
                </div>
            </div>
        </section>

        <div class="container line"><hr></div>


        <!-- Currently in cinams  -->
        <section class="coming-in-cinema-section">
            <div class="container">
                <p class="colour-primary">Currently in Cinema</p>
                <div class="row">
                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[12]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[13]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[14]); ?>

                    <?php echo createMovieTvShowArtefact($mainPageMoviesArr[15]); ?>
                </div>
            </div>
        </section>

        <div class="container line"> <hr></div>


        <!-- carousel trial -->
        <!-- <div class="container text-center my-3">
            <h2 class="font-weight-light">Bootstrap 4 - Multi Item Carousel</h2>
            <div class="row mx-auto my-auto">
                <div id="recipeCarousel" class="carousel slide w-100" data-ride="carousel">
                    <div class="carousel-inner w-100" role="listbox">
                        <div class="carousel-item active">
                            <div class="col-md-4">
                                <div class="card card-body">
                                    <img class="img-fluid" src="http://placehold.it/380?text=1">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="col-md-4">
                                <div class="card card-body">
                                    <img class="img-fluid" src="http://placehold.it/380?text=2">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="col-md-4">
                                <div class="card card-body">
                                    <img class="img-fluid" src="http://placehold.it/380?text=3">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="col-md-4">
                                <div class="card card-body">
                                    <img class="img-fluid" src="http://placehold.it/380?text=4">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="col-md-4">
                                <div class="card card-body">
                                    <img class="img-fluid" src="http://placehold.it/380?text=5">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="col-md-4">
                                <div class="card card-body">
                                    <img class="img-fluid" src="http://placehold.it/380?text=6">
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev w-auto" href="#recipeCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark border border-dark rounded-circle" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next w-auto" href="#recipeCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon bg-dark border border-dark rounded-circle" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <h5 class="mt-2">Advances one slide at a time</h5>
        </div> -->


        

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
