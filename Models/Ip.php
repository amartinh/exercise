<?php
include 'Adapter.php';

/**
 * Class Ip. Model to deal with IP registers in Redis
 */
class Ip extends Adapter {
    /**
     *
     *
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * It returns a country for a certain IP
     * @param $ip
     * @return string
     * @throws Exception
     */
    public function getCountry ($ip) {
        $result = $this->redisCLI->get($ip);
        if(!$result){
            throw new Exception("No data available");
        } else {
            return ucfirst(strtolower($result));
        }
    }

    /**
     * It creates a register based on ip and country
     * @param $ip
     * @param $country
     * @throws Exception
     */
    public function setIpCountry ($ip, $country) {
        if(!$this->redisCLI->set($ip, $country)){
            throw new Exception("Cannot insert register");
        }
    }

}