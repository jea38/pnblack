<?php
require_once 'connect.php';
require_once 'header.php';

$id = (INT)$_GET['id'];
if ($id < 1) {
    header("location: $url_path");
}
$sql = "Select * FROM posts WHERE id = '$id'";
$result = mysqli_query($dbcon, $sql);

$invalid = mysqli_num_rows($result);
if ($invalid == 0) {
    header("location: $url_path");
}

$hsql = "SELECT * FROM posts WHERE id = '$id'";
$res = mysqli_query($dbcon, $hsql);
$row = mysqli_fetch_assoc($result);

$id = $row['id'];
$title = $row['title'];
$description = $row['description'];
$author = $row['posted_by'];
$time = $row['date'];
$pattern = '/<img\s.*?src=[\'"]([^\'"]+)[\'"].*?>/i';
preg_match($pattern, $description, $matches);
// Extracted image link
$postimage = isset($matches[1]) ? $matches[1] : '';

// Remove the image tag from the description
$descriptionWithoutImage = preg_replace($pattern, '', $description);


echo '<section id="section-3"><div class="container py-5">';
echo"<a href='../../inu.php'><button class='button-colordot'><span>RETURN</span></button></a>";
echo "<div class='title flow'><h1 class='pt-3'>$title</h1>";
echo "<p style='font-size: var(--fs-5);'>$descriptionWithoutImage</p></div>";
echo '<div class="row_1"><div class="profile-cont"><img src="https://i.pinimg.com/564x/40/c7/b5/40c7b514a7684a1ee7c4be6dada100c4.jpg"/>';
echo "<p>Posted by $author</p></div>";
echo "<div class='profile-cont'><p>$time</p></div></div></div>";
echo "<div class='img-cont'><img class='user-select-none pe-none' src='$postimage' alt=''> </div>"
?>


<?php
if (isset($_SESSION['username'])) {
    ?>
    <div class="w3-text-green"><a href="<?=$url_path?>edit.php?id=<?php echo $row['id']; ?>">[Edit]</a></div>
    <div class="w3-text-red">
        <a href="<?=$url_path?>del.php?id=<?php echo $row['id']; ?>"
           onclick="return confirm('Are you sure you want to delete this post?'); ">[Delete]</a></div>
    <?php
}
echo '</section>';


include("footer.php");
