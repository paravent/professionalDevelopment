<?php

// returns HTML with PHP to display single movie or tv show artefact on the main page
// takes in a single movie or tv show array of parameter values
// In the format:
// Array ( [movieID] => 6415 [movieTitle] => Three Kings [movieReleaseDate] => 27/09/1999 [movieScore] => 6.6 
// [movieDuration] => 114 [movieRevenue] => 108000000 
// [movieDescription] => A group of American soldiers stationed in Iraq at the end of the Gulf War find a map they believe will take them to a huge cache 
// of stolen Kuwaiti gold hidden near their base, and they embark on a secret mission that's destined to change everything. 
// [movieBudget] => 75000000 [movieImageLink] => )
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
                <h5 class=""> <a href="">Interstellar</a></h5>
                <a href="moviePage.php?movieID=<?php 
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
                <p class="card-text-stars">4.5</p>
                <i class="fas fa-star"></i>
                <button class="btn"><i class="fas fa-plus"></i> Add to wishlist </i></button>
            </p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

?>