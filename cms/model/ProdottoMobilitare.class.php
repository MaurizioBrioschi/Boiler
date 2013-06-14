<?php
/**
 * Concrete class per i prodotti di mobilitare
 */
class ProdottoMobilitare extends ProdottoAbstract
{
    protected $prodotti_correlati = array();
    protected $stili = array();
    protected $luoghi = array();
    protected $target = array();
    protected $categorie = array();
    protected $immagini = array();
    protected $tipologie = array();
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
        if($prodotto->id_categoria!=2)//non deve essere una correlazione
            $this->prodotti_correlati[] = $prodotto;
    }
    
    public function getProdottiCorrelati(){
        return $this->prodotti_correlati;
    }
    
    public function getProdottoCorrelato($index){
        return $this->prodotti_correlati[$index];;
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
    public function addImmmagini($immagini=array()){
        $this->immagini = $immagini;
    }
    /**
     * Imposta un immagine
     * @param string $immagine
     */
    public function addImmagine($immagine){
        $this->immagini[] = $immagine;
    }
    
    public function getImmagini(){
        return $this->immagini;
    }
    
    public function getImmagine($index) {
        return $this->immagini[$index];
    }
    
    public function addStili($stili=array()){
        $this->stili = $stili;
    }
    /**
     * Imposta un immagine
     * @param string $immagine
     */
    public function addStile($stile){
        $this->stili[] = $stile;
    }
    
    public function getStili(){
        return $this->stili;
    }
    
    public function getStile($index) {
        return $this->stili[$index];
    }
    
    public function addLuoghi($luoghi=array()){
        $this->luoghi = $luoghi;
    }
    /**
     * Imposta un immagine
     * @param string $immagine
     */
    public function addLuogo($luogo){
        $this->luoghi[] = $luogo;
    }
    
    public function getLuoghi(){
        return $this->luoghi;
    }
    
    public function getLuogo($index) {
        return $this->luoghi[$index];
    }
    
    public function addTargets($targets=array()){
        $this->target = $targets;
    }
    /**
     * Imposta un immagine
     * @param string $immagine
     */
    public function addTarget($target){
        $this->target[] = $target;
    }
    
    public function getTargets(){
        return $this->target;
    }
    
    public function getTarget($index) {
        return $this->target[$index];
    }
    
    public function addTipologie($tipologie=array()){
        $this->tipologie = $tipologie;
    }
    /**
     * Imposta un immagine
     * @param string $immagine
     */
    public function addTipologia($tipologia){
        $this->tipologie[] = $tipologia;
    }
    
    public function getTipologie(){
        return $this->tipologie;
    }
    
    public function getTipologia($index) {
        return $this->tipologie[$index];
    }
}
?>