<?php
require_once "pdo.php";

if ( !isset($_GET['movieID']) and !isset($_GET['tvSeriesID']) ) {
    die("ID parameter missing");
}

// Set movie IMG id variable if GET parameter set, to be used in loading a poster for the movie from local storage
// added onto file name dynamically. Adds empty string if not set.
$imageLink = "";

$movieShowId = "";

if (isset($_GET['movieID'])) {
    $movieShowId = $_GET['movieID'];
} elseif (isset($_GET['tvSeriesID'])) {
    $movieShowId = $_GET['tvSeriesID'];
}



// queries to select either movie or tvSeries that had been passed in by ID
// (we don't know if it's going to be a movie or tvSeries)
// which are then put to $movieShowArr to display
// $stmt = $pdo->prepare('SELECT * FROM movies WHERE movieID=:movieId');
// $stmt->execute(array(
//     ':movieId' => $movieShowId
// ));

// queries to select movie or tv show from database
// first query is to select a movie with genre available, if it's not available
// second query selects movies without genre
// same for 3rd and 4th queries, first check tv series with genre available, if genre is not available
// get tv series without genre
$stmt = $pdo->prepare('SELECT * FROM movies INNER JOIN genreInstance ON genreInstance.movieID=movies.movieID INNER JOIN genres ON genreInstance.genreID=genres.genreID WHERE movies.movieID=:movieId');
$stmt->execute(array(
    ':movieId' => $movieShowId
));

$stmt2 = $pdo->prepare('SELECT * FROM movies WHERE movieID=:movieId');
$stmt2->execute(array(
    ':movieId' => $movieShowId
));

$stmt3 = $pdo->prepare('SELECT * FROM tvSeries INNER JOIN genreInstance ON genreInstance.tvSeriesID=tvSeries.tvSeriesID INNER JOIN genres ON genreInstance.genreID=genres.genreID WHERE tvSeries.tvSeriesID=:tvSeriesId');
$stmt3->execute(array(
    ':tvSeriesId' => $movieShowId
));

$stmt4 = $pdo->prepare('SELECT * FROM tvSeries WHERE tvSeriesID=:tvSeriesId');
$stmt4->execute(array(
    ':tvSeriesId' => $movieShowId
));

$movieShowArr = array();

// add all movies with genre dataset to $fullMoviesArray
while ($dbResults = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    $movieShowArr[] = $dbResults;
}

// add all movies without genre dataset to $fullMoviesArray
while ($dbResults = $stmt2->fetch(PDO::FETCH_ASSOC)) { 
    $movieShowArr[] = $dbResults;
}

// add all tvSeries with genre dataset to $fullMoviesArray
while ($dbResults = $stmt3->fetch(PDO::FETCH_ASSOC)) { 
    $movieShowArr[] = $dbResults;
}

// add all tvSeries without genre dataset to $fullMoviesArray
while ($dbResults = $stmt4->fetch(PDO::FETCH_ASSOC)) { 
    $movieShowArr[] = $dbResults;
}




// used for retrieving other information, such as director
$movieShowIdForJoins = "";
$stmt5;
$movieShowArrWithDirector = array();

// join tables of either movie or tvSeries for director retrieval
if (array_key_exists("movieTitle", $movieShowArr[0])) {
    $movieShowIdForJoins = $movieShowArr[0]['movieID'];
    $stmt5 = $pdo->prepare('SELECT * FROM movies INNER JOIN directedBy ON directedBy.movieID=movies.movieID INNER JOIN directors ON directedBy.directorID=directors.directorID WHERE movies.movieID=:movieId');
    $stmt5->execute(array(
        ':movieId' => $movieShowIdForJoins
    ));
} elseif (array_key_exists("tvSeriesName", $movieShowArr[0])) {
    $movieShowIdForJoins = $movieShowArr[0]['tvSeriesID'];
    $stmt5 = $pdo->prepare('SELECT * FROM tvSeries INNER JOIN directedBy ON directedBy.tvSeriesID=tvSeries.tvSeriesID INNER JOIN directors ON directedBy.directorID=directors.directorID WHERE tvSeries.tvSeriesID=:tvSeriesId');
    $stmt5->execute(array(
        ':tvSeriesId' => $movieShowIdForJoins
    ));
}

// add all tvSeries with genre dataset to $fullMoviesArray
while ($dbResults = $stmt5->fetch(PDO::FETCH_ASSOC)) { 
    $movieShowArrWithDirector[] = $dbResults;
}




if (array_key_exists("movieImageLink", $movieShowArr[0])) {
    $imageLink = $movieShowArr[0]['movieImageLink'];
} elseif (array_key_exists("tvSeriesImageLink", $movieShowArr[0])) {
    $imageLink = $movieShowArr[0]['tvSeriesImageLink'];
}

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
                            if (array_key_exists("movieTitle", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieTitle']);
                            } elseif (array_key_exists("tvSeriesName", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesName']);
                            }
                        ?></span></h1>
                    <div class="movie-stars">
                        <h3 class="card-text-stars movie-stars-number colour-primary"><?php 
                            if (array_key_exists("movieScore", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieScore']);
                            } elseif (array_key_exists("tvSeriesScore", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesScore']);
                            }
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
                            if (array_key_exists("movieTitle", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieTitle']);
                            } elseif (array_key_exists("tvSeriesName", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesName']);
                            }
                        ?></h1>
                        <span class="wishlist-movie-duration colour-3"><?php 
                            if (array_key_exists("movieDuration", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieDuration']);
                            } elseif (array_key_exists("tvSeriesAverageDuration", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesAverageDuration']);
                            }
                            ?>min</span>
                        <span class="colour-3"> | </span>
                        <span class="wishlist-movie-genre colour-3"><?php 
                            // if genre is found, echo it
                            for($x=0; $x<count($movieShowArr); $x++) {
                                if (array_key_exists("genreName", $movieShowArr[$x])) {
                                    echo $movieShowArr[$x]['genreName'] . " ";
                                }
                            }
                        ?></span>
                        <span class="colour-3"> | </span>
                        <span class="wishlist-movie-release-date colour-3">Released: <?php 
                            if (array_key_exists("movieReleaseDate", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieReleaseDate']);
                            } elseif (array_key_exists("tvSeriesReleaseDate", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesReleaseDate']);
                            }
                        ?></span>
                        <br>
                        <h4 style="display: inline;" class="colour-primary"><?php 
                            if (array_key_exists("movieScore", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieScore']);
                            } elseif (array_key_exists("tvSeriesScore", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesScore']);
                            }
                        ?></h4>
                        <i class="fas fa-star fa-1x"></i>
                        <hr>
                        <p class="colour-secondary"><?php 
                            if (array_key_exists("movieDescription", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieDescription']);
                            } elseif (array_key_exists("tvSeriesDescription", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesDescription']);
                            }
                        ?></p>
                    </div>
                </div>

                <!-- movie details -->
                <div class="row movie-details-section">
                    <div class="movie-details colour-primary  col-md-12 mb-3 mb-md-0">
                        <p>Budget: <span class="movie-budget"><?php 
                            if (array_key_exists("movieBudget", $movieShowArr[0])) {
                                // convert budget to millions
                                $budget = $movieShowArr[0]['movieBudget'];
                                if (is_numeric($budget)) {
                                    $budget = $budget / 1000000;
                                }
                                echo $budget;
                            } elseif (array_key_exists("tvSeriesBudget", $movieShowArr[0])) {
                                // convert budget to millions
                                $budget = $movieShowArr[0]['tvSeriesBudget'];
                                if (is_numeric($budget)) {
                                    $budget = $budget / 1000000;
                                }
                                echo $budget;
                            }
                        ?>mln</span> </p>
                    </div>
                    <div class="movie-details colour-primary col-md-12 mb-3 mb-md-0">
                        <p>Director: <span class="movie-director"><?php 
                            if (count($movieShowArrWithDirector) > 0 and array_key_exists("directorFirstName", $movieShowArrWithDirector[0])) {
                                echo htmlentities($movieShowArrWithDirector[0]['directorFirstName']) . " " . htmlentities($movieShowArrWithDirector[0]['directorLastName']);
                            }
                        ?></span></p>
                    </div>
                    <div class=" movie-details colour-primary col-md-12 mb-3 mb-md-0">
                        <p>Duration: <span class="movie-duration"><?php 
                            if (array_key_exists("movieDuration", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieDuration']);
                            } elseif (array_key_exists("tvSeriesAverageDuration", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesAverageDuration']);
                            }
                            ?>min</span></p>
                    </div>
                    <div class="movie-details colour-primary  col-md-12 mb-3 mb-md-0">
                        <p>Release date: <span class="movie-release-date"> <?php 
                            if (array_key_exists("movieReleaseDate", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['movieReleaseDate']);
                            } elseif (array_key_exists("tvSeriesReleaseDate", $movieShowArr[0])) {
                                echo htmlentities($movieShowArr[0]['tvSeriesReleaseDate']);
                            }
                        ?></span></p>
                    </div>
                    <div class="movie-details colour-primary  col-md-12 mb-3 mb-md-0">
                        <p>Genre(s): <span class="movie-genre" ><?php 
                            // if genre is found, echo it
                            for($x=0; $x<count($movieShowArr); $x++) {
                                if (array_key_exists("genreName", $movieShowArr[$x])) {
                                    echo htmlentities($movieShowArr[$x]['genreName']) . " ";
                                }
                            }
                        ?></span></p>
                    </div>
                    <?php
                        if (array_key_exists("tvSeriesSeasonNumber", $movieShowArr[0])) {
                            ?>
                            <div class="movie-details colour-primary movie-seasons col-md-12 mb-3 mb-md-0">
                                <p>Seasons: <span class="movie-seasons-nr"><?php
                                echo htmlentities($movieShowArr[0]['tvSeriesSeasonNumber']);
                            ?></span> </p>
                            </div><?php
                        }

                        if (array_key_exists("tvSeriesEpisodeNumber", $movieShowArr[0])) {
                            ?>
                            <div class="movie-details colour-primary movie-episodes col-md-12 mb-3 mb-md-0">
                                <p>Episodes: <span class="movies-episodes-nr" ><?php 
                                    echo htmlentities($movieShowArr[0]['tvSeriesEpisodeNumber']);
                                ?></span> </p>
                            </div><?php
                        }
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