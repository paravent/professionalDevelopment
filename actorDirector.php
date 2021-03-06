<?php

require_once "pdo.php";
require_once "functions.php";

if (!isset($_GET['actorID']) and !isset($_GET['directorID'])) {
    die("ID parameter missing");
}
$actorID = "";
$directorID = "";

if (isset($_GET['actorID'])) {
    $actorID = $_GET['actorID'];
}

if (isset($_GET['directorID'])) {
    $directorID = $_GET['directorID'];
}

$actorOrDirectorArr = getActorOrDirector($pdo, $actorID, $directorID);

$imageLink = getImageLink($actorOrDirectorArr);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Movie page</title>
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
                    <a class="nav-link colour-primary" href="login.php">Login</a>
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
    <section class="movie">

        <div class="container">
            <!-- movie header -->
            <section class="movie-header">
                <div class="row justify-content-between">
                    <h1 class="colour-primary"><span class="search-name"><?php
                        echo displayEitherActorOrDirectorDataItem($actorOrDirectorArr, "actorFirstName", "directorFirstName") . " ";
                        echo displayEitherActorOrDirectorDataItem($actorOrDirectorArr, "actorLastName", "directorLastName");
                    ?></span></h1>
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
                                echo '<img class="card-img-top" src="img/actorDirectorImages' . $imageLink .  '" onerror="this.src=\'img/personImg.png\'" alt="Card image cap">';
                            } else {
                                echo '<img class="card-img-top" src="img/personImg.png" alt="poster">';
                            }
                            ?>
                        <!-- </div> -->
                    </div>
                    <!-- <div class="movie-instance-body">

                </div> -->
                    <div class="movie-instance-body col-md-8 mb-3 mb-md-0">
                        <h3 class="colour-primary"><?php
                        echo displayEitherActorOrDirectorDataItem($actorOrDirectorArr, "actorFirstName", "directorFirstName") . " ";
                        echo displayEitherActorOrDirectorDataItem($actorOrDirectorArr, "actorLastName", "directorLastName");
                    ?></h3>
                        <hr>
                        <p class="colour-secondary"> 
                        <?php
                        echo displayEitherActorOrDirectorDescription($actorOrDirectorArr, "actorBio", "directorOscarsWon");
                    ?> </p>
                    </div>
                </div>

                <!-- movie details -->
                <div class="row movie-details-section">
                    <div class="movie-details actor-details colour-primary movie-budget col-md-12 mb-3 mb-md-0">
                        <p>Date of birth: <span class="actor-date-of-birth"><?php 
                            echo displayEitherActorOrDirectorDataItem($actorOrDirectorArr, "actorDateOfBirth", "directorDateOfBirth");
                        ?></span> </p>
                    </div>
                    <div class="movie-details actor-details colour-primary movie-budget col-md-12 mb-3 mb-md-0">
                        <p>Place of birth: <span class="actor-place-of-birth"><?php 
                            echo displayEitherActorOrDirectorDataItem($actorOrDirectorArr, "actorPlaceOfBirth", "directorPlaceOfBirth");
                        ?></span> </p>
                    </div>
                    <div class="movie-details actor-details colour-primary movie-budget col-md-12 mb-3 mb-md-0">
                        <p>Known for: <span class="actor-known-for"><?php 
                            echo displayEitherActorOrDirectorDataItem($actorOrDirectorArr, "actorsKnownFor", "directorKnownFor");
                        ?></span> </p>
                    </div>
                    
                </div>

     
                






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