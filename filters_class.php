<?php
/*
* Post ajax filters class
*/
//Example taken from http://ottopress.com/2009/wordpress-settings-api-tutorial/
class PropertiesOptions
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Properties Options', 
            'manage_options', 
            'property-options', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'property_options' );
        ?>
        <div class="wrap">
            <h1>My Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'property-options' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'property_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Property Plugin Options', // Title
            array( $this, 'print_section_info' ), // Callback
            'property-options' // Page
        );  

        add_settings_field(
            'taxonomies', // ID
            'Taxonomies to be filtered', // Title 
            array( $this, 'taxonomy_filter_callback' ), // Callback
            'property-options', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'meta', 
            'Meta Fields to be filtered', 
            array( $this, 'meta_filter_callback' ), 
            'property-options', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['taxonomies'] ) )
            $new_input['taxonomies'] = sanitize_text_field( $input['taxonomies'] );
        if( isset( $input['meta'] ) )
            $new_input['meta'] = sanitize_text_field( $input['meta'] );
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter filter settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function taxonomy_filter_callback()
    {
        printf(
            '<input type="text" id="taxonomies" name="property_options[taxonomies]" value="%s" />',
            isset( $this->options['taxonomies'] ) ? esc_attr( $this->options['taxonomies']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function meta_filter_callback()
    {
        printf(
            '<input type="text" id="meta" name="property_options[meta]" value="%s" />',
            isset( $this->options['meta'] ) ? esc_attr( $this->options['meta']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new PropertiesOptions();

class FiltersClass{
	public $filtered_properties,$per_page,$current_page,$taxonomy,$meta;
	public function property_filters(){
		$this->options = get_option('property_options');
		$property_filters = '<div class="properties-filter-wrapper">';
		$property_filters .= '<form id="property-filters" action="">';
		if(!empty($this->options)){
			foreach($this->options as $option => $value){
				if($option == 'taxonomies'){

					$values_arr = explode(',', $value);
					foreach($values_arr as $tax_value){
						$tax_terms = get_terms($tax_value);
						// echo '<pre>';print_r($tax_terms); echo '</pre>';
						$property_filters .= '<label for="'.$tax_value.'">Select '.$tax_value.' : </label>';
						$property_filters .= '<select id="'.$tax_value.'" name="'.$tax_value.'">';
						$property_filters .= '<option name="all" value="0">Select option</option>';	
						foreach ($tax_terms as $tax_term) {
							$property_filters .= '<option name="'.$tax_term->name.'" value="'.$tax_term->term_id.'">'.$tax_term->name.'</option>';	
						}
						
						$property_filters .= '</select>';
					}
					

				}else{
					$property_filters .= '<label class="ml-3" for="'.$value.'">Specify max '.ucwords($value).' : </label>';
					$property_filters .= '<input type="text" name="'.$value.'" value="" class="" placeholder="e.g 4000 to filter from 0 to 4000 price range"/>';

				}
				
			}
		}
		$property_filters .= '<button class="ml-3" type="submit" value="Submit">Search</button>';
		$property_filters .= '</form>';
		$property_filters .= '</div>';
		return $property_filters;
	}
	public function filter_properties($taxonomy,$meta,$current_page,$per_page){
		$filtered_properties = '<div class="properties-filter-wrapper">';
		if(!empty($taxonomy)){
			foreach($taxonomy as $tax){
				$filtered_properties .= ' tax - '.$tax.' | ';
			}
		}
		$filtered_properties .= '</div>';
	}
}