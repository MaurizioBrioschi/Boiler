<?php
/**
 * classe che contiene alcune utility per le operazioni input/output
 * @version 1.2
 */
class UtilityIO {
    /**
     * remove directory
     * @param string $path 
     */
    public static function removeDirectory($path)  {
        try{
             $objects = scandir($path);
             foreach ($objects as $object) {
                   if ($object != "." && $object != "..") {
                      if(is_dir($path."/".$object)){
                          UtilityIO::removeDirectory($path."/".$object);
                      }
                      else
                          UtilityIO::removeFile($path."/".$object);
                   }
             } 
             if(!@rmdir($path)){
                 $errors= error_get_last();
                 die($errors["message"]." at ".$errors["file"]." line ".$errors["line"]);
             }
        }catch(IOException $e)  {
            die($e->getMessage());
        }
    }
    /**
     * remove a file
     * @param string $filepath 
     */
    public static function removeFile($filepath)  {
        try{
             if(!@unlink($filepath))   {
                          $errors= error_get_last();
                          die($errors["message"]." at ".$errors["file"]." line ".$errors["line"]);
             }
             return true;
        }catch(IOException $e)  {
            die($e->getMessage());
            return false;
        }
    }
    /**
     * replace the oldstring with a new string in a file
     * @param String $path
     * @param String $oldString
     * @param String $newString
     * @return true if it's done, false if an error is occured
     */
    public static function replaceInFile($path,$oldString,$newString){   
        try{
            $file = file_get_contents($path);
            $file = str_replace($oldString, $newString, $file);
            $handle = @fopen($path,"wb");
            file_put_contents($path, $file);
            @fclose($handle);
            
        }catch(IOException $e)    {
            die($e->getMessage());
        }
    }
    /**
     * unzip $path file in $dest folder
     * @param type $path
     * @param type $dest
     * @return type 
     */
    public static function unzipFile($path, $dest)  {
     $zip = new ZipArchive;
     $res = $zip->open($path);
     
     if ($res === TRUE) {
         
         $zip->extractTo($dest);
         $zip->close();
         $objects = scandir($dest);
         foreach ($objects as $object) {
               if ($object != "." && $object != "..") {
                  if(is_dir($dest."/".$object)){
                      rename($dest.$object, str_replace(" ", "_", $dest.$object));
                      $name = str_replace(" ", "_", $object);
                      return $name."/";
                      
                  }
                  
               }
         } 
         
     } else {
         return "";
     }
    }
    /**
     * Uploada un file in $path con nome $file_dest, passato in post attraverso $post_var 
     * @param string $file
     * @param string $path
     * @param string $file_dest
     * @return string $fileName o Boolen FALSE
     */
    public static function uploadFile($file,$path="",$file_dest=null)   {
        if($file_dest!=null)
            $fileName = str_replace (" ", "_", $file_dest);
        else
            $fileName = str_replace (" ", "_",$file);


        if(!get_magic_quotes_gpc())
        {
            $fileName =  addslashes ($fileName);
        }

        try{
           if(move_uploaded_file($file,$path.$fileName)){
                    return $fileName;
           }  else {
               return FALSE;
           }
        }catch(Exception $e){
            return FALSE;
        }
             
             
    }  
}
?>
