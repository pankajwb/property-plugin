<?php 
class PropertiesClass{
	// Put all your add_action, add_shortcode, add_filter functions in __construct()
  // For the callback name, use this: array($this,'<function name>')
  // <function name> is the name of the function within this class, so need not be globally unique
  public function __construct(){
    //enqueue js script for ajax actions
    add_action('wp_enqueue_scripts', array($this,'properties_scripts'));
    // Register Property Custom Post Type
    add_action('init', array($this,'property_post_type'));
    // register taxonomies
		add_action( 'init', array($this,'property_type_taxonomy' ));
		add_action( 'init', array($this,'tenure_taxonomy' ));
    // ajax call backs
    add_action('wp_ajax_nopriv_properties_loadmore',array($this,'properties_loadmore'));
    add_action('wp_ajax_properties_loadmore',array($this,'properties_loadmore'));
    // shortcode for property listing
    add_action('init', array($this,'add_property_listing_shortcode'));
  }
  public function properties_scripts(){
    // css file
    wp_enqueue_style('property-styles', plugins_url('/assets/css/property.css',__FILE__));
    // js file
    wp_register_script('property-js', plugins_url('/assets/js/property.js',__FILE__), array('jquery') );
    wp_localize_script( 'property-js', 'properties_ajax_params', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            //'someVariable' => 'These are my socks'
        ));
    wp_enqueue_script('property-js');
  }
  public function property_post_type() {

		$labels = array(
			'name'                  => _x( 'Properties', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Property', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Properties', 'text_domain' ),
			'name_admin_bar'        => __( 'Properties', 'text_domain' ),
			'archives'              => __( 'Item Archives', 'text_domain' ),
			'attributes'            => __( 'Item Attributes', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Items', 'text_domain' ),
			'add_new_item'          => __( 'Add New Item', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Item', 'text_domain' ),
			'edit_item'             => __( 'Edit Item', 'text_domain' ),
			'update_item'           => __( 'Update Item', 'text_domain' ),
			'view_item'             => __( 'View Item', 'text_domain' ),
			'view_items'            => __( 'View Items', 'text_domain' ),
			'search_items'          => __( 'Search Item', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$args = array(
			'label'                 => __( 'Property', 'text_domain' ),
			'description'           => __( 'Property Post type', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor','thumbnail' ),
			'taxonomies'            => array( 'category', 'post_tag', 'property_type', 'tenure' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'property', $args );
		
	}
	// Register Custom Taxonomy: property_type
	public function property_type_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Property Types', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Property Type', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Property Types', 'text_domain' ),
			'all_items'                  => __( 'All Items', 'text_domain' ),
			'parent_item'                => __( 'Parent Item', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
			'new_item_name'              => __( 'New Item Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Item', 'text_domain' ),
			'edit_item'                  => __( 'Edit Item', 'text_domain' ),
			'update_item'                => __( 'Update Item', 'text_domain' ),
			'view_item'                  => __( 'View Item', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Items', 'text_domain' ),
			'search_items'               => __( 'Search Items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No items', 'text_domain' ),
			'items_list'                 => __( 'Items list', 'text_domain' ),
			'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy( 'property_type', array( 'property' ), $args );

	}
	// Register Custom Taxonomy: tenure
	public function tenure_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Tenure', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Tenure', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Tenure', 'text_domain' ),
			'all_items'                  => __( 'All Items', 'text_domain' ),
			'parent_item'                => __( 'Parent Item', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
			'new_item_name'              => __( 'New Item Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Item', 'text_domain' ),
			'edit_item'                  => __( 'Edit Item', 'text_domain' ),
			'update_item'                => __( 'Update Item', 'text_domain' ),
			'view_item'                  => __( 'View Item', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Items', 'text_domain' ),
			'search_items'               => __( 'Search Items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No items', 'text_domain' ),
			'items_list'                 => __( 'Items list', 'text_domain' ),
			'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy( 'tenure', array( 'property' ), $args );

	}
	// property listing shortcode callback
	public function add_property_listing_shortcode(){
		add_shortcode('property_listing','property_listing_callback');
		function property_listing_callback($atts){
			//echo '<pre>this is property listing: '.json_encode($atts).'</pre>';	
			$property_listing = '';
			// if filters are to be shown
			if($atts['filters']==true){
				$property_listing .= '<div class="property-filters-wrapper">';
				$property_listing .= '<div class="property-filters-container">';
				//$property_listing .= 'filters here'; 				//TODO: ADD FILTERS HTML
				$property_listing .= '</div></div>';
			}
			// prepare html in a variable 
			$property_listing .= '<div class="property-listing-wrapper section">';
			$property_listing .= '<div class="property-listing-container row">';
			$args = array(
				'post_type' => 'property',
				'posts_per_page' => isset($atts['per_page']) ? $atts['per_page'] : 6 ,
				'paged' => get_query_var( 'paged' )
			);
			$properties = new WP_Query($args);
			if($properties->have_posts()):
				while($properties->have_posts()): 
					$properties->the_post();
					$property_listing .= '<div class="property-card col col-md-4">';
					$property_listing .= '<img class="prop-thumb" src="'.get_the_post_thumbnail_url(get_the_ID(),'medium').'"/>';
					$property_listing .= '<h3>'.get_the_title().'<h3>';
					$property_listing .= '<div class="prop-meta">';
					$property_listing .= '<span class="tax-prop-type prop-taxo"><ul>';
					if(!empty(get_the_terms( get_the_ID(), 'property_type' ))){
						foreach ( get_the_terms( get_the_ID(), 'property_type' ) as $tax ) {
	    				$property_listing .= '<li>' . __( $tax->name ) . '</li>';
						}	
					}
					
					$property_listing .= '</span></ul>';
					$property_listing .= '<span class="tax-tenure-type prop-taxo"><ul>';
					if(!empty(get_the_terms( get_the_ID(), 'tenure' ))){
						foreach ( get_the_terms( get_the_ID(), 'tenure' ) as $tax ) {
	    				$property_listing .= '<li>' . __( $tax->name ) . '</li>';
						}
					}
					$property_listing .= '</span></ul>';
					$property_listing .= '</div>';
					$property_listing .= '</div>';
				endwhile;
					if(isset($atts['loadmore']) && $atts['loadmore'] == true){
						$property_listing .= '<div class="properties-loadmore-wrapper">';
						$property_listing .= '<button type="button" class="btn btn-secondary" id="properties-loadmore">Load More</button>';
						$property_listing .= '<input type="hidden" id="property-listing-page" value="1"/>';
						$property_listing .= '<input type="hidden" id="property-listing-perpage" value="'.$args['posts_per_page'].'"/>';
						// $property_listing .= '<input type="hidden" id="property-listing-perpage" value="'.$args['posts_per_page'].'"/>';
						$property_listing .= '</div>';
					}else{
						$total_pages = $properties->max_num_pages;

				    if ($total_pages > 1){

				        $current_page = max(1, get_query_var('paged'));
				        $property_listing .= '<div class="pagination">';
				        $property_listing .= paginate_links(array(
				            'base' => get_pagenum_link(1) . '%_%',
				            'format' => '/page/%#%',
				            'current' => $current_page,
				            'total' => $total_pages,
				            'prev_text'    => __('« prev'),
				            'next_text'    => __('next »'),
				        ));
				        $property_listing .= '</div>';
				    }
					}
			endif;
			$property_listing .= '</div>';
			//edit html above this line. 
			$property_listing .= '</div><!-- property-listing-wrapper -->';
			echo $property_listing;
		}
		
	}
	// ajax loadmore callback
	public function properties_loadmore(){
		// Set vars
		$current_page = filter_var($_POST['paged'],FILTER_SANITIZE_NUMBER_INT );
		$per_page = filter_var($_POST['posts_per_page'],FILTER_SANITIZE_NUMBER_INT );
		
		// prepare html in a variable 
		$property_listing .= '<div class="property-listing-wrapper section">';
		$property_listing .= '<div class="property-listing-container row">';
		$args = array(
			'post_type' => 'property',
			'posts_per_page' => isset($per_page) ? $per_page : 6 ,
			'paged' => $current_page
		);
		$properties = new WP_Query($args);
		if($properties->have_posts()):
			while($properties->have_posts()): 
				$properties->the_post();
				$property_listing .= '<div class="property-card col col-md-4">';
				$property_listing .= '<img class="prop-thumb" src="'.get_the_post_thumbnail_url(get_the_ID(),'medium').'"/>';
				$property_listing .= '<h3>'.get_the_title().'<h3>';
				$property_listing .= '<div class="prop-meta">';
				$property_listing .= '<span class="tax-prop-type prop-taxo"><ul>';
				if(!empty(get_the_terms( get_the_ID(), 'property_type' ))){
					foreach ( get_the_terms( get_the_ID(), 'property_type' ) as $tax ) {
    				$property_listing .= '<li>' . __( $tax->name ) . '</li>';
					}	
				}
				
				$property_listing .= '</span></ul>';
				$property_listing .= '<span class="tax-tenure-type prop-taxo"><ul>';
				if(!empty(get_the_terms( get_the_ID(), 'tenure' ))){
					foreach ( get_the_terms( get_the_ID(), 'tenure' ) as $tax ) {
    				$property_listing .= '<li>' . __( $tax->name ) . '</li>';
					}
				}
				$property_listing .= '</span></ul>';
				$property_listing .= '</div>';
				$property_listing .= '</div>';
			endwhile;
				$more_posts = true;
		else:
			$property_listing .= "";
			$more_posts = false;
		endif;
		$property_listing .= '</div>';
		//edit html above this line. 
		$property_listing .= '</div><!-- property-listing-wrapper -->';
		// send response

  	wp_send_json_success( // check wp send succes
      array(
        'properties' => $property_listing,
        'posts_found' => $more_posts,
        // 'wp_query' => $_POST['wp_qry'],
        // 'our_qry'  => $args
      )
    );
    exit;
	}

}
