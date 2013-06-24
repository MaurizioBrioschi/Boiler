<?php
    
 /**
 * Classe contenente le funzioni per ricavare i dati dal DB
  * @version 1.0
 */
  class DBUtility {
      
      /**
       * Ottiene tutti i permessi per tutte le tipologie di utente
       * @param MySqlConnection $DBCOnnnection
       * @param string $where
       * @param string $ordine
       * @return mixed array
       */
      public static function getServices(MySqlConnection $DBConnection,$where=null,$ordine="ORDER BY m.IDSection ASC,s.IDService ASC")    {
          $SQL = "SELECT m.IDSection,m.Name,s.IDService,s.ServiceName,s.Link FROM tblmanagersections m JOIN tblmanagerservices s ON m.IDSection=s.IDSection";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                return array($dbd, $i);
            }catch(Exception $e){
                $DBConnection->LogError("DBUtility.php->getServices($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return array(null,0);

            }
      }
      /**
       * Ottiene tutti i permessi per un gruppo
       * @param MySqlConnection $DBCOnnnection
       * @param string $where
       * @param string $ordine
       * @return mixed array
       */
      public static function getServicesGruppo(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT m.IDSection,m.Name,s.IDService,s.ServiceName,s.Link FROM tblmanagersections m JOIN tblmanagerservices s ON m.IDSection=s.IDSection JOIN tblmanagerusertypeservices t ON s.IDService=t.IDService";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                return array($dbd, $i);
            }catch(Exception $e){
                $DBConnection->LogError("DBUtility.php->getServicesGruppo($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return array(null,0);

            }
      }
      /**
       * Ottiene i permessi di un gruppo a livello di pagina
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $ordine
       * @return mixed array
       */
      public static function getNodiPermessi(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM nodipermessi";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                return array($dbd, $i);
            }catch(Exception $e){
                $DBConnection->LogError("DBUtility.php->getNodiPermessi($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return array(null,0);

            }
      }
      
      public static function getNodi(MySqlConnection $DBConnection,$where=null,$ordine=null)    {
          $SQL = "SELECT * FROM nodi";
          if($where!=null) $SQL .= " ".$where;
          if($ordine!=null) $SQL .= " ".$ordine;
          
          try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                return array($dbd, $i);
            }catch(Exception $e){
                $DBConnection->LogError("DBUtility.php->getNodiPermessi($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return array(null,0);
            }
      }
      
      /**
       * salve tutti i dati per un gruppo di utenti provenienti dalla post del form
       * @param MySqlConnection $DBConnection
       * @param array $post
       * @return int
       */
      public static function salvaGruppo(MySqlConnection $DBConnection,$post=array())   {
          try{
              $DBConnection->beginTransaction();
              $IDType=$post["IDType"];
              if($post["IDType"]>0) {
                  $SQL = "UPDATE tblmanagerusertype SET Name='".Utility::cleanField($post["nome"])."' WHERE IDType=$IDType";
                  $DBConnection->exeSQL($SQL);
                  $SQL = "DELETE FROM tblmanagerusertypeservices WHERE IDType=".$post["IDType"];
                  $DBConnection->exeSQL($SQL);
                  $SQL = "DELETE FROM nodipermessi WHERE IDType=".$post["IDType"];
                  $DBConnection->exeSQL($SQL);
              }else{
                  $SQL = "INSERT INTO tblmanagerusertype(Name) VALUES('".Utility::cleanField($post["nome"])."');"; 
                  $DBConnection->exeSQL($SQL);
                  $SQL = "SELECT max(IDType) AS IDType FROM tblmanagerusertype WHERE Name='".$post["nome"]."';"; 
                  list($dbd,$i) = $DBConnection->exeSQL($SQL);
                  $recordset = $DBConnection->getResult($dbd,0);
                  $IDType = $recordset["IDType"];
              }
              
              foreach ($post as $key => $value){
                  if(strstr($key,"s_")!==FALSE && $value>0) {
                       //inserisco i servizi
                       $SQL = "INSERT INTO tblmanagerusertypeservices(IDType,IDService) VALUES($IDType,".str_replace("s_", "", $key).");";
                       $DBConnection->exeSQL($SQL);
                  }else if(strstr($key,"n_")!==FALSE && $value>0)   {
                       //inserisco i permessi sulla pagina
                       $SQL = "INSERT INTO nodipermessi(id_nodo,IDType,Attivo) VALUES(".str_replace("n_", "", $key).",$IDType,1)";
                       $DBConnection->exeSQL($SQL);
                  }
              }
              
              $DBConnection->CommitTransaction();
              return $IDType;
          }catch(Exception $e)  {
               $DBConnection->RollBackTransaction();
               $DBConnection->LogError("DBUtility.php->salvaGruppo()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return 0;
          }
          
          
      }
       /**
        * Cancella un gruppo
        * @param MySqlConnection $DBConnection
        * @param type $IDType
        * @return int
        */
       public static function deleteGruppo(MySqlConnection $DBConnection,$IDType)   {
          try{
              $DBConnection->beginTransaction();  
              
              $SQL = "DELETE FROM tblmanagerusertypeservices WHERE IDType=$IDType";
              $DBConnection->exeSQL($SQL);
              $SQL = "DELETE FROM nodipermessi WHERE IDType=$IDType";
              $DBConnection->exeSQL($SQL);
              $SQL = "DELETE FROM tblmanagerusertype WHERE IDType=$IDType";
              $DBConnection->exeSQL($SQL);
              $DBConnection->CommitTransaction();
              return $IDType;
          }catch(Exception $e)  {
               $DBConnection->RollBackTransaction();
               $DBConnection->LogError("DBUtility.php->deleteGruppo()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return 0;
          }
          
          
      }
      
      /**
       * Ottiene tutti i nodi del sito
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param int $livellomax
       * @return mixed array
       * 
       */
      public static function getAlbero(MySqlConnection $DBConnection, $where=null,$livellomax=0)    {
          
          
          $SQL = "SELECT n.id as idpadre,n.padre,n.id_menu as id_menupadre,n.online as onlinepadre,n.libero as liberopadre,n.ordine as ordinepadre,n.livello as livellopadre,n.nome as nomepadre";
          for($j=0;$j<$livellomax;$j++) {
              $SQL .= ",n$j.id as idfiglio$j,n$j.id_menu as id_menufiglio$j,n$j.online as onlinefiglio$j,n$j.libero as liberofiglio$j,n$j.ordine as ordinefiglio$j,n$j.livello as livellofiglio$j,n$j.nome as nomefiglio$j";
          }
          
          $SQL .= " FROM nodi n";
          $tabellapadre = "n";
          
              for($j=0;$j<$livellomax;$j++) {
                  $SQL .= " LEFT JOIN nodi n$j ON n$j.padre=$tabellapadre.id";
                  $tabellapadre = "n$j";
              }
          
          
          if($where!=null) $SQL .= " ".$where;
          $SQL .= " ORDER BY n.Ordine ASC, n.id ASC";
          
          for($j=0;$j<$livellomax;$j++) {
              $SQL .= ",n$j.Ordine ASC, n$j.id ASC";
          }
          
            try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                return array($dbd, $i);
            }catch(Exception $e){
                $DBConnection->LogError("DBUtility.php->getAlbero($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return array(null,0);

            }
      }
      /**
       * Ritorno il livello massimo di nodi figlio, 0 è il primo livello
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order 
       * @return int 
       */
      public static function getLivelloNodiMax(MySqlConnection $DBConnection,$where=null,$order=null)   {
            $SQL = "select max(livello) as massimolivello from nodi ";
            if($where!=null) $SQL .= $where." ";
            $SQL .= $order;
            try{
                list($dbd, $i) = $DBConnection->exeSQL($SQL);
                $recordset = $DBConnection->getResult($dbd,0);
                return $recordset["massimolivello"];
            }catch(Exception $e){
                $DBConnection->LogError("DBUtility.php->getLivelloNodiMax($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"Mool");
                return array(null,0);

            }
      }
      /**
       * Salva a database una nuova pagina
       * @param MySqlConnection $DBConnection
       * @param Pagina $pagina
       * @param int $IDType
       * @return Pagina 
       */
      public static function insertNewPage(MySqlConnection $DBConnection,Pagina $pagina,Nodo $nodo,$IDType=1)    {
          /**
           * inserisco il nodo
           */
          $SQL = "SELECT max(ordine) as ordine from nodi WHERE padre=".$nodo->padre;
          
          
          $DBConnection->beginTransaction();
          try   {
              /**
               * inserisco il nodo
               */
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $ordine = 0;
              $recordset = $DBConnection->getResult($dbd,0); 
              if(strlen($recordset["ordine"])>0)  {               
                    $ordine = $recordset["ordine"] + 1;
              }
              
              
              $livello = 1;
              if($nodo->id>0)   {
                 $SQL = "SELECT Livello from nodi";
                 $SQL .= " WHERE id=".$nodo->id;
                 list($dbd,$i) = $DBConnection->exeSQL($SQL);
                 $recordset = $DBConnection->getResult($dbd,0);
                 $livello = $recordset["Livello"];
                 $livello++;
              }
              
              $SQL = "INSERT INTO nodi(padre,online,libero,ordine,Livello,nome,id_menu) VALUES (".$nodo->padre.",".$pagina->online.",0,$ordine,$livello,'".  Utility::cleanField($pagina->nome)."',".$nodo->id_menu.");";
              
                  
              $DBConnection->exeSQL($SQL);
              $SQL = "SELECT max(id) as id_nodo from nodi;";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0); 
              $pagina->id_nodo = $recordset["id_nodo"];
              /**
               * inserisco la pagina
               */
              $flagTemplate = true;
              $SQL = "INSERT INTO pagine(";
              foreach(array_keys($pagina->getAttributi()) as $key){
                    $SQL .= $key.",";
                    if($key=="template")
                        $flagTemplate = false;
              }
              if($flagTemplate) $SQL .="template,";
              $SQL .= "data_creazione,data_modifica) VALUES (";
              foreach(array_keys($pagina->getAttributi()) as $key){
                    $SQL .= "'".  Utility::cleanField($pagina->$key)."',";
              }
              if($flagTemplate) $SQL .="pagina,";
              $SQL .= "NOW(),NOW());";
              $DBConnection->exeSQL($SQL);
              $SQL = "SELECT max(id) as id from pagine;";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $pagina->id = $recordset["id"];
              
              $SQL = "INSERT INTO nodipermessi(id_nodo,IDType,Attivo) VALUES (".$pagina->id_nodo.",$IDType,1);";
              $DBConnection->exeSQL($SQL);
              $DBConnection->CommitTransaction();
              return $pagina;
          }catch(Exception $e){
              $DBConnection->RollBackTransaction();
                $DBConnection->LogError("DBUtility.php->insertNewPage($pagina->nome)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
          }
          
          
      }
      /**
       * Aggiorna il nodo ed eventualmente la relariva pagina
       * @param MySqlConnection $DBConnection
       * @param Nodo $nodo
       * @param Pagina $pagina
       * @return null|\Nodo
       */
      public static function updateNodo(MySqlConnection $DBConnection,Nodo $nodo,Pagina $pagina=null)    {          
          try   {  
              $SQL = "UPDATE nodi SET ";
              foreach(array_keys($nodo->getAttributi()) as $key){
                    $SQL .= $key."='".Utility::cleanField($nodo->$key)."',";
              }
              $SQL = substr($SQL, 0,  strlen($SQL)-1);
              $SQL .= " WHERE id=".$nodo->id;
              $DBConnection->exeSQL($SQL);
              if($pagina!=null) {
              $SQL = "UPDATE pagine SET ";
                foreach(array_keys($pagina->getAttributi()) as $key){
                      $SQL .= $key."='".Utility::cleanField($pagina->$key)."',";

                }
                $SQL .= "data_modifica=NOW()";
                $SQL .= " WHERE id_nodo=".$pagina->id_nodo;
                $DBConnection->exeSQL($SQL);
              }
              
              return $nodo;
          }catch(Exception $e){
              
                $DBConnection->LogError("DBUtility.php->updateNodo($nodo->id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
          }  
      }
      /**
       * Aggiorna a db una pagina
       * @param MySqlConnection $DBConnection
       * @param Pagina $pagina
       * @return Pagina 
       */
      public static function updatePage(MySqlConnection $DBConnection,Pagina $pagina)    {          
          try   {  
              $SQL = "UPDATE pagine SET ";
              foreach(array_keys($pagina->getAttributi()) as $key){
                    $SQL .= $key."='".Utility::cleanField($pagina->$key)."',";
                    
              }
              $SQL .= "data_modifica=NOW()";
              $SQL .= " WHERE id=".$pagina->id;
              $DBConnection->exeSQL($SQL);
              
              $SQL = "UPDATE nodi SET nome='".$pagina->nome."',online=".$pagina->online." WHERE id=".$pagina->id_nodo;
              $DBConnection->exeSQL($SQL);
              
              return $pagina;
          }catch(Exception $e){
              
                $DBConnection->LogError("DBUtility.php->updatePage($pagina->id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
          }  
      }
      /**
       * Ottiene i dati della tabella pagine
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return type 
       */
      public static function getPage(MySqlConnection $DBConnection,$where=null,$order=null)    {
         
          
          try{
              $SQL = "SELECT p.*,n.padre,n.id_menu FROM pagine p JOIN nodi n ON p.id_nodo=n.id";
              if($where!=null) $SQL .= " ".$where;
              if($order!=null) $SQL .= " ".$order;
              
            
              return $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->getPage($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");

          }
      }
      /**
       * Cancella la una pagina e il relativo nodo
       * @param MySqlConnection $DBConnection
       * @param int $ID 
       */
      public static function deletePage(MySqlConnection $DBConnection,$ID)  {
          try{
              $DBConnection->beginTransaction();
              $SQL = "DELETE FROM paragrafi WHERE id_pagina=(select id from pagine where id_nodo=$ID)";
              $DBConnection->exeSQL($SQL);
              $SQL = "DELETE FROM pagine WHERE id_nodo=$ID";
              $DBConnection->exeSQL($SQL);
              $SQL = "SELECT id FROM nodi WHERE padre=$ID";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              for($j=0;$j<$i;$j++)  {
                  $recordset = $DBConnection->getResult($dbd,$j);
                  DBUtility::deletePage($DBConnection, $recordset["id"]);
              }
              $SQL = "DELETE FROM nodi WHERE id=$ID";
              $DBConnection->exeSQL($SQL);
              $DBConnection->CommitTransaction();
          }catch(Exception $e)  {
              $DBConnection->RollBackTransaction();
              $DBConnection->LogError("DBUtility.php->deletePage($ID)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");

          }
      }
      /**
       * Inserisce un nuovo paragrafo
       * @param MySqlConnection $DBConnection
       * @param Paragrafo $paragrafo 
       */
      public static function insertNewParagrafo(MySqlConnection $DBConnection, Paragrafo $paragrafo)    {
          try   {
              $SQL = "INSERT INTO paragrafi(";
              foreach(array_keys($paragrafo->getAttributi()) as $key){
                    $SQL .= $key.",";
              }
              $SQL .= "data_creazione,data_modifica) VALUES (";
              foreach(array_keys($paragrafo->getAttributi()) as $key){
                    $SQL .= "'".  $paragrafo->$key."',";
              }
              $SQL .= "NOW(),NOW());";
              $DBConnection->exeSQL($SQL);
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->insertNewParagrafo($paragrafo->titolo)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               
          }  
      }
      /**
       * Modifica i dati a db di un paragrafo
       * @param MySqlConnection $DBConnection
       * @param Paragrafo $paragrafo 
       */
      public static function updateParagrafo(MySqlConnection $DBConnection, Paragrafo $paragrafo)    {
          try   {
              $SQL = "UPDATE paragrafi SET ";
              foreach(array_keys($paragrafo->getAttributi()) as $key){
                    $SQL .= $key."='".$paragrafo->$key."',";
              }
              
              $SQL .= "data_modifica=NOW()";
              $SQL .= " WHERE id=".$paragrafo->id;
              $DBConnection->exeSQL($SQL);
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->updateParagrafo($paragrafo->titolo)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               
          }  
      }
      /**
       * Ottiene i paragrafi in forma di lista di recordset
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return mixed 
       */
      public static function getParagrafi(MySqlConnection $DBConnection,$where=null,$order=null)    {
          try   {
              $SQL = "SELECT * FROM paragrafi";
              if($where!=null)  $SQL .= " ".$where;
              if($order!=null)  $SQL .= " ".$order;
              
              return $DBConnection->exeSQL($SQL);
              
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->getParagrafi($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               return null;
          }  
      }
      /**
       * cancella un paragrafo secondo la condizione $where
       * ATTENZIONE CHE SE NON SI METTE LA WHERE CANCELLA TUTTI I PARAGRAFI
       * @param MySqlConnection $DBConnection
       * @param string $where 
       */
      public static function deleteParagrafo(MySqlConnection $DBConnection,$where=null) {
          try   {
              $SQL = "DELETE FROM paragrafi";
              if($where!=null)  $SQL .= " ".$where;
              $DBConnection->exeSQL($SQL);
              
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->deleteParagrafo($where)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               return null;
          }  
      }
      /**
       * Ottine gli attributi dei paragrafi
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return mixed 
       */
      public static function getAttributiParagrafo(MySqlConnection $DBConnection,$where=null,$order=null)    {
          try   {
              $SQL = "SELECT pa.id,pa.id_attributo,ta.nome as tipiattributonome,ta.descrizione as tipiattributodescrizione,av.id as idattributo,av.nome, av.valore,av.descrizione FROM paragrafoattributi pa LEFT JOIN attributovalori av ON av.id_paragrafoattributo=pa.id LEFT JOIN tipiattributo ta ON ta.id=pa.id_attributo";
              if($where!=null)  $SQL .= " ".$where;
              if($order!=null)  $SQL .= " ".$order;
              
              return $DBConnection->exeSQL($SQL);
              
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->getAttributiParagrafo($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               return null;
          }  
      }
      
      /** 
       * Inserisce una nuova gallery a database e ne restituisce l'id
       * @param MySqlConnection $DBConnection
       * @param string $path
       * @param string $nome
       * @param string $descrizione
       * @param int $id_paragrafo
       * @return int 
       */
      public static function insertGallery(MySqlConnection $DBConnection,$nome,$descrizione,$id_paragrafo)    {
          try{
              $DBConnection->beginTransaction();
              $SQL = "UPDATE paragrafoattributi SET ordine=ordine+1 WHERE id_paragrafo=$id_paragrafo";
              $DBConnection->exeSQL($SQL);
              $SQL = "INSERT INTO paragrafoattributi(id_paragrafo,id_attributo,ordine,data_creazione,data_modifica) VALUES ($id_paragrafo,1,1,NOW(),NOW())";
              $DBConnection->exeSQL($SQL);
              $SQL = "SELECT id FROM paragrafoattributi WHERE id_paragrafo=$id_paragrafo AND ordine=1";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0);
              $SQL = "INSERT INTO attributovalori(id_paragrafoattributo,nome,valore) VALUES (".$recordset["id"].",'GalleryNome','".Utility::cleanField($nome)."');";
              $DBConnection->exeSQL($SQL);
              $SQL = "INSERT INTO attributovalori(id_paragrafoattributo,nome,valore) VALUES (".$recordset["id"].",'GalleryDescrizione','".Utility::cleanField($descrizione)."');";
              $DBConnection->exeSQL($SQL);
              $DBConnection->CommitTransaction();
              return $recordset["id"];
          }catch(Exception $e)  {
              $DBConnection->RollBackTransaction();
              $DBConnection->LogError("DBUtility.php->insertGallery($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
              return 0;
          }
      }
      /**
       * Cancella l'attributo del paragrafo con id=$id
       * @param MySqlConnection $DBConnection
       * @param int $id 
       */
      public static function deleteAttributoParagrafo(MySqlConnection $DBConnection,$id)    {
          try{
              $DBConnection->beginTransaction();
              $SQL = "DELETE FROM attributovalori WHERE id_paragrafoattributo=$id";
              $DBConnection->exeSQL($SQL);
              $SQL = "DELETE FROM paragrafoattributi WHERE id=$id";
              $DBConnection->exeSQL($SQL);
              $DBConnection->CommitTransaction();
              
          }catch(Exception $e)  {
              $DBConnection->RollBackTransaction();
              $DBConnection->LogError("DBUtility.php->deleteGallery($id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
              
          }
      }
       /**
        * Aggiorna il campo nome e descrizione di una gallery
        * @param MySqlConnection $DBConnection
        * @param string $nome
        * @param string $descrizione
        * @param int $id 
        */
       public static function updateGallery(MySqlConnection $DBConnection,$nome,$descrizione,$id)    {
          try{
              $DBConnection->beginTransaction();
              
              $SQL = "UPDATE paragrafoattributi SET data_modifica=NOW() WHERE id=$id";
              $DBConnection->exeSQL($SQL);
              $SQL = "UPDATE attributovalori SET valore='".Utility::cleanField($nome)."' WHERE nome='GalleryNome' AND id_paragrafoattributo=$id";
              $DBConnection->exeSQL($SQL);
              $SQL = "UPDATE attributovalori SET valore='".Utility::cleanField($descrizione)."' WHERE nome='GalleryDescrizione' AND id_paragrafoattributo=$id";
              $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0);
              
              $DBConnection->CommitTransaction();
              
          }catch(Exception $e)  {
              $DBConnection->RollBackTransaction();
              $DBConnection->LogError("DBUtility.php->insertGallery($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
             
          }
      }
      /**
       * Inserisce una nuova foto per la gallery
       * @param MySqlConnection $DBConnection
       * @param int $id_paragrafoattributo
       * @param string $nome
       * @param string $valore 
       */
      public static function insertNewPhoto(MySqlConnection $DBConnection,$id_paragrafoattributo,$nome,$valore){
          try{
              $SQL = "INSERT INTO attributovalori(id_paragrafoattributo,nome,valore) VALUES ($id_paragrafoattributo,'$nome','".Utility::cleanField($valore)."');";
              $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->insertNewPhoto($id_paragrafoattributo,$nome,$valore)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      
      /**
       * Cancella l'attributo con id=$id
       * @param MySqlConnection $DBConnection
       * @param int $id 
       */
      public static function deleteAttributoValore(MySqlConnection $DBConnection,$id)   {
          try{
              $SQL = "DELETE FROM attributovalori WHERE id=$id";
              $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->deletephotogallery($id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Aggiorna la descrizione dell'attributo
       * @param MySqlConnection $DBConnection
       * @param int $id
       * @param string $descrizione 
       */
      public static function updateAttributoDescrizione(MySqlConnection $DBConnection,$id,$descrizione)   {
          try{
              $SQL = "UPDATE attributovalori SET descrizione='".Utility::cleanField($descrizione)."' WHERE id=$id";
              $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->updateAttributoDescrizione($id,$descrizione)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Sposta l'ordine di un nodo dell'incremento $incremento
       * @param MySqlConnection $DBConnection
       * @param int $id
       * @param int $incremento 
       */
      public static function spostaNodo(MySqlConnection $DBConnection,$id,$incremento)    {
          try{
              $SQL = "SELECT * FROM nodi WHERE id=$id";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0);
              if($incremento>0) {
                $SQL = "UPDATE nodi SET ordine=ordine+$incremento WHERE id=$id";
                $DBConnection->exeSQL($SQL);
                $SQL = "UPDATE nodi SET ordine=ordine-$incremento WHERE padre=".$recordset["padre"]." AND id<>$id AND ordine=".($recordset["ordine"]+$incremento).";";
                $DBConnection->exeSQL($SQL);
             }else{
                $incremento = $incremento * (-1);
                $SQL = "UPDATE nodi SET ordine=ordine-$incremento WHERE id=$id";
                $DBConnection->exeSQL($SQL);               
                $SQL = "UPDATE nodi SET ordine=ordine+$incremento WHERE padre=".$recordset["padre"]." AND id<>$id AND ordine=".($recordset["ordine"]-$incremento).";";
                $DBConnection->exeSQL($SQL);
             }
              
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->spostaNodo($id,$incremento)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /******
       * GESTIONE NEWS
       */
      
      /**
       * Ottiene le news con i relativi paragrafi
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return mixed  
       */
      public static function getNews(MySqlConnection $DBConnection,$where=null, $order=null)    {
          try{
              $SQL = "select n.*,r.id_category,c.nome as nome from news n LEFT JOIN newscategoryrelation r ON n.id=r.id_news JOIN newscategory c ON c.id=r.id_category";
              if($where!=null)  $SQL .= " ".$where;
              if($order!=null)  $SQL .= " ".$order;
              
              return $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->getNews($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Ottiene i paragrafi in forma di lista di recordset
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return mixed 
       */
      public static function getNewsParagrafi(MySqlConnection $DBConnection,$where=null,$order=null)    {
          try   {
              $SQL = "SELECT * FROM newsparagrafi";
              if($where!=null)  $SQL .= " ".$where;
              if($order!=null)  $SQL .= " ".$order;
              
              return $DBConnection->exeSQL($SQL);
              
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->getNewsParagrafi($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               return null;
          }  
      }
      
      /**
       * Salva a database una nuova news
       * @param MySqlConnection $DBConnection
       * @param News $news
       * @param int $ddlCategory
       * @return News 
       */
      public static function insertNews(MySqlConnection $DBConnection,News $news,$ddlCategory)    {
          /**
           * inserisco il nodo
           */
          $SQL = "SELECT max(ordine) as ordine from news";
          
          
          $DBConnection->beginTransaction();
          try   {
              /**
               * inserisco il nodo
               */
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $ordine = 1;
              $recordset = $DBConnection->getResult($dbd,0); 
              if(strlen($recordset["ordine"])>0 && $i>0)  {               
                    $ordine = $recordser["ordine"] + 1;
              }
              
              $news->ordine = $ordine;
              $SQL = "INSERT INTO news(";
              foreach(array_keys($news->getAttributi()) as $key){
                  if(strstr($key,"id_slider")===FALSE)
                    $SQL .= $key.",";
              }
              
              $SQL .= "data_creazione,data_modifica) VALUES (";
              foreach(array_keys($news->getAttributi()) as $key){
                  if(strstr($key,"id_slider")===FALSE)
                    $SQL .= "'".  Utility::cleanField($news->$key)."',";
              }
              $SQL .= "NOW(),NOW());";
              $DBConnection->exeSQL($SQL);
              $SQL = "SELECT max(id) as id from news;";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0);
              $news->id = $recordset["id"];
              foreach(array_keys($news->getAttributi()) as $key){
                  if(strstr($key,"id_slider")!=FALSE)   {
                      $id_slider = str_replace("id_slider_","", $key);
                       $SQL = "SELECT max(ordine) as maxordine FROM slider_elementi WHERE id_slider=$id_slider;";
                       list($dbdM,$iM) = $DBConnection->exeSQL($SQL);
                       $recordset = $DBConnection->getResult($dbdM,0);
                       $ordine = $recordset["maxordine"]+1;
                      $SQL = "INSERT INTO slider_elementi(id_slider,id_news,ordine) VALUES ($id_slider,".$news->id.",$ordine)";
                      $DBConnection->exeSQL($SQL);
                  }
                     
              }
              
              
              $SQL = "INSERT INTO newscategoryrelation(id_category,id_news) VALUES($ddlCategory,".$news->id.");";
              $DBConnection->exeSQL($SQL);
              
              $DBConnection->CommitTransaction();
              return $news;
          }catch(Exception $e){
              $DBConnection->RollBackTransaction();
              $DBConnection->LogError("DBUtility.php->insertNews($news->titolo)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
          }  
      }     
      /**
       * Aggiorna a db una news
       * @param MySqlConnection $DBConnection
       * @param News $news
       * @param int $ddlCategory
       * @return News 
       */
      public static function updateNews(MySqlConnection $DBConnection,News $news,$ddlCategory)     {          
          try   {  
              $SQL = "UPDATE news SET ";
              foreach(array_keys($news->getAttributi()) as $key){
                   if(strstr($key,"id_slider")===FALSE) 
                        $SQL .= $key."='".Utility::cleanField($news->$key)."',";                    
              }
              $SQL .= "data_modifica=NOW()";
              $SQL .= " WHERE id=".$news->id;
              $DBConnection->exeSQL($SQL);
              if($ddlCategory!=null)    {
                  $SQL = "DELETE FROM newscategoryrelation WHERE id_news=".$news->id;
                  $DBConnection->exeSQL($SQL);

                  $SQL = "INSERT INTO newscategoryrelation(id_category,id_news) VALUES($ddlCategory,".$news->id.");";
                  $DBConnection->exeSQL($SQL);
              }
              foreach(array_keys($news->getAttributi()) as $key){
                if(strstr($key,"id_slider")!==FALSE) {
                          $id_slider = str_replace("id_slider_","", $key);
                          if($news->$key==0)  {
                              $SQL = "DELETE FROM slider_elementi WHERE id_slider=$id_slider AND id_news=".$news->id;
                              $DBConnection->exeSQL($SQL);
                          }else{
                              $SQL = "SELECT * FROM slider_elementi WHERE id_slider=$id_slider AND id_news=".$news->id;
                              list($dbdM,$iM) = $DBConnection->exeSQL($SQL);
                              if($iM==0)   {
                                  $SQL = "SELECT max(ordine) as maxordine FROM slider_elementi WHERE id_slider=$id_slider;";
                                  list($dbdM,$iM) = $DBConnection->exeSQL($SQL);
                                  $recordset = $DBConnection->getResult($dbdM,0);
                                  $ordine = $recordset["maxordine"]+1;
                                  $SQL = "INSERT INTO slider_elementi(id_slider,id_news,ordine) VALUES ($id_slider,".$news->id.",$ordine)";
                                  $DBConnection->exeSQL($SQL);
                              }
                          }   
                      }  
              }
              return $news;
          }catch(Exception $e){
              
                $DBConnection->LogError("DBUtility.php->updateNews($news->id)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
                return null;
          }  
      }
      /**
       * Cancella la news con id=$ID
       * @param MySqlConnection $DBConnection
       * @param int $ID 
       */
      public static function deleteNews(MySqlConnection $DBConnection,$ID)    {          
          try{
              $DBConnection->beginTransaction();
              $SQL = "DELETE FROM newsparagrafi WHERE id_news=$ID";
              $DBConnection->exeSQL($SQL);
              $SQL = "DELETE FROM newscategoryrelation WHERE id_news=$ID";
              $DBConnection->exeSQL($SQL);
              $SQL = "DELETE FROM news WHERE id=$ID";
              $DBConnection->exeSQL($SQL);
              
              $DBConnection->CommitTransaction();
          }catch(Exception $e)  {
              $DBConnection->RollBackTransaction();
              $DBConnection->LogError("DBUtility.php->deleteNews($ID)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");

          }  
      }
      
      /**
       * Inserisce un nuovo paragrafo delle news
       * @param MySqlConnection $DBConnection
       * @param Paragrafo $paragrafo 
       */
      public static function insertNewParagrafoNews(MySqlConnection $DBConnection, Paragrafo $paragrafo)    {
          try   {
              $SQL = "SELECT max(ordine) as ordine FROM newsparagrafi WHERE id_news=".$paragrafo->id_news;
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0);
              $ordine = 1;
              if(strlen($recordset["ordine"])>0 && $i>0)  {               
                    $ordine = $recordset["ordine"] + 1;
              }      
              
              $SQL = "INSERT INTO newsparagrafi(";
              foreach(array_keys($paragrafo->getAttributi()) as $key){
                    $SQL .= $key.",";
              }
              $SQL .= "ordine,data_creazione,data_modifica) VALUES (";
              foreach(array_keys($paragrafo->getAttributi()) as $key){
                    $SQL .= "'".  $paragrafo->$key."',";
              }
              $SQL .= "$ordine,NOW(),NOW());";
              $DBConnection->exeSQL($SQL);
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->insertNewParagrafo($paragrafo->titolo)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               
          }  
      }
      /**
       * Modifica i dati a db di un paragrafo delle news
       * @param MySqlConnection $DBConnection
       * @param Paragrafo $paragrafo 
       */
      public static function updateParagrafoNews(MySqlConnection $DBConnection, Paragrafo $paragrafo)    {
          try   {
              $SQL = "UPDATE newsparagrafi SET ";
              foreach(array_keys($paragrafo->getAttributi()) as $key){
                    $SQL .= $key."='".$paragrafo->$key."',";
              }
              
              $SQL .= "data_modifica=NOW()";
              $SQL .= " WHERE id=".$paragrafo->id;
              $DBConnection->exeSQL($SQL);
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->updateParagrafoNews($paragrafo->titolo)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               
          }  
      }
      /**
       * cancella un paragrafo secondo la condizione $where
       * ATTENZIONE CHE SE NON SI METTE LA WHERE CANCELLA TUTTI I PARAGRAFI DELLE NEWS
       * @param MySqlConnection $DBConnection
       * @param string $where 
       */
      public static function deleteParagrafoNews(MySqlConnection $DBConnection,$where=null) {
          try   {
              $SQL = "DELETE FROM newsparagrafi";
              if($where!=null)  $SQL .= " ".$where;
              $DBConnection->exeSQL($SQL);
              
            }catch(Exception $e){            
                $DBConnection->LogError("DBUtility.php->deleteParagrafoNews($where)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
               return null;
          }  
      }
      
      public static function spostaNews(MySqlConnection $DBConnection,$id,$incremento)    {
          try{
              $SQL = "SELECT * FROM news WHERE id=$id";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0);
              if($incremento>0) {
                $SQL = "UPDATE news SET ordine=ordine+$incremento WHERE id=$id";
                $DBConnection->exeSQL($SQL);
                $SQL = "UPDATE news SET ordine=ordine-$incremento WHERE id<>$id AND ordine=".($recordset["ordine"]+$incremento).";";
                $DBConnection->exeSQL($SQL);
             }else{
                $incremento = $incremento * (-1);
                $SQL = "UPDATE news SET ordine=ordine-$incremento WHERE id=$id";
                $DBConnection->exeSQL($SQL);               
                $SQL = "UPDATE news SET ordine=ordine+$incremento WHERE id<>$id AND ordine=".($recordset["ordine"]-$incremento).";";
                $DBConnection->exeSQL($SQL);
             }
              
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->spostaNews($id,$incremento)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Ottiene le categorie delle news
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return type 
       */
      public static function getNewsCategory(MySqlConnection $DBConnection,$where=null, $order=null)    {
          try{
              $SQL = "SELECT * FROM newscategory";
              if($where!=null)  $SQL .= " ".$where;
              if($order!=null)  $SQL .= " ".$order;
              
              return $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->getNewsCategory($where,$ordine)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Ottiene i gruppo degli utenti ammonistratori di MOOL 2
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return mixed 
       */
      public static function getGruppiUtentiAmministratori(MySqlConnection $DBConnection,$where=null, $order=null)    {
          try{
              $SQL = "SELECT * FROM tblmanagerusertype";
              if($where!=null)  $SQL .= " ".$where;
              if($order!=null)  $SQL .= " ".$order;
              
              return $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->getGruppiUtentiAmministratori($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Ottiene i gli utenti ammonistratori di MOOL 2
       * @param MySqlConnection $DBConnection
       * @param string $where
       * @param string $order
       * @return mixed 
       */
      public static function getUtentiAmministratori(MySqlConnection $DBConnection,$where=null, $order=null)    {
          try{
              $SQL = "SELECT * FROM tblmanageruser u JOIN tblmanagerusertyperel t ON u.IDUser=t.IDUser";
              if($where!=null)  $SQL .= " ".$where;
              if($order!=null)  $SQL .= " ".$order;
              
              return $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->getUtentiAmministratori($where,$order)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      
      /**
       * Aggiorna un utente amministratore
       * @param MySqlConnection $DBConnection
       * @param string $nome
       * @param string $cognome
       * @param string $email
       * @param string $password
       * @param int $State, 1=attivo
       * @param int $IDType
       * @param int $IDUser
       * @return int, IDUser o -1 se si inserisce un email già presente
       */
      public static function updateUtenteAmministratore(MySqlConnection $DBConnection,$nome,$cognome,$email,$password,$State,$IDType,$IDUser)    {
          try{
              
              $SQL = "SELECT * FROM tblmanageruser u JOIN tblmanagerusertyperel t ON u.IDUser=t.IDUser WHERE u.Email='$email'";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              $recordset = $DBConnection->getResult($dbd,0);
              if($i>0 && $recordset["IDUser"]!=$IDUser)  {
                  return -1; //email già presente
              }else{
                  $DBConnection->beginTransaction();
                  if($password!=$recordset["Password"])
                     $SQL = "UPDATE tblmanageruser SET Name='".Utility::cleanField($nome)."',Surname='".Utility::cleanField($cognome)."',Email='".Utility::cleanField($email)."',Password='".md5($password)."',State=$State WHERE IDUser=$IDUser;";
                  else
                     $SQL = "UPDATE tblmanageruser SET Name='".Utility::cleanField($nome)."',Surname='".Utility::cleanField($cognome)."',Email='".Utility::cleanField($email)."',State=$State WHERE IDUser=$IDUser;"; 
                  $DBConnection->exeSQL($SQL);
                  
                  $SQL= "UPDATE tblmanagerusertyperel SET IDType=$IDType WHERE IDUser=$IDUser;";  
                  $DBConnection->exeSQL($SQL);
                  $DBConnection->CommitTransaction();
                  return $IDUser;
              }
              
              
          }catch(Exception $e)  {
              $DBConnection->RollBackTransaction();
              
              $DBConnection->LogError("DBUtility.php->insertUtenteAmministratore($nome,$cognome,$email,$password,$IDType)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Inserisce un nuovo utente amministratore
       * @param MySqlConnection $DBConnection
       * @param string $nome
       * @param string $cognome
       * @param string $email
       * @param string $password
       * @param int $State, 1= attivo
       * @param int $IDType
       * @return int, IDUser o -1 per email già presente o -2  se IDType non maggiore di 0
       */
      public static function insertUtenteAmministratore(MySqlConnection $DBConnection,$nome,$cognome,$email,$password,$State,$IDType)    {
          try{
              
              $SQL = "SELECT * FROM tblmanageruser u JOIN tblmanagerusertyperel t ON u.IDUser=t.IDUser WHERE u.Email='$email';";
              list($dbd,$i) = $DBConnection->exeSQL($SQL);
              if($i>0)  {
                  return -1; //email già presente
              }else if($IDType<1){
                  return -2; //necessario selezionare un tipo
              }else{
                  $DBConnection->beginTransaction();
                  $SQL = "INSERT INTO tblmanageruser(Name,Surname,Email,Password,State,LastLogin) VALUES ('".Utility::cleanField($nome)."','".Utility::cleanField($cognome)."','".Utility::cleanField($email)."','".md5($password)."',$State,NOW());";
                  $DBConnection->exeSQL($SQL);
                  $SQL = "SELECT u.IDUser FROM tblmanageruser u WHERE u.Email='$email';";
                  list($dbd,$i) = $DBConnection->exeSQL($SQL);
                  $recordset = $DBConnection->getResult($dbd,0);
                  $SQL= "INSERT INTO tblmanagerusertyperel(IDUser,IDType) VALUES(".$recordset["IDUser"].",$IDType);";  
                  $DBConnection->exeSQL($SQL);
                  $DBConnection->CommitTransaction();
                  return $recordset["IDUser"];
              }
              
              
          }catch(Exception $e)  {
              $DBConnection->RollBackTransaction();
              
              $DBConnection->LogError("DBUtility.php->insertUtenteAmministratore($nome,$cognome,$email,$password,$IDType)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Aggiorna lo stato dell'utente amministatore con $IDUser
       * @param MySqlConnection $DBConnection
       * @param int $IDUser
       * @param int $Stato
       * 
       */
      public static function updateStatoUtenteAmministratore(MySqlConnection $DBConnection,$IDUser, $Stato)    {
          try{
             
                  
              $SQL = "UPDATE tblmanageruser SET State=$Stato WHERE IDUser=$IDUser";
              $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->updateStatoUtenteAmministratore($IDUser, $Stato)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      /**
       * Cancella l'utente amministratore con $IDUser
       * @param MySqlConnection $DBConnection
       * @param int $IDUser 
       */
      public static function deleteUtenteAmministratore(MySqlConnection $DBConnection,$IDUser)    {
          try{
              $SQL = "DELETE FROM tblmanagerusertyperel WHERE IDUser=$IDUser;"; 
              $DBConnection->exeSQL($SQL);
              
              $SQL = "DELETE FROM tblmanageruser WHERE IDUser=$IDUser";
              $DBConnection->exeSQL($SQL);
          }catch(Exception $e)  {
              $DBConnection->LogError("DBUtility.php->deleteUtenteAmministratore($IDUser)",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
          }
      }
      
      
      
      
  }

?>
