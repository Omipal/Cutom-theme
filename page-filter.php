<?php 
/*
  Template Name: Filter Page
*/
get_header();
?>
<section class="entry-contents">
<div class="container">
  <div class="category-filter">
    <?php $categories = get_categories(); ?>
    <form action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
        <select name="category" class="categories">
          <?php
          foreach ($categories as $categorie) { ?>
          <option value="<?php echo $categorie->term_id ?>"><?php echo $categorie->cat_name ?></option> 
            <?php 
            
          }  
          ?>
        </select>
        <input type="hidden" name="action" value="myfilter">
        <button id="filter-cats">Applay Filter</button>
    </form>
      </div>
      <div class="grid-container">
        <?php 
          $ajaxposts = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 3,
            'order_by' => 'date',
            'order' => 'desc'

          ]);
          if($ajaxposts->have_posts()):
            while ($ajaxposts->have_posts()):$ajaxposts->the_post();
            ?>
              <div class="grid-item">
                <h4><?php the_title(); ?></h4>
                <span class="post-cats">
                  <?php 
                    the_terms(get_the_ID(), 'category', 'Categories: ', ' / ');
                  ?>
                </span>
                <p class="contents"><?php wp_trim_words(the_content(), 55); ?></p>
              </div>
            <?php

            endwhile; 
            wp_reset_postdata();  
          endif;
        ?>
      </div>
  </div>
</section>

<script>
  jQuery(document).ready(function(){
    jQuery("#filter-cats").click(function(){
      var filter = jQuery("#filter");

      jQuery.ajax({
        url:filter.attr("action"),
        data:filter.serialize(),
        type:filter.attr("method"),
        beforeSend: function(xhr){
          filter.find("button").text("Procesing...");
          console.log(filter.serialize());
        },
        success:function(data){
     
           filter.find("button").text("Applay Filter");
           jQuery(".grid-container").html(data);
        }
      });
      return false;
    })
  })
</script>

<?php get_footer(); ?>