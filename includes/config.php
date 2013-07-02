<?php
 /** variabili per path **/
 $UrlSite = "http://localhost/boiler/";
 $UrlSiteCMS = "http://localhost/boiler/cms/";
 $pathSite = $_SERVER['DOCUMENT_ROOT']."/boiler/";
 $imgs = "imgs/";
 $imgNews = $imgs."news/";
 $imgsProdotti = "imgs/prodotti/";
 $mat = "mat/";
 $css = "css/";
 $js = "js/";
 $pdf = "pdf/";
 
 
 /** varibili conf */
 $titleApplication = "Boiler"; //application name
 $logo = $UrlSite.$imgs."logo.gif"; //file logo
 $gallery_thumb = "200";//thumb dimension
 include ('dbconf.php');
?>
