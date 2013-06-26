<?php
/*
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
 * 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace ridesoft\Boiler\model;

class User extends UserAbstract{
    protected $whishlist;
    protected $isLogged = false;
    
    function __construct($id_session) {  
        $this->id_session = $id_session;
        $this->whishlist = new Whishlist();
        
    }
    
   
    public function LogIn($recordset=array()) {   
        if(count($recordset)>0) $this->isLogged = true;
        foreach(array_keys($recordset) as $key){
                if($key=='id_whishlist')    {
                    $this->whishlist->__set($key,$recordset[$key]);
                }else
                    $this->__set($key, $recordset[$key]);
        }
    }
    public function isLogged()  {
        return $this->isLogged;
    }
    public function setWhishlist(Whishlist $whishlist) {
        $this->whishlist = $whishlist;
    }
    public function getWhishlist() {
        return $this->whishlist;
    }
    /**
     *  Aggiunge un prodotto alla whishlist dell'utente e se questo è loggato e $inDB è true lo inserisce a DB
     * @global type $conn
     * @param ProdottoAbstract $p
     * @param type $inDB
     * @param MySqlConnection $DBConnection
     */
    public function addProdottoToWhishlist(ProdottoAbstract $p, $inDB=true,MySqlConnection $DBConnection=null)    {
        $esito = true;
//        if($this->isLogged() && $inDB) {
//            if($DBConnection==null) {
//                global $conn;
//                $DBConnection = $conn;
//            }
//            $presente = false;
//            
//            for($j=0;$j<count($this->whishlist->getProdotti());$j++)    {
//                if($this->whishlist->getProdotto($j)->id==$p->id)   {
//                     
//                    $presente=true;
//                    break;
//                    
//                }
//            }
//            if(!$presente) {  
//
//                $esito = ecommerce::insertProdottoToWhishlist($DBConnection, $this->whishlist->id_whishlist, $p->id);
//                
//                if($esito) {$this->whishlist->addProdotto($p);}
//            }           
//        }else if($esito) $this->whishlist->addProdotto($p);
        
        $this->whishlist->addProdotto($p);
    }
    
    public function removeProdottoToWhishlist($id,$inDB=true,MySqlConnection $DBConnection=null)  {
         $esito = true;
         $this->whishlist->removeProdotto($id);
    }
   
    
}
?>