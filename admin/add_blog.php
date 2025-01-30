<?php
// Inclusie van de benodigde bestanden
require_once("includes/header.php");
require_once("includes/sidebar.php");
require_once("includes/content-top.php");

global $database; // Zorg ervoor dat je toegang hebt tot het database-object

// Controleer of er een melding in de sessie staat
$the_message = "";
if (isset($_SESSION['the_message'])) {
    $the_message = $_SESSION['the_message'];
    unset($_SESSION['the_message']); // Verwijder de melding na ophalen
}

// Controleer of het formulier is ingediend
if (isset($_POST['submit'])) {
    $blog = new Blog();
    $photo = new Photo();

    // Controleer of de gebruiker is ingelogd
    if (!isset($_SESSION['user_id'])) {
        $the_message = "Je moet ingelogd zijn om een blogpost te maken.";
    } else {
        $blog->author_id = $_SESSION['user_id']; // Zet de ingelogde gebruiker als auteur
        $photo_id = 0; // Standaard op nul indien geen afbeelding wordt geüpload

        // **Foto uploaden en opslaan**
        if (!empty($_FILES['photo']['name'])) {
            $photo->title = trim($_POST['title']);
            $photo->description = trim($_POST['description']);
            $photo->set_file($_FILES['photo']);

            // Probeer de foto op te slaan
            if ($photo->save() === false) {
                $the_message = "De foto kon niet worden opgeslagen.";
            } else {
                $the_message = "Foto werd opgeslagen";
            }

            $photo_id = $database->get_last_insert_id();
        }

        // **Blogpost opslaan**
        $blog->photo_id = $photo_id; // Koppel de foto aan de blogpost
        $blog->title = trim($_POST['title']);
        $blog->description = trim($_POST['description']);

        // Probeer de blogpost op te slaan
        if ($blog->save() === true) {
            $the_message = "De blogpost kon niet worden opgeslagen.";
        } else {
            $the_message = "Blogpost werd opgeslagen";
        }

        // Zet de melding in de sessie
        $_SESSION['the_message'] = $the_message;
    }
}
?>

<?php if (!empty($the_message)): ?>
	<!-- Weergave van de succes- of foutmelding -->
	<div class="alert alert-success alert-dismissible show fade">
        <?php echo $the_message; ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>

<!-- Formulier voor het toevoegen van een blogpost -->
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Add Blog</h4>
	</div>
	<div class="card-content">
		<div class="card-body">
			<form class="form form-vertical" method="post" enctype="multipart/form-data">
				<div class="form-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group has-icon-left">
								<label for="title-icon">Title</label>
								<div class="position-relative">
									<input type="text" class="form-control" placeholder="Enter title" id="title-icon" name="title" required>
									<div class="form-control-icon">
										<i class="bi bi-type"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group has-icon-left">
								<label for="description-icon">Description</label>
								<div class="position-relative">
									<input type="text" class="form-control" placeholder="Enter description" id="description-icon" name="description" required>
									<div class="form-control-icon">
										<i class="bi bi-card-text"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group has-icon-left">
								<label for="photo">Upload Photo</label>
								<div class="position-relative">
									<input type="file" class="form-control" id="photo" name="photo" accept="image/*">
									<div class="form-control-icon">
										<i class="bi bi-cloud-upload"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 d-flex justify-content-end">
							<input type="submit" name="submit" class="btn btn-primary me-1 mb-1" value="Submit">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
// Inclusie van widgets en de footer
require_once("includes/widget.php");
require_once("includes/footer.php");
?>
