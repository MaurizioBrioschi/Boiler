<?php
 /**
  * init file per la configurazione di tutti i parametri globali
  * @version 1.0
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

 $conn = new MySqlConnection($SERVER,$USER,$PWD,$DATABASE);
 $registry->MySqlConnection = $conn;
 
 

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
 $registry->titleApplication = "Boiler"; //nome dell'applicazione
 $registry->logo = $registry->UrlSite.$registry->imgs."logo.gif"; // nome del file del logo
 $registry->gallery_thumb = "200";//dimensione della thumb per le foto della gallery
 $registry->gallery_picture = "400"; //ridimensionamento delle foto della gallery
 
 @session_start();
 
 if(!empty($_SESSION['contextUser'])){
          $registry->AdminUser = unserialize($_SESSION['contextUser']);
  }
 
?>
