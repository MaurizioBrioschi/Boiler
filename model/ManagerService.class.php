<?php
/**
 * class for manager service admin 
 * @todo serialize
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1 
  * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ManagerService{
    private $IDService,$IDSection;
    private $SectionName,$SectionDescription,$SectionOrder,$SectionLink,$ServiceLink,$ServiceName;
    
    /**
     * ottiene l'id del servizio
     * @return int
     */
    public function getIDService(){
          return $this->IDService;
    }
    /**
     * Ottiene l'id della sezione del servizio
     * @return int 
     */
    public function getIDSection(){
          return $this->IDSection;
    }
    /**
     * Ottiene il nome della sezione
     * @return string 
     */
    public function getSectionName(){
          return $this->SectionName;
    }
    /**
     * Ottiene la descrizione della sezione
     * @return string
     */
    public function getSectionDescription(){
          return $this->SectionDescription;
    }
    /**
     * Ottiene l'ordine del servizio
     * @return int 
     */
    public function getSectionOrder(){
          return $this->SectionOrder;
    }
    /**
     * Ottiene il link alla sezione del servizio
     * @return string 
     */
    public function getSectionLink(){
          return $this->SectionLink;
    }
    /**
     * Ottiene il nome del servizio
     * @return string 
     */
    public function getServiceName(){
          return $this->ServiceName;
    }
    /**
     * Ottiene il link al servizio
     * @return string
     */
    public function getServiceLink(){
          return $this->ServiceLink;
    }
    /**
     * Ottiene la descrizione del servizio
     * @return string
     */
    public function getServiceDescription(){
          return $this->ServiceDescription;
    }
    /**
     * Ottiene l'ordinde del servizio
     * @return int
     */
    public function getServiceOrder(){
          return $this->ServiceOrder;
    }
      /**
       * costruttori
       */
      function __construct(){
        $argv = func_get_args();
        switch(func_num_args())
        {
            default:
            case 1:
                self::__construct1($argv[0]);
                break;


        }
      }
      /**
       * Instanzia un servizio con i dati passati dal recordset del db
       * @param array $recordset 
       */
      function __construct1($recordset){
           $this->IDSection = $recordset["IDSection"];
           $this->IDService = $recordset["IDService"];
           $this->SectionDescription = $recordset["SectionDescription"];
           $this->SectionName = $recordset["SectionName"];
           $this->SectionOrder = $recordset["SectionOrder"];
           $this->ServiceDescription = $recordset["ServiceDescription"];
           $this->ServiceName = $recordset["ServiceName"];
           $this->ServiceOrder = $recordset["ServiceOrder"];
           $this->ServiceLink = $recordset["ServiceLink"];
           $this->SectionLink = $recordset["SectionLink"];
      }

      /**
     * sleep method for serialize object
     * @return serialize array
     */
      function __sleep()
        {
                return array('IDService','IDSection', 'SectionName','SectionDescription','SectionOrder','SectionLink','ServiceName','ServiceLink'); 

        }
     /**
      * wakeup method for serialize object
      * @return ManagerService
      */
     function __wakeup()
     {
            echo "";
     }
}//Service

?>