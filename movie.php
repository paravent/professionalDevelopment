<?php
require_once "pdo.php";
require_once "functions.php";

// check whether movie or tvSeries ID was passed in through $_GET from previous page
if ( !isset($_GET['movieID']) and !isset($_GET['tvSeriesID']) ) {
    die("ID parameter missing");
}

// get movie or tvSeries ID from $_GET parameters
$movieShowId = "";

if (isset($_GET['movieID'])) {
    $movieShowId = $_GET['movieID'];
} elseif (isset($_GET['tvSeriesID'])) {
    $movieShowId = $_GET['tvSeriesID'];
}


// get movies or tvSeries with genres if genre exists in the DB for that item, or without if it doesn't exist
$movieShowArr = getMoviesShowsWithOrWithoutGenre($pdo, $movieShowId);

// get director for the movie/tvSeries if it exists in the DB for that specific movie/tvSeries
$movieShowArrWithDirector = getMoviesShowsWithDirector($pdo, $movieShowArr);

// get relative image link from the DB if it exists
$imageLink = getImageLink($movieShowArr);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Actor page</title>
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
                    <a class="nav-link colour-primary" href="login.html">Login</a>
                </li>
            </ul>
            <!-- <form class="form-inline my-2 my-lg-0"> -->
            <form class="form-inline" action="search.php">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" name="test" aria-label="Search">
                <button class="search btn-search btn my-2 my-sm-0" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </nav>






    <!-- container -->
    <section class="movie">

        <div class="container">
            <!-- movie header -->
            <section class="movie-header">
                <div class="row justify-content-between">
                    <h1 class="colour-primary"><span class="search-name"><?php 
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieTitle', 'tvSeriesName');
                        ?></span></h1>
                    <div class="movie-stars">
                        <h3 class="card-text-stars movie-stars-number colour-primary"><?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieScore', 'tvSeriesScore');
                        ?></h3>
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>

                <hr>
            </section>
        </div>

        <div class="container">
            <!-- movie body -->
            <section class="movie-body">
                <div class="row movie-row justify-content-around">
                    <div class="align-self-center movie-instance-image col-md-3 mb-3 mb-md-0 card-container">
                        <!-- <div class="card h-100"> -->
                        <?php
                            // add image dynamically, and if it doesn't exist add dummy movieImg.png
                            if ($imageLink != "") {
                                echo '<img class="card-img-top" src="img/movieTvShowImages' . $imageLink .  '" onerror="this.src=\'img/movieImg.png\'" alt="Card image cap">';
                            } else {
                                echo '<img class="card-img-top" src="img/movieImg.png" alt="poster">';
                            }
                            ?>
                        <!-- <img class="card-img-top" src="img/interstellar.png" alt="Card image cap"> -->
                        <!-- </div> -->
                    </div>
                    <!-- <div class="movie-instance-body">

                </div> -->
                    <div class="movie-instance-body col-md-8 mb-3 mb-md-0">
                        <h1 class="colour-primary"><?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieTitle', 'tvSeriesName');
                        ?></h1>
                        <span class="wishlist-movie-duration colour-3"><?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieDuration', 'tvSeriesAverageDuration');
                            ?>min</span>
                        <span class="colour-3"> | </span>
                        <span class="wishlist-movie-genre colour-3"><?php
                            // display genres if they exist for this movie/tvSeries in the DB
                            displayGenres($movieShowArr);
                        ?></span>
                        <span class="colour-3"> | </span>
                        <span class="wishlist-movie-release-date colour-3">Released: <?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieReleaseDate', 'tvSeriesReleaseDate');
                        ?></span>
                        <br>
                        <h4 style="display: inline;" class="colour-primary"><?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieScore', 'tvSeriesScore');
                        ?></h4>
                        <i class="fas fa-star fa-1x"></i>
                        <hr>
                        <p class="colour-secondary"><?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieDescription', 'tvSeriesDescription');
                        ?></p>
                    </div>
                </div>

                <!-- movie details -->
                <div class="row movie-details-section">
                    <div class="movie-details colour-primary  col-md-12 mb-3 mb-md-0">
                        <p>Budget: <span class="movie-budget"><?php 
                            // echo budget for movie/tvSeries
                            displayBudget($movieShowArr);
                        ?>mln</span> </p>
                    </div>
                    <div class="movie-details colour-primary col-md-12 mb-3 mb-md-0">
                        <p>Director: <span class="movie-director"><?php 
                            
                            // echo director if exists for this movie/tvSeries
                            displayDirector($movieShowArrWithDirector);
                        ?></span></p>
                    </div>
                    <div class=" movie-details colour-primary col-md-12 mb-3 mb-md-0">
                        <p>Duration: <span class="movie-duration"><?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieDuration', 'tvSeriesAverageDuration');
                            ?>min</span></p>
                    </div>
                    <div class="movie-details colour-primary  col-md-12 mb-3 mb-md-0">
                        <p>Release date: <span class="movie-release-date"> <?php
                            // check whether it's a movie or tvSeries and display the appropriate key value
                            displayEitherMovieOrSeriesDataItem($movieShowArr, 'movieReleaseDate', 'tvSeriesReleaseDate');
                        ?></span></p>
                    </div>
                    <div class="movie-details colour-primary  col-md-12 mb-3 mb-md-0">
                        <p>Genre(s): <span class="movie-genre" ><?php 
                            // display genres if they exist for this movie/tvSeries in the DB
                            displayGenres($movieShowArr);
                        ?></span></p>
                    </div>
                    <?php
                        // display Season and Episode numbers if it's a tvSeries
                        echo displaySeriesSeasonAndEpisodeNumber($movieShowArr);
                    ?>
                    
                </div>

                <!-- movie  review-->
                <div class="movie-review-section">
                    <form>
                        <h1 class="colour-primary">Reviews from users</h1>
                        <div class="form-group">
                            <textarea class="form-control review-textarea-1 review-textarea"
                                 rows="8">Gerard Elenor

                                "Lorem, ipsum dolor sit amet consectetur adipisicing elit.Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit natus esse neque, officia quis rem aut dolorem ad vitae fuga suscipit? Dicta excepturi ipsum, harum assumenda alias nam beatae perspiciatis! Vitae asperiores expedita perspiciatis, doloremque similique quidem fugiat, quam maiores quisquam unde beatae eum, velit eveniet recusandae ducimus. Error deleniti nobis praesentium!"
                            </textarea>
                            <textarea class="form-control review-textarea-2 review-textarea" 
                             rows="8">Eliodor Abel

                            "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Vitae asperiores expedita perspiciatis, doloremque similique quidem fugiat, quam maiores quisquam unde beatae eum, Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vitae aspernatur explicabo suscipit optio illo. Molestias ex perferendis, consequuntur officiis illum ab ratione dignissimos, omnis earum atque non ad voluptatem? At! velit eveniet recusandae ducimus. Error deleniti nobis praesentium!"</textarea>
                        </div>
                    </form>
                </div>
                <hr>
                <div class="movie-user-review">
                    <form>
                        <h1 class="colour-primary">Leave a review:</h1>
                        <textarea class="form-control review-textarea-1 review-textarea" rows="8"></textarea>
                        <div class="form-group row">
                          <div class="col-sm-10">
                            <button type="submit" class="btn btn-customised colour-primary">Submit review</button>
                          </div>
                        </div>
                      </form>
                    
                </div>
                <hr>

            </section>

        </div>

    </section>
    <!-- </section> -->

    <!-- </div> -->









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