<?php include("includes/header.php");
if (!$session->is_signed_in()) {
    header("Location:login.php");
}
if (empty($_GET['id'])) {
    header("Location:photos.php");
}
$photo = Photo::find_by_id($_GET['id']);

header("Location:photos.php");
if($photo) {
    $photo->soft_delete();
    header("Location:photos.php");
} else {
    header("Location:photos.php");
}
?>
<h1>delete page</h1>
<?php include("includes/footer.php"); ?>
