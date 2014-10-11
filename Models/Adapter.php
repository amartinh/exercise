<?php


/**
 * Class Adapter
 * It setups Redis connection. All models that need to use it will extend it.
 */
class Adapter {
    private $host;
    private $port;
    protected $redisCLI;

    /**
     *
     */
    public function __construct (){
        global $configs;
        $this -> host = $configs['database']['server'];
        $this -> port = $configs['database']['port'];
        $this -> connectDb();
    }

    /**
     * @throws Exception
     */
    private function connectDb () {
        $this -> redisCLI = new Redis();
        if (!$this -> redisCLI -> connect($this -> host, $this -> port )){
            throw new Exception("No se puede conectar a Redis");
        }
    }

}