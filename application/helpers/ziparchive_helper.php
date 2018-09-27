<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */


if ( ! function_exists('ziparchive'))
{
	function ziparchive($file_names=array(),$archive_file_name,$file_path) {
		 //create the object
		  $zip = new ZipArchive();
		  //create the file and throw the error if unsuccessful
		  if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
		    return false;
		  }else{
		
			  //add each files of $file_name array to archive
			  foreach($file_names as $files)
			  {
			    $zip->addFile($file_path.$files,$files);
			  }
			  
			  
			  $zip->close();
			
			  //then send the headers to foce download the zip file
			  header("Content-type: application/zip");
			  header("Content-Disposition: attachment; filename=$archive_file_name");
			  header("Pragma: no-cache");
			  header("Expires: 0");
			  readfile("$archive_file_name");
		  
		  }
		
	}
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */