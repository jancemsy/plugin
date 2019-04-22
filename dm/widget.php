<?php
 
class DM_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'dm_widget',
			'description' => 'DM Widget is awesome',
		);
		parent::__construct( 'DM_widget', 'DM Silo Sidebar Widget', $widget_ops );
	}


	private function getChildCategories($categories, $parentID){
		$child_categories = array();
		$duplicate = array(); 

		//die($parentID ."XXXXXXXX");

		foreach($categories as $category){ 
				if($category->parent == $parentID && !isset($duplicate[$category->cat_ID])  ){   
					$duplicate[$category->cat_ID] = 1;
					$child_categories[]  = $categories[$category->cat_ID];    
				}
			}

			//var_dump($child_categories);

			//exit();

		return $child_categories; 
	}

	

function search($search, $subject){
    $pos = strpos($subject, $search); 
    return $pos !== false ? true : false;  
}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {



		?>
		<textarea id='dummy_breadcrumb' style="display:none;" >
		<div class="breadcrumb">You are here: 
		
			<span class="breadcrumb-link-wrap" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem"> 
			<a href="/" itemprop="item"><span itemprop="name">Home</span></a></span> &gt;  
			<span class='here'></span> 
 
        </div></textarea>
		
		
		<?php 


		// outputs the content of the widget
		echo "<div class=\"silo-menu\">";    
		
		$target = $_SERVER['REQUEST_URI'];   
		$target = str_replace("/category","",$target); 		
		$u = explode("/",$target); 

		$names = array(); 
		$check_last_key = "";
	    foreach($u as $item){
           if( !empty($item) ) {
			   $names[] = $item; 
			   $check_last_key = $item;
		   }
		}


		$tier = 0;
		if( count($names)  === 0 ){  
		}else{ 
			$tier = count($names);  	 
		}

		$categories = get_categories(array('hide_empty' => false));    
		$search_key_init = "";
		$search_key = "";
		if(isset($_GET["s"])){ 
			$search_key_init = strtolower($_GET["s"]);
		}


		foreach($categories as $category) { 
			$categories[$category->cat_ID] = $category;
			$categories[$category->slug] = $category; 
			$_cat = strtolower($category->name);

			if($search_key  !=""){
				if( search($search_key,  $_cat)  ){
				   	 $search_key = $category->slug;
				}
			} 
		} 


	
		
		//check for match in last key 
		if(isset($categories[$check_last_key])){
			$names = array($check_last_key);
			$tier = 1;
		}
		

		if(isset($_GET["s"])){  
		  
		  $tier = 111;
		  
		}
  

		switch($tier){  
		case 111: 

			while ( have_posts() ) : the_post(); ?>

					<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
					
			<?php endwhile; 
		break; 

		case 1: 

		                $target = $names[0];

						//header 
						$category = $categories[$target]; 
						


						if( !@isset($category->name)  ){
							echo "Debugging: there is no $target category in this page. please see to it that 
							you have the correct settings like the other category pages. <br/> <hr size=1 />";

							echo "debugging: target - {$names[0]}:  <br/>  Search for categories in : <br/>";

							foreach($categories as $item){
							   echo " ----  ".$item->name."<br/>	"; 
							} 
						}else{ 
 

							        $silo_start = "<h2>";$silo_end = "</h2>";

									if($category->parent != 0){ //check if there is parent 
										$parent_category = $categories[$category->parent];   
										$link = "/category/".$parent_category->slug."/"; 
										echo "$silo_start <a href='$link'>".$parent_category->name."</a>$silo_end";  	  
										$silo_start = "<h3>";$silo_end = "</h3>";
									}
									

									$link = "/category/".$category->slug."/"; 
									echo "$silo_start <a href='$link'>".$category->name."</a> $silo_end";  
 

						
 
									$childCategories =  $this->getChildCategories($categories, $category->cat_ID);  

									
									if(count($childCategories) != 0){ //with child categories 
										foreach($childCategories as $cat){ 
											$link = "/category/".$cat->slug; 
											echo "<h3> <a href='$link'>".$cat->name."</a> </h3>";   
										}
									}else{ //display the post links  
										$posts = get_posts(array("category" => $category->cat_ID )) ;   
										echo "<ul>";
										
										foreach($posts as $p){
												$permalink = get_permalink($p);
												echo "<li><a href='$permalink'>".$p->post_title."</a>";  
										}  	 
										echo "</ul>";	
									}
									
			
					} 


 
 
		break;
 

		//deeper 
		case 3:    
		                                    $silo_start = "<h2>";$silo_end = "</h2>"; 

											$parent_category = $categories[$names[0]];   
											$link = "/category/".$parent_category->slug."/"; 
											echo "$silo_start <a href='$link'>".$parent_category->name."</a> $silo_end";  	
											 

											$silo_start = "<h3>";$silo_end = "</h3>"; 

											$category = $categories[$names[1]];   
											$link = "/category/".$category->slug."/"; 
											echo "$silo_start <a href='$link'>".$category->name."</a> $silo_end";  	
 

			                                $thispost = $names[2];   

			 								//related post link
											$posts = get_posts(array("category" => $category->cat_ID )) ;   
											echo "<ul>";
											foreach($posts as $p){ 
															 $permalink = get_permalink($p); 
															 $class = ($p->post_name == $thispost) ? " selected "  : ""; 
															 echo " <li><a href='$permalink' class='$class' >".$p->post_title."</a></li>";  
											}  	 
											echo "</ul>"; 
								break;  
		 default: //home page

		                        
            					//get all parent categories
								$parents = array(); 
								$duplicate  = array();
								foreach($categories as $category) { 
									if(  $category->parent == 0  && $category->name != "Uncategorized" && !@isset($duplicate[$category->cat_ID]) ){
										$duplicate[$category->cat_ID] = 1;
										$parents[] = $category; 
									}
								}

							
								$silo_start = "<h1>";$silo_end = "</h1>"; 

								$duplicate  = array();
								foreach($parents as $parent){
									$link = "/category/".$parent->slug;
									echo "$silo_start<a href='".$link."'>".$parent->name."</a>$silo_end";  
 
									/*
									foreach($categories as $category) { 
										if(  $category->parent == $parent->cat_ID  && $parent->cat_ID != 0  && !@isset($duplicate[$category->cat_ID]) ){
											$parent_slug = $categories[$category->parent]->slug; 
											$link = "/category/".$category->slug; 
											$duplicate[$category->cat_ID] = 1;
											echo "<h3><a href='".$link."'>".$category->name."</a></h3>"; 
										}
									}	  
									*/
								} 
		} 
		echo "</div>";


		?>

		<script>

		jQuery(function(){ 
			if(jQuery(".breadcrumb").length == 0){ 

			var _i = "", h;  
			var h1 = jQuery(".entry h1").eq(0); 
			var h2 = jQuery(".silo-menu h2").eq(0).find("a");
			var h3 = jQuery(".silo-menu h3").eq(0).find("a"); 
			 

			var this_post = h1.text();   
  

			if( h2.length > 0 && h2.text() != this_post  ){  
			    _i += '&gt; <span class="breadcrumb-link-wrap"  > <a href="'+h2.attr('href')+'" itemprop="item"> <span itemprop="name">'+h2.text()+'</span></a></span>'; 

			   if( h3.length > 0 && h3.text() != this_post  ){   
			      _i += '&gt; <span class="breadcrumb-link-wrap"  > <a href="'+h3.attr('href')+'" itemprop="item"> <span itemprop="name">'+h3.text()+'</span></a></span>'; 
   			   } 

			}


	

			_i += '&gt; <span class="breadcrumb-link-wrap"  >   <span itemprop="name">'+this_post+'</span> </span>'; 

			
             

			 jQuery(jQuery("#dummy_breadcrumb").val()).insertBefore(".content .entry"); 

			  jQuery(_i).insertAfter('.breadcrumb .here');
			}

		});

		</script>

		<?php 
 
	}


	 
 
}

 
 

add_action( 'widgets_init', function(){
	register_widget( 'DM_Widget' );
});
