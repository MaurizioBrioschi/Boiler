<?php
/**
 * Concrete class per prodotti
 */
class Prodotto extends ProdottoAbstract
{
    protected $prodotti_correlati = array();
    protected $categorie = array();
    protected $immagini = array();
    
    /**
     * Imposta i prodotti correlati
     * @param ProdottoAbstract array $prodotti
     */
    public  function addProdottiCorrelati($prodotti=array()) {
        $this->prodotti_correlati = $prodotti;
    }
    /**
     * Aggiunge un prodotto correlato
     * @param ProdottoAbstract $prodotto
     */
    public function addProdottoCorrelato(ProdottoAbstract $prodotto){
        $this->prodotti_correlati[] = $prodotto;
    }
    /**
     * Imposta le categorie
     */
    public function addCategorie($categorie=array()){
        foreach ($categorie as $cat){
            $this->categorie[] = $categorie;
        }
    }
    /**
     * Aggiunge una categoria
     * @param string $categoria
     */
    public function addCategoria($categoria){
        $this->categorie[] = $categoria;
    }
    /**
     * imposta le immagini del prodotto
     * @param  string $immagini
     */
    public function addImmmagini($immagini=  array()){
        $this->immagini = $immagini;
    }
    /**
     * Imposta un immagine
     * @param string $immagine
     */
    public function addImmagine($immagine){
        $this->immagini[] = $immagine;
    }
    
}
?>