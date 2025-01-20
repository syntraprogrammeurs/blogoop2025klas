<?php
require_once("includes/header.php");
require_once("includes/sidebar.php");
require_once("includes/content-top.php");
?>
<div class="row">
    <?php $posts = Post::find_all_posts(); ?>
    <?php foreach($posts as $post):?>
	<div class="col-12 col-lg-4">
		<div class="card">
			<div class="card-content">
				<img src="./assets/compiled/jpg/motorcycle.jpg" class="card-img-top img-fluid"
				     alt="singleminded">
				<div class="card-body">
					<p class="card-title"><?= $post->title?> - <?= $post->created_at?></p>
					<p class="card-text">
						<?= $post->description;?>
					</p>
				</div>
			</div>
		</div>
	</div>
    <?php endforeach;?>
</div>
<?php
require_once("includes/widget.php");
require_once("includes/footer.php");
?>
