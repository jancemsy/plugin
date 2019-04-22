<?php
/**
* ProCyber Category Template
*/

define( 'WP_USE_THEMES', false ); get_header(); 
 
?> 

 

<div class="content"><!-- shop-v10 was, <header class="archive-header"> Fixed page column problem-->
        <div class="entry"><!-- shop-v10, fixes the problem of no white gap around text content-->

<?php 
// Check if there are any posts to display
if ( have_posts() ) { 
    
    ob_start();
     
    ?>
 

        <h1><?php single_cat_title(''); ?></h1>

        <?php
        // Display optional category description
        if ( category_description() ) : ?>
            <div class="archive-meta">
                <?php echo category_description(); ?> 
            </div>
        <?php endif; ?>



        <?php
        $category = get_category( get_query_var( 'cat' ) );
        $category_id = $category->cat_ID;


          //show the bottom only if this is tier3/sub category  
          $categories = get_categories(array( 'parent' => $category_id ) ); 
          
          $is_tier3 = (count($categories) > 0) ? false : true ; //if it has more subcategory, this is not the end category. 
 

          if($is_tier3){  
                 query_posts($query_string.'&orderby=title&order=ASC');  
                // The Loop START  
                while ( have_posts() ) : the_post(); ?> 
                  <div class="entry-content ourcustom-entry" itemprop="text">
                        <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>                

                         <a class="entry-image-link" href="<?php the_permalink() ?>"  >
                                 <?php  the_post_thumbnail('thumbnail'); ?>
                         </a> 


                         <p> <?php the_content( "[Read more...]", true ); ?> </p> 
                 </div>
                <?php endwhile;  
          }else{ //tier 2 categories and its description 

                $categories = get_categories( array(
                    'parent'      =>  $category_id,
                    'orderby' => 'name',
                    'order'   => 'ASC'
                ) );
                
                foreach( $categories as $category ) {

                    $image = z_taxonomy_image_url($category->term_id,'thumbnail',true);
                    //https://i.imgur.com/BPp3kcX.png
                    //var_dump($category); exit();
                    /*
                    $category_link = sprintf( 
                        '<a href="%1$s" alt="%2$s">%3$s</a>',
                        esc_url( get_category_link( $category->term_id ) ),
                        esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
                        esc_html( $category->name )
                    );*/

                    $category_link = get_category_link( $category->term_id ) ;


                    ?>
                    <div class="entry-content ourcustom-entry" itemprop="text">
                        <h4><a href="<?php echo $category_link ?>" rel="bookmark" 
                        title="Permanent Link to <?php echo $category->name ?>"><?php echo $category->name; ?></a></h4>                

                         <a class="entry-image-link" href="<?php echo $category_link ?>"  >
                                    <img src="<?php echo $image; ?>" />
                         </a>  
                         
                         <p> <?php echo $category->description; ?> </p> 
                     </div>

                     <?php 

                    
                    /*
                    echo '<p>' . sprintf( esc_html__( 'Category: %s', 'textdomain' ), $category_link ) . '</p> ';
                    echo '<p>' . sprintf( esc_html__( 'Description: %s', 'textdomain' ), $category->description ) . '</p>';
                    echo '<p>' . sprintf( esc_html__( 'Post Count: %s', 'textdomain' ), $category->count ) . '</p>';
                    */
                } 

          }


    }else{ ?> 
                        <p>Sorry, no posts matched your criteria.</p>  
    <?php } // The Loop END ?>


        </div>
</div>


<?php



$data = ob_get_contents();      


ob_end_clean();     


$data = str_replace("<p></p>","HELLOW REPLACING THE P HERE JAMES ", $data);

echo $data;

get_sidebar(); ?>
<?php get_footer(); ?>