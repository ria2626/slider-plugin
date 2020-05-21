<?php
/*
    Plugin Name: Slide Show Plugin
    Description: Image Slider provide to add/remove and change the order of images
    Author: Ria Sharma
    Version: 1.0
*/
function np_init() {
    $args =  array(
        'label' => 'Slideshows',
        'show_ui' => true,
        'supports' => array('title'),
        'labels' => array (
            'name' => 'Slideshows',
            'singular_name' => 'Slideshow',
            'menu_name' => 'Slideshows'
        ),
    );
    register_post_type('slideshows', $args);
}
add_action('init', 'np_init');
add_action( 'add_meta_boxes', 'add_slide_image_uploader' );
function add_slide_image_uploader()
{	
	add_meta_box(
		'image_uploader_metabox',
		esc_html__('Slider Images( You can drag and drop the Images Slider to change the order of Images in front)', 'textdomain'),
		'slide_image_metaboxes', 
		'slideshows',
		'normal', 
		'default' 
	);
}
function slide_image_metaboxes($object, $box)
{
	wp_nonce_field ( basename ( __FILE__ ), 'slide_image_metaboxes' );
	global $post;
    $upload_link = esc_url( get_upload_iframe_src() );
    
    ?>
      <input type="hidden" id="plugin_url" value = "<?php echo plugin_dir_url(__FILE__).'lib/assets/delete-icon.png'; ?>">
	<div id="custom-images">
		
		<div class="custom-img-container clearfix" id="sortable1">
			
			<?php 
				$meta_values = get_post_meta( get_the_ID(), 'image_src', false );
				foreach ($meta_values as $value){
			?>
				<div class="image-wrapper" >
                <img src="<?php echo $value;?>">
                    <input type="hidden" name="image_src[]" value="<?php echo $value;?>" >
                  
					<div class="delete-custom-img-main" ><a class="delete-custom-img" href="#"><img src="<?php echo plugin_dir_url(__FILE__).'lib/assets/delete-icon.png'; ?>"></a></div>
				</div>
				
			<?php }?>
			
		</div>
		
	</div>
		
	<p class="custom-content">
        <a class="upload-custom-img" href="<?php echo $upload_link; ?>">
        <img src="<?php echo plugin_dir_url(__FILE__).'lib/assets/add-more.png'; ?>" alt="add more images">
		</a>
    
    </p>
    <div class="clear">
<?php } 

//Save Metadata

function save_image_uploader_metadata( $post_id, $post )
{
if ( !isset( $_POST['slide_image_metaboxes'] ) || !wp_verify_nonce( $_POST['slide_image_metaboxes'], basename( __FILE__ ) ) )
			return $post_id;		
		$post_type = get_post_type_object( $post->post_type );
     	$meta_key = 'image_src';
		$meta_value = get_post_meta( $post_id, $meta_key, false );
		foreach ($meta_value as $value){
			delete_post_meta( $post_id, $meta_key, $value );
        }
		foreach($_POST['image_src'] as $value){	
			add_post_meta( $post_id, $meta_key, $value, false );
		}		
}

function enqueue_media(){
	wp_enqueue_media();
}
function admin_media_uploader_enqueue() {
    wp_enqueue_media();
    wp_register_script('jquery-ui', plugins_url('lib/js/jquery-ui.js' , __FILE__ ), array('jquery'));
    wp_enqueue_script('jquery-ui');
    wp_register_script('media-uploader', plugins_url('lib/js/media-uploader.js' , __FILE__ ), array('jquery'));
    wp_enqueue_script('media-uploader');
    wp_register_style('jquery-ui-styles', plugins_url('lib/css/jquery-ui.css', __FILE__));
    wp_enqueue_style('jquery-ui-styles');
    wp_register_style('plugin-styles', plugins_url('lib/css/plugin-style.css', __FILE__));
     wp_enqueue_style('plugin-styles');
}
function front_scripts() {

    wp_register_script('slideshow-script', plugins_url('lib/js/slideshow-slider.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('slideshow-script');
    wp_register_script('main-script', plugins_url('lib/js/script.js', __FILE__));
    wp_enqueue_script('main-script');
    wp_register_style('swipper-main', plugins_url('lib/css/swipper.css', __FILE__));
    wp_register_style('slideshow-styles', plugins_url('lib/css/slideshow-styles.css', __FILE__));
    wp_enqueue_style('swipper-main');
     wp_enqueue_style('slideshow-styles');
    
}

    add_action('admin_enqueue_scripts', 'admin_media_uploader_enqueue');
    add_action ( 'admin_enqueue_scripts', 'enqueue_media' );
    add_action( 'admin_head', 'admin_media_uploader_enqueue' );
    add_action( 'wp_head', 'front_scripts' );
    add_action( 'add_meta_boxes', 'add_slide_image_uploader' );
    add_action( 'save_post', 'save_image_uploader_metadata', 10, 2 );
    function np_function($atts) {
        $upload_dir   = wp_upload_dir();
        $atts = extract( shortcode_atts( array(
            'id' => false,
           
        ), $atts ) );

        $result = '<div class="swiper-container">';
        $result .= '<div class="swiper-wrapper" id="gallery_wrapper">';
           $meta_key = 'image_src';
           $id = $id;
            $meta_value = get_post_meta($id, $meta_key, false );
          
           foreach($meta_value as $key=>$value)
           { 
               
            $result .= "<div class=\"swiper-slide\">
            <img src= '".$value."'>
            </div>";
    }       
               
        $result .= '</div>';
        $result .= '</div>';
        $result .= '<div class="swiper-pagination"></div>';
             $result .='</div>';
        return $result;
    }

    add_shortcode('myslideshow', 'np_function');


?>