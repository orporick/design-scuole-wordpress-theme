<?php
// Attenzione, non c'e' controllo se i posts sono vuoti.

$home_show_events = dsi_get_option("home_show_events", "homepage");



// Recupero l'id della categoria Avvisi. Forse c'e' un modo piu'
// semplice?
$avvisi = [];
$articoli = [];
$termid = 0;
$tipologie_notizie = dsi_get_option("tipologie_notizie", "notizie");


// Se nelle impostazioni ho selezionato anche gli eventi, raccolgo gli ultimi 3
if ($home_show_events == "true_event") {
            $args = array('post_type' => 'evento',
                'posts_per_page' => 3,
                'meta_key' => '_dsi_evento_timestamp_inizio',
                'orderby'   =>  array('meta_value' => 'ASC', 'date' => 'ASC'),
                'meta_query' => array(
                    array(
                        'key' => '_dsi_evento_timestamp_inizio'
					),
					// Se l'evento e' finito, non lo mostro piu'
                    array(
                        'key' => '_dsi_evento_timestamp_fine',
                        'value' => time(),
                        'compare' => '>=',
                        'type' => 'numeric'
                    )
                )
			);
			// Creo un unico array, potenzialmente di 6 oggetti.
			$posts2 = get_posts($args);
}


$numpost = sizeof($posts2);
foreach ( $tipologie_notizie as $id_tipologia_notizia ) {
        $tipologia_notizia = get_term_by("id", $id_tipologia_notizia, "tipologia-articolo");
        if ($tipologia_notizia->name == "Avvisi"){
			$termid = $tipologia_notizia->term_id;
			$args = array('post_type' => 'post',
				'posts_per_page' => 3,
				'tax_query' => array(
				array(
					'taxonomy' => 'tipologia-articolo',
					'field' => 'term_id',
					'terms' => $termid,
					),
				),
			);
		$avvisi = get_posts($args);
		} else {
			$args = array('post_type' => 'post',
                    'posts_per_page' => 6-$numpost,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'tipologia-articolo',
                            'field' => 'term_id',
                            'terms' => $tipologia_notizia->term_id,
                        ),
                    ),
                );
        $posts = get_posts($args);
		$articoli = array_merge($articoli, $posts);
		}
}


//Ordino per data del post
$articoli = array_merge($articoli, $posts2);
usort($articoli, function($post_a, $post_b) {
	return $post_b->post_date <=> $post_a->post_date;
});

// Aggiungo gli annunci
//$posts = array_merge($avvisi,$articoli);
?>



    <section class="section bg-white py-2 py-lg-3 py-xl-5">
    <div class="container">
        <div class="row variable-gutters">
                <?php

                foreach ( $avvisi as $post ) {
					if($post) {
						?>
                        <div class="col-lg-4 mb-4">
                            <?php
                                get_template_part("template-parts/single/card-marinelli-avviso");
                            ?>
                        </div><!-- /col-lg-4 -->
                        <?php
                    }
                }
                foreach ( $articoli as $post ) {
					if($post) {
						?>
                        <div class="col-lg-4 mb-4">
                            <?php
                            if ($post->post_type == "evento")
                                get_template_part("template-parts/evento/card");
							else
                                get_template_part("template-parts/single/card-marinelli");
                            ?>
                        </div><!-- /col-lg-4 -->
                        <?php
                    }
                }
            ?>
        </div><!-- /row -->

    </div><!-- /container -->
        <?php
        $landing_url = dsi_get_template_page_url("page-templates/notizie.php");
        if($landing_url) {
            ?>
            <div class="text-center mt-4">
                <a class="text-underline" href="<?php echo $landing_url; ?>"><strong><?php _e("Scopri di più", "design_scuole_italia"); ?></strong></a>
            </div>
            <?php
        }
        ?>
</section><!-- /section --><?php

