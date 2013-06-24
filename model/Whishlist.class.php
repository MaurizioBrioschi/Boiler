<?php
class Whishlist
{
    protected $attr = array();
    protected $prodotti=array();
    
    
    function __construct($recordset=array()) {      
        foreach(array_keys($recordset) as $key){
                $this->__set($key, $recordset[$key]);
        }
       
    }
    
    public function __set($index, $value)
    {
	$this->attr[$index] = $value;
    }  

    public function __get($index)
    {
            return stripslashes($this->attr[$index]);
    }
    
    public function setProdotti($prodotti = array())    {
        $this->prodotti = $prodotti;
    }   
    
    public function getProdotti()   {
        return $this->prodotti;
    }
    
    public function getProdotto($index)   {
        return $this->prodotti[$index];
    }
    /**
     * Aggiunge un prodotto alla whishlist
     * @param ProdottoAbstract $p
     * @return boolean
     */
    public function addProdotto(ProdottoAbstract $p)    {
        $presente = false;
        foreach ($this->prodotti as $prodotto)  {
            if($prodotto->id==$p->id)   {
                $presente = true;
                break;
            }
        }
        if(!$presente)  {
            array_push($this->prodotti,$p);
            
        }else{
            
            
        }
    }
    
    public function removeProdotto($id) {
         for($i=0;$i<count($this->prodotti);$i++)   {
            if($this->prodotti[$i]->id==$id)   {
                unset($this->prodotti[$i]);
                $this->prodotti = array_values($this->prodotti);
            }
        }
    }
    
   
    
    
}
?>