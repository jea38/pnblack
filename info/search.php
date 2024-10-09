<?php
require_once 'connect.php';
require_once 'header.php';

if (isset($_GET['q'])) {
    $q = mysqli_real_escape_string($dbcon, $_GET['q']);

    $sql = "SELECT * FROM posts WHERE title LIKE '%{$q}%' OR description LIKE '%{$q}%'";
    $result = mysqli_query($dbcon, $sql);

    if (mysqli_num_rows($result) < 1) {
           echo "<section id='landing-section'><div class='column_2'><div class='column_2b'><h1>Uh Oh... nothing found.</h1><h2> Kindly check your spelling or search for something else.</h2>
           <div><a href='inu.php'><button data-type='inverted' class='button'><div class='button__bg'></div><p>RETURN</p></button></a></div>
 </div></div></section>";
    } else {
        
      echo " <section id='landing-section'><div class='column_2'><div class='column_2b'><h1>Showing Results for '$q' </h1>
           <div><a href='inu.php'><button data-type='inverted' class='button'><div class='button__bg'></div><p>RETURN</p></button></a></div>
 </div></div></section>";

      while ($row = mysqli_fetch_assoc($result)) {

        $id = htmlentities($row['id']);
        $title = htmlentities($row['title']);
        $des = htmlentities(strip_tags($row['description']));
        $slug = htmlentities(strip_tags($row['slug']));
        $time = htmlentities($row['date']);

        $permalink = "p/".$id ."/".$slug;

        
        echo'<section><div class="blog-card-container"><div class="blog-cards" data-view="list-view">';
   echo'<article class="blog-card">';
     echo'<div class="blog-card__content">';
      echo"<div class='blog-card__category'>$time</div>";
     echo"<h2 class='blog-card__title'><a href='$permalink'>$title</a></h2>";
       echo"<p class='blog-card__description'>";
       echo substr($des, 0, 100);        
        echo"</p>";
         echo"<a class='blog-card-button' href='$permalink'><i class='fa fa-ellipsis-v'></i><span>Read more</span></a>";
      echo"</div></article>";
      echo"</div></div></section>";

      }

    }
}
include("footer.php");
