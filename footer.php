<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Custom
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'custom' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'custom' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'custom' ), 'custom', '<a href="https://stylothemes.com">Omprakash</a>' );
				?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script>
	var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
	var page = 2;
	jQuery(function($)){
		//init isotope
		var $grid = $('.grid');
		$grid.isotope({
			itemSelector: '.grid-item',
			percentPosition: true,

		});
		jQuery('body').on('click', '.loadmore', function(e){

			var data = {
				'action': 'load_posts_by_ajax',
				'page': page,
				'security': '<?php echo wp_create_nonce('load_more_posts'); ?>'
			};
			$.post(ajaxurl, data, function(response){
            if (response != '') {
              var $answer = $(response);

              //append items to grid
              $grid.append($answer)
              .isotope('appended', $answer);

              // layaout on imagesLoaded
              $grid.imagesLoaded(function(){
                $grid.isotope('layout');
              });
              page++;
            } else{
              $('.loadmore').text("No more Post!");
              $('.loadmore').attr("disabled", true);
              $('.loadmore').css("borderColor", "gray");
              $('.loadmore').css("color", "gray");
            }
          });
			e.preventDefault();
		});

	};
</script>
</body>
</html>
