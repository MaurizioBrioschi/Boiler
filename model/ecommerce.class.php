<?php

/**
 * classe per la gestione dei dati riguardanti all'ecommerce
 * @version 1.0
 */
class ecommerce{
   
    /**
     * Restituisce il dataset di tutti gli utenti iscritti al sito
     * @param MySqlConnection $DBConnection
     * @param string $where
     * @param string $ordine
     * @return mixed array
     */
    public static function getIscritti(MySqlConnection $DBConnection,$where=null,$ordine="ORDER BY data_iscrizione DESC,id DESC")    {
          $SQL = "SELECT * FROM utenti";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                return array($dbd, $i);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getIscritti($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return array(null,0);
            }
    }
    /**
     * Salva un nuovo iscritto a database
     * @param MySqlConnection $DBConnection
     * @param type $post
     * @return int
     */
    public static function salvaIscritto(MySqlConnection $DBConnection,$post=array())   {
          try{
              if($post["id"]>0) {
                  $SQL = "SELECT password FROM utenti WHERE id=".$post["id"];
                  list($dbd,$i) = $DBConnection->exeSQL($SQL);
                  $recordset = $DBConnection->getResult($dbd,0);
                  if($post["password"]!=$recordset["password"])  {
                      $post["password"] = md5($post["password"]);
                  }
                  
                  $SQL = "UPDATE utenti SET ";
                  foreach ($post as $key => $value){
                        if($key!="password_conferma")
                            $SQL .= $key."='".Utility::cleanField($value)."',";
                  } 
                  $SQL = substr($SQL, 0,strlen($SQL)-1);
                  $SQL .= " WHERE id=".$post["id"];
                  $DBConnection->exeSQL($SQL);
                  
                  return $post["id"];
              }else{
                  $SQL = "INSERT INTO utenti(";
                    foreach ($post as $key => $value){
                        if($key!='id' && $key!="password_conferma")  {
                            $SQL .= $key.",";
                        } 
                    }
                    $SQL .= "data_iscrizione) VALUES (";
                    
                    foreach ($post as $key => $value){
                        if($key!='id' && $key!="password_conferma")  {
                            if($key=='password')    {
                                $SQL .= "'".md5($value)."',";
                            }else
                                $SQL .= "'".Utility::cleanField($value)."',";
                        }
                        
                    }
                    $SQL .= "NOW())";
                    $DBConnection->exeSQL($SQL);
                    $id=mysql_insert_id();
                    //echo $SQL."<br>".$id;
                    
                    //exit;
                    //  da sistemare l'ultimo utente inserito
                    /*$SQL = "SELECT max(id) AS IDUser FROM utenti";
                    list($dbd,$i) = $DBConnection->exeSQL($SQL);
                    $recordset = $DBConnection->getResult($dbd,0);*/
                    
                    $SQL = "INSERT INTO whishlist(id_iscritto,data_creazione,data_modifica) VALUES(".$id.",NOW(),NOW())";
                    $DBConnection->exeSQL($SQL);
                   
                   
                   
                    //return $recordset["IDUser"];
                    return $id;
                   
                    
              } 
          }catch(Exception $e)  {
               
               $DBConnection->LogError("ecommerce->salvaIscritto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return 0;
          }
      }
      /**
       * Cancella un iscritto
       * @param MySqlConnection $DBConnection
       * @param int $id
       * @return int
       */
       public static function deleteIscritto(MySqlConnection $DBConnection,$id)   {
           try{
                    $SQL = "DELETE FROM whishlist_prodotti WHERE id_whishlist IN (SELECT id from whishlist WHERE id_iscritto=$id)";
                    $DBConnection->exeSQL($SQL);
                    $SQL = "DELETE FROM whishlist WHERE id_iscritto=$id";
                    $DBConnection->exeSQL($SQL);
                    $SQL = "DELETE FROM utenti WHERE id=$id";
                    $DBConnection->exeSQL($SQL);

          }catch(Exception $e)  {
               
               $DBConnection->LogError("ecommerce->deleteIscritto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return 0;
          }
       }
       /**
        * 
        * @param MySqlConnection $DBConnection
        * @param type $andwhere
        * @param type $ordine
        * @return 
        */
       public static function getProdotti(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT p.*,c.id_categoria,c2.categoria,f.url_immagine as immagine_principale FROM prodotti p LEFT JOIN prodotti_categoria c ON p.id=c.id_prodotto AND c.principale=1 LEFT JOIN categorie c2 ON c2.id=c.id_categoria LEFT JOIN prodotti_immagini f ON p.id=f.id_prodotto AND f.main=1";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                return array($dbd, $i);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdotti($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return array(null,0);

            }
     }
     /**
        * Ottiene un particolare prodotto specificato nella where e se full impostato a true ne associa anche le varie caratteristiche non principali (quelle cioè non appartenti alla tabella prodotti e categorie)
        * @param MySqlConnection $DBConnection
        * @param type $andwhere
        * @param type $ordine
        * @param bool full 
        * @return mixed array
        */
       public static function getProdotto(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT p.*,c.id_categoria,c2.categoria,f.url_immagine as immagine_principale FROM prodotti p LEFT JOIN prodotti_categoria c ON p.id=c.id_prodotto AND c.principale=1 LEFT JOIN categorie c2 ON c2.id=c.id_categoria LEFT JOIN prodotti_immagini f ON p.id=f.id_prodotto AND f.main=1";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                $recordset = $DBConnection->getResult($dbd,0);
                return $recordset;
               
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdotto($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function getProdottiImmagini(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM prodotti_immagini";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
                
                
               
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdottiImmagini($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function salvaProdottoImmagine(MySqlConnection $DBConnection,  $id_prodotto, $url, $ordine)    {
          $SQL = "INSERT INTO prodotti_immagini(id_prodotto,url_immagine,main,ordine) VALUES ($id_prodotto,'$url',0,$ordine);";
          try{
                return $DBConnection->exeSQL($SQL);        
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->salvaProdottoImmagine($id_prodotto, $url, $ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
            }
    }
     
     public static function deleteFotoProdotto(MySqlConnection $DBConnection,$id)    {
          $SQL = "SELECT * FROM prodotti_immagini WHERE id=$id";
          list($dbd,$i) = $DBConnection->exeSQL($SQL); 
          $recordset = $DBConnection->getResult($dbd,0);
          
          
          $SQL = "DELETE FROM prodotti_immagini WHERE id=$id";  
          try{
                $DBConnection->exeSQL($SQL); 
                $SQL = "UPDATE prodotti_immagini SET ordine=ordine-1 WHERE id_prodotto=".$recordset["id_prodotto"]." AND ordine>=".$recordset["ordine"];  
                $DBConnection->exeSQL($SQL); 
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->deleteFotoProdotto($id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function deletepdfProdotto(MySqlConnection $DBConnection,$id)    {
          
          
          
          $SQL = "UPDATE prodotti SET pdf='' WHERE id=$id";  
          try{
                $DBConnection->exeSQL($SQL); 
                
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->deletepdfProdotto($id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     public static function getLuoghi(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM luoghi";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
                
                
               
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getLuoghi($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     public static function getProdottiLuoghi(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT l.id,l.luogo FROM prodotti_luoghi p JOIN luoghi l ON p.id_luogo=l.id";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
                
                
               
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdottiLuoghi($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function insertLuogo(MySqlConnection $DBConnection,$luogo)    {
          $SQL = "INSERT INTO luoghi(luogo) VALUES('".Utility::cleanField($luogo)."')";
          try{
                $DBConnection->exeSQL($SQL);
                $SQL = "SELECT max(id) as id_luogo,luogo FROM luoghi";
                list($dbd,$i) = $DBConnection->exeSQL($SQL);
                $recordset = $DBConnection->getResult($dbd,0);
                return $recordset["id_luogo"];

            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertLuogo($luogo)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            }
     }
     
     public static function insertProdottoLuogo(MySqlConnection $DBConnection,$id_luogo,$id_prodotto)    {
          $SQL = "INSERT INTO prodotti_luoghi(id_prodotto,id_luogo) VALUES ($id_prodotto,$id_luogo)";

          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertProdottoLuogo($id_luogo,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     
      public static function updateLuogo(MySqlConnection $DBConnection,$luogo,$id_luogo)    {
          $SQL = "UPDATE luoghi SET luogo='".Utility::cleanField($luogo)."' WHERE id=$id_luogo";
          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertProdottoLuogo($id_luogo,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     
     public static function insertProdottoCorrelato(MySqlConnection $DBConnection,$id_prodotto,$id_prodotto2)    {
          $SQL = "INSERT INTO prodotti_prodotti(id_prodotto,id_prodotto2) VALUES ($id_prodotto,$id_prodotto2)";

          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->inserProdottoCorrelato($id_prodotto,$id_prodotto2)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     
      public static function insertProdottoComposizione(MySqlConnection $DBConnection,$id_composizione,$id_prodotto)    {
          $SQL = "INSERT INTO prodotti_composizioni(id_composizione,id_prodotto) VALUES ($id_composizione,$id_prodotto)";

          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertProdottoComposizione($id_composizione,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }


     public static function insertStile(MySqlConnection $DBConnection,$stile)    {
          $SQL = "INSERT INTO stili(stile) VALUES('$stile')";
          try{
                $DBConnection->exeSQL($SQL);
                $SQL = "SELECT max(id) as id_stile FROM stili";
                list($dbd,$i) = $DBConnection->exeSQL($SQL);
                $recordset = $DBConnection->getResult($dbd,0);
                return $recordset["id_stile"];

            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertStile($stile)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            }
     }
      public static function updateStile(MySqlConnection $DBConnection,$stile,$id_stile)    {
          $SQL = "UPDATE stili SET stile='".Utility::cleanField($stile)."' WHERE id=$id_stile";
          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->updateStile($id_luogo,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     public static function insertProdottoStile(MySqlConnection $DBConnection,$id_stile,$id_prodotto)    {
          $SQL = "INSERT INTO prodotti_stili(id_prodotto,id_stile) VALUES ($id_prodotto,$id_stile)";

          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertProdottoStili($id_stile,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }

     public static function insertTipologia(MySqlConnection $DBConnection,$tipologia)    {
          $SQL = "INSERT INTO tipologie(tipologia) VALUES('".Utility::cleanField($tipologia)."')";
          try{
                $DBConnection->exeSQL($SQL);
                $SQL = "SELECT max(id) as id_tipologia,tipologia FROM tipologie";
                list($dbd,$i) = $DBConnection->exeSQL($SQL);
                $recordset = $DBConnection->getResult($dbd,0);
                return $recordset["id_tipologia"];

            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertTipologia($stile)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            }
     }
      public static function updateTipologia(MySqlConnection $DBConnection,$tipologia,$id_tipologia)    {
          $SQL = "UPDATE tipologie SET tipologia='".Utility::cleanField($tipologia)."' WHERE id=$id_tipologia";
          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->updateTipologia($id_luogo,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     public static function insertProdottoTipologia(MySqlConnection $DBConnection,$id_tipologia,$id_prodotto)    {
          $SQL = "INSERT INTO prodotti_tipologie(id_prodotto,id_tipologia) VALUES ($id_prodotto,$id_tipologia)";

          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertProdottoTipologie($id_stile,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     
     public static function insertTarget(MySqlConnection $DBConnection,$target,$testo_pagina)    {
          $SQL = "INSERT INTO target(target,testo_pagina) VALUES('".Utility::cleanField($target)."','".Utility::cleanField($testo_pagina)."')";
          try{
                $DBConnection->exeSQL($SQL);
                $SQL = "SELECT max(id) as id_target,target FROM target";
                list($dbd,$i) = $DBConnection->exeSQL($SQL);
                $recordset = $DBConnection->getResult($dbd,0);
                return $recordset["id_target"];

            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertTarget($target)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            }
     }
     public static function updateTarget(MySqlConnection $DBConnection,$key,$value,$id_target)    {
          $SQL = "UPDATE target SET $key='".Utility::cleanField($value)."' WHERE id=$id_target";
          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->updateTarget($target,$id_target)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     public static function insertProdottoTarget(MySqlConnection $DBConnection,$id_target,$id_prodotto)    {
          $SQL = "INSERT INTO prodotti_target(id_prodotto,id_target) VALUES ($id_prodotto,$id_target)";

          try{
                $DBConnection->exeSQL($SQL);
                return true;
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertProdottoTarget($id_prodotto,$id_target)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;

            }
     }
     
     public static function getTarget(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM target";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getTarget($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     public static function getProdottiTarget(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT t.id,t.target FROM prodotti_target p JOIN target t ON p.id_target=t.id";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdottiTarget($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function getStili(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM stili";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getStili($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function getProdottiStili(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT s.id,s.stile FROM prodotti_stili p JOIN stili s ON p.id_stile=s.id";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdottiStili($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
            }
     }
     
     public static function getTipologie(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM tipologie";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getTipologie($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
            }
     }
     
     public static function getProdottiTipologie(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT t.id,t.tipologia FROM prodotti_tipologie p JOIN tipologie t ON p.id_tipologia=t.id";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdottiTipologie($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function getProdottiCorrelati(MySqlConnection $DBConnection,$id_prodotto)    {
          $SQL = "SELECT id_prodotto,id_prodotto2 FROM prodotti_prodotti p WHERE p.id_prodotto=$id_prodotto OR id_prodotto2=$id_prodotto";
          
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdottiCorrelati($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function getProdottiComposizione(MySqlConnection $DBConnection,$id_prodotto)    {
          $SQL = "SELECT id_prodotto FROM prodotti_composizioni WHERE id_composizione=$id_prodotto";
          
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getProdottiCorrelati($id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;

            }
     }
     
     public static function getCategorie(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM categorie";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                return $DBConnection->exeSQL($SQL);
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->getCategorie($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
            }
     }
     
     /**
      * salva i dati db nella tabella prodotti
      * @param MySqlConnection $DBConnection
      * @param ProdottoAbstract $oProdotto
      * @return boolean
      */
     public static function insertProdotto(MySqlConnection $DBConnection,  ProdottoAbstract $oProdotto)    {
          try{
                $SQL = "INSERT INTO prodotti(";
                foreach(array_keys($oProdotto->getAttributi()) as $key){
                    if($key!=='id' && $key!=='immagine_principale' && $key!=='categoria' && $key!=='id_categoria' && strstr($key,"id_slider")===FALSE) 
                        $SQL .= $key.",";
                }
                $SQL .= "data_inserimento,data_modifica) VALUES (";
                foreach(array_keys($oProdotto->getAttributi()) as $key){
                    if($key!=='id' && $key!=='immagine_principale' && $key!=='categoria' && $key!=='id_categoria' && strstr($key,"id_slider")===FALSE) 
                        $SQL .= "'".nl2br(Utility::cleanField($oProdotto->$key))."',";
                }
                $SQL .= "NOW(),NOW())";
                $DBConnection->exeSQL($SQL);
                
                $SQL = "SELECT max(id) as id_prodotto FROM prodotti;";
                list($dbd,$i) = $DBConnection->exeSQL($SQL);
                $recordset = $DBConnection->getResult($dbd,0);
                    
                if(strlen($oProdotto->immagine_principale)>0)   {
                    $SQL2 = "INSERT INTO prodotti_immagini(id_prodotto,url_immagine,main,ordine) VALUES (".$recordset["id_prodotto"].",'".$oProdotto->immagine_principale."',1,1);";
                    $DBConnection->exeSQL($SQL2);
                }
                
                $SQL2 = "INSERT INTO prodotti_categoria(id_prodotto,id_categoria,principale) VALUES (".$recordset["id_prodotto"].",'".$oProdotto->id_categoria."',1);";
                $DBConnection->exeSQL($SQL2);
                
                /** parte per aggiungere il prodotto ai vari slider */
                foreach(array_keys($oProdotto->getAttributi()) as $key){
                    if(strstr($key,"id_slider")!==FALSE && $oProdotto->$key>0) {
                        $id_slider = str_replace("id_slider_","", $key);
                        $SQL = "SELECT max(ordine) as maxordine FROM slider_elementi WHERE id_slider=$id_slider;";
                        list($dbdM,$iM) = $DBConnection->exeSQL($SQL);
                        $recordset = $DBConnection->getResult($dbdM,0);
                        $ordine = $recordset["maxordine"]+1;
                        $SQL = "INSERT INTO slider_elementi(id_slider,id_prodotto,ordine) VALUES ($id_slider,".$recordset["id_prodotto"].",$ordine)";
                        $DBConnection->exeSQL($SQL);
                    }
                        
                }
                /** fine parte per gli slider*/
                return $recordset["id_prodotto"];
               
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->insertProdotto($oProdotto->nome)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return 0;
            }
     }
     /**
      * Aggiorna i dati a db nella tabella prodotti
      * @param MySqlConnection $DBConnection
      * @param ProdottoAbstract $oProdotto
      * @param string $urlUpload
      * @return boolean
      */
     public static function updateProdotto(MySqlConnection $DBConnection,  ProdottoAbstract $oProdotto)    {
        try{
                $SQL = "UPDATE prodotti set ";
                foreach(array_keys($oProdotto->getAttributi()) as $key){
                    if($key=="immagine_principale" && strlen($oProdotto->$key)>0){
                        $SQL2 = "DELETE FROM prodotti_immagini WHERE id_prodotto=".$oProdotto->id;
                        $DBConnection->exeSQL($SQL2);
                        $SQL2 = "INSERT INTO prodotti_immagini(id_prodotto,url_immagine,main,ordine) VALUES (".$oProdotto->id.",'".$oProdotto->immagine_principale."',1,1);";
                        $DBConnection->exeSQL($SQL2);
                    }else if($key!="immagine_principale" && $key!=='categoria' && $key!=='id_categoria' && strstr($key,"id_slider")===FALSE)
                        $SQL .= $key."='".nl2br(Utility::cleanField($oProdotto->$key))."',";
                }
                
                $SQL .= "data_modifica=NOW() WHERE id=".$oProdotto->id;
                $DBConnection->exeSQL($SQL);
                
                $SQL2 = "UPDATE prodotti_categoria SET id_categoria=".$oProdotto->id_categoria." WHERE id_prodotto=".$oProdotto->id.";";
                $DBConnection->exeSQL($SQL2);
                
                /** parte per aggiungere il prodotto ai vari slider */                
                foreach(array_keys($oProdotto->getAttributi()) as $key){               
                    if(strstr($key,"id_slider")!==FALSE) {
                        $id_slider = str_replace("id_slider_","", $key);
                        if($oProdotto->$key==0)  {
                            $SQL = "DELETE FROM slider_elementi WHERE id_slider=$id_slider AND id_prodotto=".$oProdotto->id;
                            $DBConnection->exeSQL($SQL);
                        }else{
                            $SQL = "SELECT * FROM slider_elementi WHERE id_slider=$id_slider AND id_prodotto=".$oProdotto->id;
                            list($dbdM,$iM) = $DBConnection->exeSQL($SQL);
                            if($iM==0)   {
                                $SQL = "SELECT max(ordine) as maxordine FROM slider_elementi WHERE id_slider=$id_slider;";
                                list($dbdM,$iM) = $DBConnection->exeSQL($SQL);
                                $recordset = $DBConnection->getResult($dbdM,0);
                                $ordine = $recordset["maxordine"]+1;
                                $SQL = "INSERT INTO slider_elementi(id_slider,id_prodotto,ordine) VALUES ($id_slider,".$oProdotto->id.",$ordine)";
                                $DBConnection->exeSQL($SQL);
                            }
                        }   
                    }     
                }
                /** fine parte per gli slider*/
                return true;
               
            }catch(Exception $e){
                $DBConnection->LogError("ecommerce.php->updateProdotto($oProdotto->nome)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return false;
            }
     }
     /**
      * Cancella un prodotto con tutte le sue correlazioni
      * @param MySqlConnection $DBConnection
      * @param type $id
      */
     public static function deleteProdotto(MySqlConnection $DBConnection,  $id)    {
         try{
                $DBConnection->beginTransaction();  
                $SQL = "DELETE FROM prodotti_immagini WHERE id_prodotto=".$id;
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti_categoria WHERE id_prodotto=".$id;
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti_composizioni WHERE id_prodotto=".$id." OR id_composizione=$id";
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti_lingue WHERE id_prodotto=".$id;
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti_luoghi WHERE id_prodotto=".$id;
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti_prodotti WHERE id_prodotto=".$id." OR id_prodotto2=$id";
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti_stili WHERE id_prodotto=".$id;
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti_target WHERE id_prodotto=".$id;
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM whishlist_prodotti WHERE id_prodotto=".$id;
                $DBConnection->exeSQL($SQL); 
                $SQL = "DELETE FROM prodotti WHERE id=".$id;
                $DBConnection->exeSQL($SQL); 
                $DBConnection->CommitTransaction();
               
        }catch(Exception $e){
            $DBConnection->RollBackTransaction();
            $DBConnection->LogError("ecommerce.php->deleteProdotto($id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
        }
     }
     
     public static function getProdottiWhishlist(MySqlConnection $DBConnection,$id_user)    {
          try{
                        
                $SQL = "SELECT p . * , f.url_immagine AS immagine_principale, c.id_categoria, c2.categoria, f.url_immagine AS immagine_principale
                        FROM whishlist_prodotti wp
                        JOIN whishlist w ON w.id = wp.id_whishlist
                        JOIN prodotti p ON wp.id_prodotto = p.id
                        LEFT JOIN prodotti_categoria c ON p.id = c.id_prodotto
                        AND c.principale =1
                        LEFT JOIN categorie c2 ON c2.id = c.id_categoria
                        LEFT JOIN prodotti_immagini f ON p.id = f.id_prodotto
                        AND f.main =1
                        WHERE w.id_iscritto =".$id_user;
                return $DBConnection->exeSQL($SQL); 
                
               
        }catch(Exception $e){
            
            $DBConnection->LogError("ecommerce.php->getProdottiWhishlist($id_user)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
        }
     }
     
     public static function insertProdottoToWhishlist(MySqlConnection $DBConnection,$id_whishlist,$id_prodotto,$qta=1)    {
          try{
                
                $SQL = "INSERT INTO whishlist_prodotti(id_whishlist,id_prodotto,qta,data_inserimento,data_modifica) VALUES ($id_whishlist,$id_prodotto,$qta,NOW(),NOW());";
                $DBConnection->exeSQL($SQL); 
                return true;
               
        }catch(Exception $e){
            
            $DBConnection->LogError("ecommerce.php->insertProdottoToWhishlist($id_whishlist,$id_prodotto,$qta)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return false;
        }
     }
     
     public static function removeProdottoToWhishlist(MySqlConnection $DBConnection,$id_whishlist,$id_prodotto)    {
          try{
                
                $SQL = "DELETE FROM whishlist_prodotti WHERE id_whishlist=$id_whishlist AND id_prodotto=$id_prodotto";
                $DBConnection->exeSQL($SQL); 
                
               
        }catch(Exception $e){
            
            $DBConnection->LogError("ecommerce.php->removeProdottoToWhishlistt($id_whishlist,$id_prodotto)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return false;
        }
     }
     
      public static function getSlider(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM slider";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;

        try{
                return $DBConnection->exeSQL($SQL);
        }catch(Exception $e){
            $DBConnection->LogError("ecommerce.php->getSlider($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return null;
        }
     }
     public static function getSliderElementiBase(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT s.nome, se.* FROM slider s
                    JOIN slider_elementi se ON s.id = se.id_slider";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;

        try{
                return $DBConnection->exeSQL($SQL);
        }catch(Exception $e){
            $DBConnection->LogError("ecommerce.php->getSliderElementiBase($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return null;
        }
     }
      public static function getSliderElementi(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT s.nome, se . * ,p.id as id_prodotto, p.nome AS nomeprodotto,p.descr_breve,pi.url_immagine,n.titolo,c.categoria,pc.id_categoria FROM slider s
                    JOIN slider_elementi se ON s.id = se.id_slider
                    LEFT JOIN prodotti p ON p.id = se.id_prodotto
                    AND se.id_prodotto IS NOT NULL JOIN prodotti_immagini pi ON pi.id_prodotto=p.id AND main=1 JOIN prodotti_categoria pc ON pc.id_prodotto=p.id JOIN categorie c ON c.id=pc.id_categoria
                    LEFT JOIN news n ON n.id = se.id_news
                    AND se.id_news IS NOT NULL";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;

        try{
                return $DBConnection->exeSQL($SQL);
        }catch(Exception $e){
            $DBConnection->LogError("ecommerce.php->getSliderElementi($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return null;
        }
     }
     
     public static function getSliderElementiNews(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT s.nome,se.id as se_id,se.id_slider, se.ordine as ordineslider,n.* FROM slider s
                    JOIN slider_elementi se ON s.id = se.id_slider
                    JOIN news n ON n.id = se.id_news
                    AND se.id_news IS NOT NULL";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;

        try{
                return $DBConnection->exeSQL($SQL);
        }catch(Exception $e){
            $DBConnection->LogError("ecommerce.php->getSliderElementi($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return null;
        }
     }
     
     public static function getWishlist(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT w.id as id_whishlist,u.* FROM whishlist w JOIN utenti u ON w.id_iscritto=u.id";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;

        try{
                return $DBConnection->exeSQL($SQL);
        }catch(Exception $e){
            $DBConnection->LogError("ecommerce.php->getWishlist($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return null;
        }
     }
     
     public static function getWishlistProdotti(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT wp.*,p.*,u.id as id_utente,u.nome as nome_utente,u.cognome as cognome_utente,pi.url_immagine FROM `whishlist` w JOIN utenti u ON w.id_iscritto=u.id JOIN whishlist_prodotti wp ON w.id=wp.id_whishlist JOIN prodotti p ON wp.id_prodotto=p.id JOIN prodotti_immagini pi ON p.id=pi.id_prodotto WHERE pi.main=1";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
        try{
                return $DBConnection->exeSQL($SQL);
        }catch(Exception $e){
            $DBConnection->LogError("ecommerce.php->getWishlistProdotti($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
            return null;
        }
     }
}
?>