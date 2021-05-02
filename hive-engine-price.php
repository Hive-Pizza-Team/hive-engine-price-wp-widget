<?php
/**
 * @package HiveEnginePrice
 */
/*
Plugin Name: Hive Engine Price
Plugin URI: https://github.com/Hive-Pizza-Team/hive-engine-price-wp-widget
Description: Display price information for HiveEngine tokens
Version: 0.0.1
Author: Hive Pizza Team
Author URI: https://hive.pizza
License: GPLv2
Text Domain: hiveengine
*/


// Creating the widget
class hep_widget extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'hep_widget',

// Widget name will appear in UI
__('Hive Engine Price', 'hep_widget_domain'),

// Widget description
array( 'description' => __( 'Show Hive-Engine token prices', 'hep_widget_domain' ), )
);
}


// Creating widget front-end

public function widget( $args, $instance ) {
$token = apply_filters( 'widget_title', $instance['token'] );

$content_div_id = 'hep_widget_content_' . $this->id;


// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $token ) )
//echo $args['before_title'] . $this->name . $args['after_title'];

// This is where you run the code and display the output
echo __( '<!-- Start : Hive Engine Price Widget -->', 'hep_widget_domain' );
echo __( '<div class="hep_widget_container">', 'hep_widget_domain' );

// Widget content header
echo __( '<div class="hep_widget_header" style="background-color: #'.get_background_color().'; color: #'.header_textcolor().';">', 'hep_widget_domain' );
echo __( '<h4>$' . $token . '</h4><h6> Current Price:</h6>', 'hep_widget_domain' );
echo __( '</div>', 'hep_widget_domain' );

echo __( get_header_textcolor(), 'hep_widget_domain');

// Width content body
echo __( '<h5><div id="' . $content_div_id . '" class="hep_widget_content" data-token-name="'.$token.'""></div></h5>', 'hep_widget_domain' );

echo __( '<div class="hep_widget_exchanges">
			<a href="https://hive-engine.com/?p=market&t='.$token.'"><img src="'.plugins_url( '/img/hiveengine.png' , __FILE__ ).'"></a>
			<a href="https://leodex.io/market/'.$token.'"><img src="'.plugins_url( '/img/leodex.png' , __FILE__ ).'"></a>
			<a href="https://tribaldex.com/trade/'.$token.'"><img src="'.plugins_url( '/img/tribaldex.png' , __FILE__ ).'"></a>
		 </div>'
		  , 'hep_widget_domain' );

echo __( '</div>', 'hep_widget_domain' );
echo __( '<!-- End : Hive Engine Price Widget -->', 'hep_widget_domain' );

echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'token' ] ) ) {
$token = $instance[ 'token' ];
}
else {
$token = __( 'PIZZA', 'hep_widget_domain' );
}

// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Token:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'token' ); ?>" name="<?php echo $this->get_field_name( 'token' ); ?>" type="text" value="<?php echo esc_attr( $token ); ?>" />
</p>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['token'] = ( ! empty( $new_instance['token'] ) ) ? strip_tags( $new_instance['token'] ) : '';
return $instance;
}

// Class hep_widget ends here
}


// Register and load the widget
function hep_load_widget() {
    register_widget( 'hep_widget' );

    // Load scripts
	add_action( 'wp_enqueue_scripts', 'hep_load_scripts' );
	function hep_load_scripts() {

	    wp_enqueue_script( 'axios', plugins_url( '/axios.min.js' , __FILE__ ) );
	    wp_enqueue_script( 'hive-engine-price', plugins_url( '/hive-engine-price.js' , __FILE__ ), array( 'axios' ), '1.0', true);
	}

	add_action('wp_head', 'widget_styles');
	function widget_styles(){
	    wp_enqueue_style(  'hep', plugins_url( '/css/hep.css' , __FILE__ ) );
	}
}
add_action( 'widgets_init', 'hep_load_widget' );
