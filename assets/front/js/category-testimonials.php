<?php get_header(); ?>

    <section class="comman-banner-section" style="background-image:url('images/testimonial-banner.png');">
        <div class="banner-content">
            <h1>Testimonials</h1>
        </div>
    </section>
    <!-- banner section end -->

    <!-- testimonial section strat -->

    <section class="testimonial-ground-section pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-10 mx-auto">
                    <h2 class="dark-section-heading">Truck Driving School Featured Graduate</h2>
                </div>
            </div>

          <?php $custom_terms = get_terms('category');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
foreach($custom_terms as $custom_term) {
    wp_reset_query();
    $args = array('post_type' => 'post',
         'paged' => $paged,
    'posts_per_page' => 4,
    'category'     => 'Testimonials',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $custom_term->slug,
            ),
        ),
     );

     $loop = new WP_Query($args);
    
     if($loop->have_posts()) {
        if($custom_term->name == 'Testimonials'){ 

            $i=0;
             while($loop->have_posts()) : $loop->the_post();
                if($i% 2 == 0){  ?>

	             <div class="row py-4">
	                <div class="col-12 col-md-4">
	                    <div class="testimonial-ground-thumb">
                            <?php  $img_attribs = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' ); // returns an array
                                if( $img_attribs ) {
                                ?> 
                                <img src="<?php echo $img_attribs[0]; ?>" width="<?php echo $img_attribs[1]; ?>" height="<?php echo $img_attribs[2]; ?>">
                                <?php }else{ ?>
                                    <img src="http://178.128.177.194/truckschools/wp-content/themes/Nexus-child/custom/images/user.jpg">
                                <?php } ?>
	                        <?php //the_post_thumbnail( 'thumbnail' ); ?>
	                    </div>
	                </div>
	                <div class="col-12 col-md-8">
	                    <div class="testimonial-ground-info">
	                        <img src="http://178.128.177.194/truckschools/wp-content/themes/Nexus-child/custom/images/quote.png" class="quote">
	                        <p>
	                          <?php the_excerpt(); ?>
	                        </p>  
	                        <a href="<?php echo get_permalink(); ?>" class="testimonial-read-more">Read More</a>
	                        <h4 class="testimonial-ground-name"><?php echo get_the_title();?></h4>
	                    </div>
	                </div>                
	            </div>

                <?php } else{ ?>


		              <div class="row py-4 flex-md-row flex-column-reverse">
		                <div class="col-12 col-md-8">
		                    <div class="testimonial-ground-info">
		                        <img src="http://178.128.177.194/truckschools/wp-content/themes/Nexus-child/custom/images/quote.png" class="quote">
		                        <p>
		                              <?php the_excerpt(); ?>
		                        </p>
		                      <a href="<?php echo get_permalink(); ?>" class="testimonial-read-more">Read More</a>
		                        <h4 class="testimonial-ground-name"><?php echo get_the_title();?></h4>
		                    </div>
		                </div>
		                <div class="col-12 col-md-4">
		                    <div class="testimonial-ground-thumb">
                                 <?php  $img_attribs = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' ); // returns an array
                                        if( $img_attribs ) {
                                        ?> 
                                        <img src="<?php echo $img_attribs[0]; ?>" width="<?php echo $img_attribs[1]; ?>" height="<?php echo $img_attribs[2]; ?>">
                                        <?php }else{ ?>
                                            <img src="http://178.128.177.194/truckschools/wp-content/themes/Nexus-child/custom/images/user.jpg">
                                        <?php } ?>
		                        <?php // the_post_thumbnail( 'thumbnail' ); ?>
		                    </div>
		                </div>                
		            </div>
                <?php  }
            $i++;
        endwhile; 
           //post_pagination();
           ?>
        <section class="pagination-area">
           <div class="container">
                <div class="row py-3">
                    <div class="col-12 col-md-12">
       					<?php  wp_pagenavi(); ?>
       				</div>
       			</div>
       		</div>
       	</section>

       <?php  }
    }
} ?>
          
        
        </div>
    </section>
        
<?php get_template_part( 'include/info_banners', 'page' ); ?>
<?php get_footer(); ?>