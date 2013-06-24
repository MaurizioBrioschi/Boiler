<?php
/**
 * class for photo utility
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 */
class PhotoUtility{
// call createThumb function and pass to it as parameters the path
// to the directory that contains images, the path to the directory
// in which thumbnails will be placed and the thumbnail's width.
// We are assuming that the path will be a relative path working
// both in the filesystem, and through the web for links
//createThumbs("upload/","upload/thumbs/",100);
public static function createThumbs($pathToImages, $pathToThumbs, $thumbWidth )
{
  // open the directory
  $dir = opendir( $pathToImages );
 
  // loop through it, looking for any/all JPG files:
  while (FALSE !== ($fname = readdir( $dir ))) {
    // parse path for the extension
    $info = pathinfo($pathToImages . $fname);
    
    // continue only if this is a JPEG image
    if ( strtolower($info['extension']) == 'jpg' )
    {
      echo "Creating thumbnail for {$fname} <br />";

      // load image and get image size
      try{
      $img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
      
      $width = imagesx( $img );

      $height = imagesy( $img );
      if($width > $height){
      // calculate thumbnail size
          $new_width = $thumbWidth;
          $new_height = floor( $height * ( $thumbWidth / $width ) );
      }else{
          $new_height = $thumbWidth;
          $new_width = floor( $width * ( $thumbWidth / $height ) );
      }
      // create a new temporary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image
      imagecopyresampled ( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
      
      imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
      }catch(Exception $e){
          print $e->getMessage();
      }
    }
  }
  // close the directory
  closedir( $dir );
}

public static function createThumb_old($pathImage,$pathToThumbs, $thumbWidth,$thumHeight=0){

  // loop through it, looking for any/all JPG files:

    
    // parse path for the extension
    $info = pathinfo($pathImage);
    
    // continue only if this is a JPEG image
    if ( strtolower($info['extension']) == 'jpeg' || strtolower($info['extension']) == 'jpg')
    {


      // load image and get image size
      try{
      
      $img = imagecreatefromjpeg( "{$pathImage}" );
      $width = imagesx( $img );
      
      if($thumHeight==0) $height = imagesy( $img );
      else $height=$thumHeight;

      // calculate thumbnail size
      if($width >= $height){
          $new_width = $thumbWidth;
          $new_height = floor( $height * ( $thumbWidth / $width ) );
      }else{

          $new_height = $thumbWidth;
          $new_width = floor( $width * ( $thumbWidth / $height ) );
      }

      // create a new temporary image
      
     
     if($new_width < $width){
         $tmp_img = imagecreatetruecolor( $new_width, $new_height );
         imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
      }else{
         $tmp_img = imagecreatetruecolor($width, $height );
         imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $width, $height, $width, $height );
      }

      // save thumbnail into a file
      ob_start();
      imagejpeg( $tmp_img, "{$pathToThumbs}");
    

     
      $sBinaryThumbnail = ob_get_contents(); // the raw jpeg image data.
      ob_end_clean();
      
      echo $sBinaryThumbnail;
      
      }catch(Exception $e){
          print $e->getMessage();
      }
    }
    if ( strtolower($info['extension']) == 'gif')
    {


      // load image and get image size
      try{

      $img = imagecreatefromgif( "{$pathImage}" );
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      if($width >= $height){
          $new_width = $thumbWidth;
          $new_height = floor( $height * ( $thumbWidth / $width ) );
      }else{

          $new_height = $thumbWidth;
          $new_width = floor( $width * ( $thumbWidth / $height ) );
      }

      // create a new temporary image


      if($new_width < $width){
         $tmp_img = imagecreatetruecolor( $new_width, $new_height );
         imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
      }else{
         $tmp_img = imagecreatetruecolor($width, $height );
         imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $width, $height, $width, $height );
      }

      // save thumbnail into a file
      ob_start();
      imagegif( $tmp_img, "{$pathToThumbs}");



      $sBinaryThumbnail = ob_get_contents(); // the raw jpeg image data.
      ob_end_clean();

      echo $sBinaryThumbnail;

      }catch(Exception $e){
          print $e->getMessage();
      }
    }

    if ( strtolower($info['extension']) == 'png')
    {


      // load image and get image size
      try{

      $img = imagecreatefrompng( "{$pathImage}" );
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      if($width >= $height){
          $new_width = $thumbWidth;
          $new_height = floor( $height * ( $thumbWidth / $width ) );
      }else{

          $new_height = $thumbWidth;
          $new_width = floor( $width * ( $thumbWidth / $height ) );
      }

      // create a new temporary image

      if($new_width < $width){
         $tmp_img = imagecreatetruecolor( $new_width, $new_height );
         imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
      }else{
         $tmp_img = imagecreatetruecolor($width, $height );
         imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $width, $height, $width, $height );
      }
      // save thumbnail into a file
      ob_start();
      imagepng( $tmp_img, "{$pathToThumbs}");



      $sBinaryThumbnail = ob_get_contents(); // the raw jpeg image data.
      ob_end_clean();

      echo $sBinaryThumbnail;

      }catch(Exception $e){
          print $e->getMessage();
      }
    }
}


public static function createThumb($pathImage,$pathToThumbs, $th_width,$th_height=0){

  // loop through it, looking for any/all JPG files:

    $forcefill = true;
    // parse path for the extension
    $info = pathinfo($pathImage);
    
    // continue only if this is a JPEG image
    if ( strtolower($info['extension']) == 'jpeg' || strtolower($info['extension']) == 'jpg')
    {
		

      // load image and get image size
      try{
      
      $img = imagecreatefromjpeg( "{$pathImage}" );
     
      
      $width = imagesx( $img );
      $height = imagesy( $img );
      
      if($th_height==0)
      {
      	$th_height = floor(($th_width/$width)*$height);
	  }
	  
	  
	  if($width > $th_width || $height > $th_height){
      $a = $th_width/$th_height;
      $b = $width/$height;

      if(($a > $b)^$forcefill)
      {
         $src_rect_width  = $a * $height;
         $src_rect_height = $height;
         if(!$forcefill)
         {
            $src_rect_width = $width;
            $th_width = $th_height/$height*$width;
         }
      }
      else
      {
         $src_rect_height = $width/$a;
         $src_rect_width  = $width;
         if(!$forcefill)
         {
            $src_rect_height = $height;
            $th_height = $th_width/$width*$height;
         }
      }

      $src_rect_xoffset = ($width - $src_rect_width)/2*intval($forcefill);
      $src_rect_yoffset = ($height - $src_rect_height)/2*intval($forcefill);

      $tmp_img  = imagecreatetruecolor($th_width, $th_height);
      imagecopyresampled ($tmp_img, $img, 0, 0, $src_rect_xoffset, $src_rect_yoffset, $th_width, $th_height, $src_rect_width, $src_rect_height);
	  
	  
      // create a new temporary image
      
      // save thumbnail into a file
      ob_start();
      imagejpeg( $tmp_img, "{$pathToThumbs}");
    

     
      $sBinaryThumbnail = ob_get_contents(); // the raw jpeg image data.
      ob_end_clean();
      
      echo $sBinaryThumbnail;
		      }
		
      }catch(Exception $e){
          print $e->getMessage();
      }
    }
    
    
    
    if ( strtolower($info['extension']) == 'gif')
    {


      // load image and get image size
      try{

      $img = imagecreatefromgif( "{$pathImage}" );
      $width = imagesx( $img );
      $height = imagesy( $img );
      
      if($th_height==0)
      {
      	$th_height = floor(($th_width/$width)*$height);
	  }
	  
	  
	  if($width > $th_width || $height > $th_height){
      $a = $th_width/$th_height;
      $b = $width/$height;

      if(($a > $b)^$forcefill)
      {
         $src_rect_width  = $a * $height;
         $src_rect_height = $height;
         if(!$forcefill)
         {
            $src_rect_width = $width;
            $th_width = $th_height/$height*$width;
         }
      }
      else
      {
         $src_rect_height = $width/$a;
         $src_rect_width  = $width;
         if(!$forcefill)
         {
            $src_rect_height = $height;
            $th_height = $th_width/$width*$height;
         }
      }

      $src_rect_xoffset = ($width - $src_rect_width)/2*intval($forcefill);
      $src_rect_yoffset = ($height - $src_rect_height)/2*intval($forcefill);

      $tmp_img  = imagecreatetruecolor($th_width, $th_height);
      imagecopyresampled ($tmp_img, $img, 0, 0, $src_rect_xoffset, $src_rect_yoffset, $th_width, $th_height, $src_rect_width, $src_rect_height);
      // save thumbnail into a file
      ob_start();
      imagegif( $tmp_img, "{$pathToThumbs}");



      $sBinaryThumbnail = ob_get_contents(); // the raw jpeg image data.
      ob_end_clean();

      echo $sBinaryThumbnail;
	}
      }catch(Exception $e){
          print $e->getMessage();
      }
    }

    if ( strtolower($info['extension']) == 'png')
    {


      // load image and get image size
      try{

      $img = imagecreatefrompng( "{$pathImage}" );
      $width = imagesx( $img );
      $height = imagesy( $img );
      
      if($th_height==0)
      {
      	$th_height = floor(($th_width/$width)*$height);
	  }
	  
	  
	  if($width > $th_width || $height > $th_height){
      $a = $th_width/$th_height;
      $b = $width/$height;

      if(($a > $b)^$forcefill)
      {
         $src_rect_width  = $a * $height;
         $src_rect_height = $height;
         if(!$forcefill)
         {
            $src_rect_width = $width;
            $th_width = $th_height/$height*$width;
         }
      }
      else
      {
         $src_rect_height = $width/$a;
         $src_rect_width  = $width;
         if(!$forcefill)
         {
            $src_rect_height = $height;
            $th_height = $th_width/$width*$height;
         }
      }

      $src_rect_xoffset = ($width - $src_rect_width)/2*intval($forcefill);
      $src_rect_yoffset = ($height - $src_rect_height)/2*intval($forcefill);

      $tmp_img  = imagecreatetruecolor($th_width, $th_height);
      imagecopyresampled ($tmp_img, $img, 0, 0, $src_rect_xoffset, $src_rect_yoffset, $th_width, $th_height, $src_rect_width, $src_rect_height);
      // save thumbnail into a file
      ob_start();
      imagepng( $tmp_img, "{$pathToThumbs}");



      $sBinaryThumbnail = ob_get_contents(); // the raw jpeg image data.
      ob_end_clean();

      echo $sBinaryThumbnail;
}
      }catch(Exception $e){
          print $e->getMessage();
      }
    }
}


// call createGallery function and pass to it as parameters the path
// to the directory that contains images and the path to the directory
// in which thumbnails will be placed. We are assuming that
// the path will be a relative path working
// both in the filesystem, and through the web for links
//createGallery("upload/","upload/thumbs/");
public static function createGallery( $pathToImages, $pathToThumbs )
{
  echo "Creating gallery.html <br />";

  $output = "<html>";
  $output .= "<head><title>Thumbnails</title></head>";
  $output .= "<body>";
  $output .= "<table cellspacing=\"0\" cellpadding=\"2\" width=\"500\">";
  $output .= "<tr>";

  // open the directory
  $dir = opendir( $pathToThumbs );

  $counter = 0;
  // loop through the directory
  while (FALSE !== ($fname = readdir($dir)))
  {
    // strip the . and .. entries out
    if ($fname != '.' && $fname != '..')
    {
      $output .= "<td valign=\"middle\" align=\"center\"><a href=\"{$pathToImages}{$fname}\">";
      $output .= "<img src=\"{$pathToThumbs}{$fname}\" border=\"0\" />";
      $output .= "</a></td>";

      $counter += 1;
      if ( $counter % 4 == 0 ) { $output .= "</tr><tr>"; }
    }
  }
  // close the directory
  closedir( $dir );

  $output .= "</tr>";
  $output .= "</table>";
  $output .= "</body>";
  $output .= "</html>";

  // open the file
  $fhandle = fopen( "gallery.html", "w" );
  // write the contents of the $output variable to the file
  fwrite( $fhandle, $output );
  // close the file
  fclose( $fhandle );
}
}///photoUtility
?>