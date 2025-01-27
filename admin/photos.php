<?php
require_once("includes/header.php");
require_once("includes/sidebar.php");
require_once("includes/content-top2.php");
?>
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Our Gallery</h5>
                </div>
                <div class="card-body">
                    <div class="row gallery">
	                    <?php
	                        $photos = Photo::find_all();
						?>
                        <?php foreach ($photos as $photo): ?>
		                    <div class="col-6 col-sm-6 col-lg-3 p-2">
			                    <div class="position-relative" style="padding-top: 100%;">
				                    <img class="position-absolute top-0 start-0 w-100 h-100 img-thumbnail" style="object-fit: cover;" src="<?= $photo->picture_path(); ?>">
                                    <a href="delete_photo.php?id=<?php echo $photo->id; ?>"  class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">
                                        <i class="bi bi-trash"></i>
                                    </a>
			                    </div>
		                    </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require_once("includes/footer.php");
?>
