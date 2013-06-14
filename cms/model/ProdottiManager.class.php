<?php
/**
 * Intefaccia di controllo per il creator ProdottiManager di ProdottoAbstract per  del factory pattern Prodo
 * @version 1.0
 */
interface IProdottiManager
{
   /**
    * crea il prodotto e a seconda della action fa delle operazioni a db
    * @param array $recordset
    * @param string $action:se action è null recupero i dati di base del prodotto(serve per il catalogo del front online), "get" per recuperare a db l'intero prodotto,  per  "insert" per inserire il prodotto, "update" per aggiornare il prodotto
    */
   //public function creaProdotto($recordset,$action);
   
}
/**
 * Creator di ProdottoAbstract
 */
class ProdottiManager implements IProdottiManager
{
   private $DBConnection;
   public function __construct(MySqlConnection $oDBConnection=null){
       $this->DBConnection=$oDBConnection;
   }
   
   public function creaProdotto($recordset,$action=null)
   {
     $prodotto = null;
     if(array_key_exists("id_categoria",$recordset)){
         if($recordset["id_categoria"]==2)  {
             $prodotto = new Composizione($recordset); 
         }else{
             $prodotto = new ProdottoMobilitare($recordset);
         }
     }  else {
         $prodotto= new Prodotto($recordset);
     }
     /**
      * 
      */
     if($action!=null)
        return $this->connectDB($prodotto, $action);
     else
         return $prodotto;
   }
   
   /**
    * si interfaccia con il database
    * @param ProdottoAbstract $prodotto
    * @param string $action
    * @return ProdottoAbstract
    */
   private function connectDB(ProdottoAbstract $prodotto,$action)   {
     $class=  get_class($prodotto);
     if($action=="get" && $this->DBConnection!=null){
         if($class=="Composizione") {
             $prodotto = $this->setInfoComposizione($prodotto);
             return $prodotto;
         }else if($class=="ProdottoMobilitare") {
             $prodotto = $this->setInfoProdottoMobilitare($prodotto);
             return $prodotto;
         }else {
                list($dbd,$i)=ecommerce::getSliderElementi($this->DBConnection, "WHERE se.id_prodotto=".$prodotto->id);
                for($j=0;$j<$i;$j++)   {
                    $rec = $this->DBConnection->getResult($dbd,$j);
                    $prodotto->__set("id_slider_".$rec["id_slider"],"1");
                }
                return $prodotto;
         }
     }else if($action=="insert" && $this->DBConnection!=null){
         if($class=="Composizione") {
             try    {
                 $this->DBConnection->beginTransaction();
                 $prodotto->id = ecommerce::insertProdotto($this->DBConnection, $prodotto);
                 $this->DBConnection->CommitTransaction();
             }  catch (Exception $e)    {
                  $this->DBConnection->RollBackTransaction();
                  $this->DBConnection->LogError("ProdottiManager.class.php->creaProdotto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
             }
         }else if($class=="ProdottoMobilitare") {
             try    {
                 $this->DBConnection->beginTransaction();
                 $prodotto->id = ecommerce::insertProdotto($this->DBConnection, $prodotto);
                 $this->DBConnection->CommitTransaction();
             }  catch (Exception $e)    {
                  $this->DBConnection->RollBackTransaction();
                  $this->DBConnection->LogError("ProdottiManager.class.php->creaProdotto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
             }
         }  else {
             try    {
                 $this->DBConnection->beginTransaction();
                 $prodotto->id = ecommerce::insertProdotto($this->DBConnection, $prodotto);
                 $this->DBConnection->CommitTransaction();
             }  catch (Exception $e)    {
                  $this->DBConnection->RollBackTransaction();
                  $this->DBConnection->LogError("ProdottiManager.class.php->creaProdotto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
             }
         }
     }else if($action=="update" && $this->DBConnection!=null)  {
         if($class=="Composizione") {
             try    {
                 $this->DBConnection->beginTransaction();
                 ecommerce::updateProdotto($this->DBConnection, $prodotto);
                 $this->DBConnection->CommitTransaction();
             }  catch (Exception $e)    {
                  $this->DBConnection->RollBackTransaction();
                  $this->DBConnection->LogError("ProdottiManager.class.php->creaProdotto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
             }
         }else if($class=="ProdottoMobilitare") {
             try    {
                 $this->DBConnection->beginTransaction();
                 ecommerce::updateProdotto($this->DBConnection, $prodotto);
                 $this->DBConnection->CommitTransaction();
             }  catch (Exception $e)    {
                  $this->DBConnection->RollBackTransaction();
                  $this->DBConnection->LogError("ProdottiManager.class.php->creaProdotto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
             }
         }else{
             try    {
                 $this->DBConnection->beginTransaction();
                 ecommerce::updateProdotto($this->DBConnection, $prodotto);
                 $this->DBConnection->CommitTransaction();
             }  catch (Exception $e)    {
                  $this->DBConnection->RollBackTransaction();
                  $this->DBConnection->LogError("ProdottiManager.class.php->creaProdotto()",$e->getMessage(),$e->getTraceAsString(),$SQL,$e->getLine(),1,"CMS.Mool2");
             }
         }
     }
     return $prodotto;
   }
   
   /**
    * Imposta tutte le caratteristiche del prodotto per la tipologia di oggetto ProdottoMobilitare
    * @param ProdottoMobilitare $prodotto
    * @return \ProdottoMobilitare
    */
   private function setInfoProdottoMobilitare(ProdottoMobilitare $prodotto){
       //recupero le immagini 
             list($dbd,$i)=ecommerce::getProdottiImmagini($this->DBConnection, "WHERE id_prodotto=".$prodotto->id." AND main<1","ORDER BY ordine ASC");
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addImmagine($rec);
             }
             //recupero i luoghi 
             list($dbd,$i)=ecommerce::getProdottiLuoghi($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addLuogo($rec);
             }
             //recupero le correlazioni
             list($dbd,$i)=ecommerce::getProdottiCorrelati($this->DBConnection, $prodotto->id);
              for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 
                    if($rec["id_prodotto"]!=$prodotto->id){ 
                       $prodotto->addProdottoCorrelato(new ProdottoMobilitare(ecommerce::getProdotto($this->DBConnection, "WHERE p.id=".$rec["id_prodotto"])));
                    }else {

                       $prodotto->addProdottoCorrelato(new ProdottoMobilitare(ecommerce::getProdotto($this->DBConnection, "WHERE p.id=".$rec["id_prodotto2"])));
                    }
                 
             }
             //recupero gli stili
             list($dbd,$i)=ecommerce::getProdottiStili($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addStile($rec);
             }
             //recupero i target
             list($dbd,$i)=ecommerce::getProdottiTarget($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addTarget($rec);
             }
             //recupero i target
             list($dbd,$i)=ecommerce::getProdottiTipologie($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addTipologia($rec);
             }
             
             list($dbd,$i)=ecommerce::getSliderElementi($this->DBConnection, "WHERE se.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->__set("id_slider_".$rec["id_slider"],"1");
             }
             
             return $prodotto;
   }
   
   private function setInfoComposizione(Composizione $prodotto){
       //recupero le immagini 
             list($dbd,$i)=ecommerce::getProdottiImmagini($this->DBConnection, "WHERE id_prodotto=".$prodotto->id." AND main<1","ORDER BY ordine ASC");
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addImmagine($rec);
             }
             //recupero i luoghi 
             list($dbd,$i)=ecommerce::getProdottiLuoghi($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addLuogo($rec);
             }
             //recupero le correlazioni
             list($dbd,$i)=ecommerce::getProdottiCorrelati($this->DBConnection, $prodotto->id);
              for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 if($rec["id_prodotto"]!=$prodotto->id){ 
                    $prodotto->addProdottoCorrelato(new ProdottoMobilitare(ecommerce::getProdotto($this->DBConnection, "WHERE p.id=".$rec["id_prodotto"])));
                 }else {
                    
                    $prodotto->addProdottoCorrelato(new ProdottoMobilitare(ecommerce::getProdotto($this->DBConnection, "WHERE p.id=".$rec["id_prodotto2"])));
                 }
             }
             //recupero gli stili
             list($dbd,$i)=ecommerce::getProdottiStili($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addStile($rec);
             }
             //recupero i target
             list($dbd,$i)=ecommerce::getProdottiTarget($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addTarget($rec);
             }
             //recupero i target
             list($dbd,$i)=ecommerce::getProdottiTipologie($this->DBConnection, "WHERE p.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addTipologia($rec);
             }
             
             list($dbd,$i)=ecommerce::getProdottiComposizione($this->DBConnection, $prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->addProdottoComposizione(new ProdottoMobilitare(ecommerce::getProdotto($this->DBConnection, "WHERE p.id=".$rec["id_prodotto"])));
             }
             list($dbd,$i)=ecommerce::getSliderElementi($this->DBConnection, "WHERE se.id_prodotto=".$prodotto->id);
             for($j=0;$j<$i;$j++)   {
                 $rec = $this->DBConnection->getResult($dbd,$j);
                 $prodotto->__set("id_slider_".$rec["id_slider"],"1");
             }
             
             return $prodotto;
   }
   
}

?>