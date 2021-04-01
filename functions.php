<?php

// get HTML for movie/tvSeries item to display on index page
// $param1 : $movieTvShow array of movies and tvSeries
// returns : HTML for individual movie/tvSeries item to display on index.php
function createMovieTvShowArtefact($movieTvShow) {
    ob_start();
    ?>
    <div class="col-md-3 mb-3 mb-md-0 card-container">
        <div class="card h-100">
                <?php 
                    // generate image link for use on line 238 just below
                    $imageLink = "";
                    if (array_key_exists("movieID", $movieTvShow)) {
                        $imageLink = $movieTvShow['movieImageLink'];
                    } elseif (array_key_exists("tvSeriesID", $movieTvShow)) {
                        $imageLink = $movieTvShow['tvSeriesImageLink'];
                    }

                    if ($imageLink != "") {
                        $imageLink = "img/movieTvShowImages" . $imageLink;
                    } else {
                        $imageLink = "img/movieImg.png";
                    }
                ?>
            <img class="card-img-top" src="<?php echo $imageLink; ?>" onerror="this.src='img/movieImg.png'" alt="Card image cap">
            <div class="card-body">
                <a href="movie.php?movieID=<?php 
                if (array_key_exists("movieID", $movieTvShow)) {
                    echo htmlentities($movieTvShow['movieID']);
                } elseif (array_key_exists("tvSeriesID", $movieTvShow)) {
                    echo htmlentities($movieTvShow['tvSeriesID']);
                }
                ?>">
            <article id="main-movie">
            
            <h2><?php 
                if (array_key_exists("movieTitle", $movieTvShow)) {
                    echo htmlentities($movieTvShow['movieTitle']);
                } elseif (array_key_exists("tvSeriesName", $movieTvShow)) {
                    echo htmlentities($movieTvShow['tvSeriesName']);
                } ?>
            </h2>
            <p><?php 
                if (array_key_exists("movieScore", $movieTvShow)) {
                    echo htmlentities($movieTvShow['movieScore']);
                } elseif (array_key_exists("tvSeriesScore", $movieTvShow)) {
                    echo htmlentities($movieTvShow['tvSeriesScore']);
                } ?>
                <i class="fas fa-star"></i>
            </p>
            <p><?php 
                if (array_key_exists("movieDescription", $movieTvShow)) {
                    echo htmlentities($movieTvShow['movieDescription']);
                } elseif (array_key_exists("tvSeriesDescription", $movieTvShow)) {
                    echo htmlentities($movieTvShow['tvSeriesDescription']);
                }
                ?>
            </p>
            </a>
                <p class="card-text">
                <button class="btn"><i class="fas fa-plus"></i> Add to wishlist </i></button>
            </p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}



// get movies or tvSeries without or with genre (if genre exists in the DB for that particular item)
// $params: $pdo - database connection;
// $params: $movieShowId - movie or tvSeries ID
// $returns: $movieShowArr array of a specific movie or tvSeries data items (title, budget, duration, etc.)
function getMoviesShowsWithOrWithoutGenre($pdo, $movieShowId) {
    $movieShowArr = array();

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

    return $movieShowArr;
}



// get movies or tvSeries with director if it exists in the DB
// $param1 : database connection
// $param2 : $movieShowArr array of specific movie or show data items retrieved from the DB (this function uses movieID or tvSeriesID from this array)
// returns: $movieShowArrWithDirector array of a specific movie or tvSeries data items with a director if it exists in the DB for that specific movie/show
function getMoviesShowsWithDirector($pdo, $movieShowArr) {
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

    return $movieShowArrWithDirector;
}



// get imageLink of a specific movie/tvSeries
// $param1 : $movieTvShow array of data items for specific movie/tvSeries
// returns: $imageLink relative link to an image taken from the DB
function getImageLink($movieShowArr) {
    $imageLink = "";
    if (array_key_exists("movieImageLink", $movieShowArr[0])) {
        $imageLink = $movieShowArr[0]['movieImageLink'];
    } elseif (array_key_exists("tvSeriesImageLink", $movieShowArr[0])) {
        $imageLink = $movieShowArr[0]['tvSeriesImageLink'];
    }

    return $imageLink;
}



// check whether it's a movie or tvSeries and display the appropriate key value
// $param1 : $movieShowArr movie or tvSeries data items array
// $param2 : $movieKey key to display if it's a movie
// $param3 : $tvSeriesKey key to display if it's a tvSeries
function displayEitherMovieOrSeriesDataItem($movieShowArr, $movieKey, $tvSeriesKey) {
    if (array_key_exists($movieKey, $movieShowArr[0])) {
        echo htmlentities($movieShowArr[0][$movieKey]);
    } elseif (array_key_exists($tvSeriesKey, $movieShowArr[0])) {
        echo htmlentities($movieShowArr[0][$tvSeriesKey]);
    }
}



// display tvSeries season and episode numbers if they exist
// $param1 : $movieTvShow specific tvSeries data items array
// returns: HTML to display Seasons and Episodes numbers
function displaySeriesSeasonAndEpisodeNumber($movieShowArr) {
    ob_start();
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
    return ob_get_clean();
}



// echo budget for movie or tvSeries
// $param1 : $movieTvShow specific movie or tvSeries data items array
function displayBudget($movieShowArr) {
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
}



// display director if exists for the specific movie/tvSeries
// $param1 : $movieShowArrWithDirector specific movie/tvSeries data items array with director table included
function displayDirector($movieShowArrWithDirector) {
    if (count($movieShowArrWithDirector) > 0 and array_key_exists("directorFirstName", $movieShowArrWithDirector[0])) {
        echo htmlentities($movieShowArrWithDirector[0]['directorFirstName']) . " " . htmlentities($movieShowArrWithDirector[0]['directorLastName']);
    }
}



// echo genres if they exist for specific movie/tvSeries
// $param1 : $movieShowArr specific movie/tvSeries data items array
function displayGenres($movieShowArr) {
    // if genre is found, echo it
    for($x=0; $x<count($movieShowArr); $x++) {
        if (array_key_exists("genreName", $movieShowArr[$x])) {
            echo $movieShowArr[$x]['genreName'] . " ";
        }
    }
}

?>