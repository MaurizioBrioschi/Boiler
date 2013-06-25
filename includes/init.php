<?php
 /**
  * init file to configure web application parameters
  * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 include 'conf.php';
 /*** include the controller class ***/
 include __SITE_PATH . '/application/' . 'controller_base.class.php';

 /*** include the registry class ***/
 include __SITE_PATH . '/application/' . 'registry.class.php';

 /*** include the router class ***/
 include __SITE_PATH . '/application/' . 'router.class.php';

 /*** include the template class ***/
 include __SITE_PATH . '/application/' . 'template.class.php';
 
 
 /*** auto load model classes ***/
 function __autoload($class_name) {
    $filename = $class_name . '.class.php';
    $file = __SITE_PATH . '/model/' . $filename;
    /*$templates = __SITE_PATH . '/model/templates/' . $filename;*/
    
    if (file_exists($file) == false)
    {
        return false;
    }
    include ($file);
}

/*** a new registry object ***/
 $registry = new registry;
// this how to create a db connection... if you don't want it all pages, just call this where is needed
// $conn = new MySqlConnection($SERVER,$USER,$PWD,$DATABASE);
// $registry->repository = new DBRepository($conn);
 
 

 $registry->Authentication = new Authentication();
 
 /** variabili per path **/
 $registry->UrlSite = "http://localhost/boiler/";
 $registry->UrlSiteCMS = "http://localhost/boiler/cms/";
 $registry->pathSite = $_SERVER['DOCUMENT_ROOT']."/boiler/";
 $registry->imgs = "imgs/";
 $registry->imgNews = $registry->imgs."news/";
 $registry->imgsProdotti = "imgs/prodotti/";
 $registry->mat = "mat/";
 $registry->css = "css/";
 $registry->js = "js/";
 $registry->pdf = "pdf/";
 
 
 /** varibili conf */
 $registry->titleApplication = "Boiler"; //application name
 $registry->logo = $registry->UrlSite.$registry->imgs."logo.gif"; //file logo
 $registry->gallery_thumb = "200";//thumb dimension
 
 
 @session_start();
 /**
  * if you have a part of the web application protected
  */
 if(!empty($_SESSION['contextUser'])){
          $registry->AdminUser = unserialize($_SESSION['contextUser']);
  }
 
?>
