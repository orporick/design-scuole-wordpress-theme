<?php
global $post, $autore;
$autore = get_user_by("ID", $post->post_author);

$image_id= get_post_thumbnail_id($post);
$image_url = get_the_post_thumbnail_url($post, "vertical-card");

$excerpt =  dsi_get_meta("descrizione", "", $post->ID);
if(!$excerpt)
    $excerpt = get_the_excerpt($post);
?>

<div class="card card-bg card-vertical-thumb bg-white card-thumb-rounded">
	<div class="card-body">
		<div class="card-content">
			<h3 class="h5"><a href="<?php echo get_permalink($post); ?>"><?php echo get_the_title($post); ?></a></h3>
			<p><?php echo $excerpt; ?></p>
		</div>
		<?php if($image_url) { ?>
			<div class="card-thumb">
            	<?php dsi_get_img_from_id_url( $image_id, $image_url ); ?>
			</div>
		<?php  } ?>
	</div><!-- /card-body -->
	<div class="card-event-dates">
		<div class="card-event-dates-icon">
			<svg class="icon svg-news"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-news"></use></svg>
		</div><!-- /card-event-dates-icon -->
		<div class="card-event-dates-content">
			<p class="font-weight-normal"><?php
                echo date_i18n('d F Y', $post->post_date);?></p>
		</div><!-- /card-event-dates-content -->
	</div><!-- /card-event-dates -->

</div><!-- /card --><?php
