<?php

if ( ! function_exists('thumbnail'))
{
	function thumbnail($image_path, $height, $width) {
			
			// Get the CodeIgniter super object
		    $CI =& get_instance();
		
		    // Path to image thumbnail
		    //$image_thumb = dirname( $image_path ) . '/' . $height . '_' . $width . '.jpg';
		    
		    if(!file_exists('uploads/thumbnails/')){
		    	mkdir('uploads/thumbnails/');
		    }
			
			
		    $image_thumb = 'uploads/thumbnails/'.pathinfo($image_path, PATHINFO_BASENAME);
		
		    if ( !file_exists( $image_thumb ) ) {
		        // LOAD LIBRARY
		        $CI->load->library( 'image_lib' );
		
		        // CONFIGURE IMAGE LIBRARY
		        $config['image_library']    = 'gd2';
		        $config['source_image']     = $image_path;
		        $config['new_image']        = $image_thumb;
		        $config['maintain_ratio']   = TRUE;
		        $config['height']           = $height;
		        $config['width']            = $width;
		        $CI->image_lib->initialize( $config );
		        $CI->image_lib->resize();
		        $CI->image_lib->clear();
		    }
		
		    return '<img src="'.base_url() . $image_thumb . '" />';
		 
		 
		 
	}
}

?>