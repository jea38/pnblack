<?php
require_once 'connect.php';
require_once 'header.php';
?>

<section id="landing-section">
          <img loading="lazy" class="column_1 user-select-none pe-none" src="https://i.pinimg.com/736x/04/2f/f4/042ff44fb9c5733c61667685e2455904.jpg" alt="" />
          <div class="column_2">
            <div></div>
            <div class="column_2b">
              <h1 class="d3">
                <b style="color: var();"><?="$site_name"?></b>  <span class='typewriter-text' data-text='[ "Info 📰.", "FAQs ❓." ]'></span>
            </h1>
              <div>
                    <form class="credentials-box" action="search.php" method="GET">
                       <input type="text" name="q" placeholder="Looking for..." required>
                    <button type="submit" class="button-colordot">
                     <span>SEARCH</span>
                     </button>
               </form>
              </div>
            </div>
          </div>
        </section>
      
        <section>
            <div class="blog-card-container">           
                <div class="blog-cards" data-view="grid-view">


<?php
// COUNT
$sql = "SELECT COUNT(*) FROM posts";
$result = mysqli_query($dbcon, $sql);
$r = mysqli_fetch_row($result);
$numrows = $r[0];

$rowsperpage = PAGINATION;
$totalpages = ceil($numrows / $rowsperpage);

$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = (INT)$_GET['page'];
}

if ($page > $totalpages) {
    $page = $totalpages;
}

if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $rowsperpage;

$sql = "SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $rowsperpage";
$result = mysqli_query($dbcon, $sql);

if (mysqli_num_rows($result) < 1) {
    echo '<h2>No post yet!</h2>';
} else {
  while ($row = mysqli_fetch_assoc($result)) {

    $id = htmlentities($row['id']);
    $title = htmlentities($row['title']);
    $des = htmlentities(strip_tags($row['description']));
    $slug = htmlentities($row['slug']);
    $time = htmlentities($row['date']);

    $permalink = "p/".$id ."/".$slug;


   echo'<article class="blog-card">';

     echo'<div class="blog-card__content">';
      echo"<div class='blog-card__category'>$time</div>";
     echo"<h2 class='blog-card__title'><a href='$permalink'>$title</a></h2>";
       echo"<p class='blog-card__description'>";
       echo substr($des, 0, 100);        
        echo"</p>";
         echo"<a class='blog-card-button' href='$permalink'><i class='fa fa-ellipsis-v'></i><span>Read more</span></a>";
      echo"</div></article>";
}

echo "</div></div>";

echo "<center><div class='pagination p12'><ul>";

if ($page > 1) {
    echo "<a href='?page=1'><li><i class='fas fa-angle-double-left'></i></li></a>";
    $prevpage = $page - 1;
    echo "<a class='is-active' href='?page=$prevpage'><li>Previous</li></a>";
}

$range = 5;
for ($x = $page - $range; $x < ($page + $range) + 1; $x++) {
    if (($x > 0) && ($x <= $totalpages)) {
        if ($x == $page) {
            echo "<a><li>$x</li></a>";
        } else {
            echo "<a href='?page=$x'><li>$x</li></a>";
        }
    }
}

if ($page != $totalpages) {
    $nextpage = $page + 1;
    echo "<a class='is-active' href='?page=$nextpage'><li>Next</li></a>";
    echo "<a href='?page=$totalpages'><li><i class='fas fa-angle-double-right'></i></li></a>";
}

echo "</ul></div></center></section>";
 
  

}
include("footer.php");
