<?php
include MODELSPATH . 'Ip.php' ;


/**
 * Class apiController
 */
class apiController {

    /**
     * @param null $ip
     */
    public function ip ($ip = null){
        if (isset($ip)) {
            $method = 'get';
        } else {
            $method = 'post';
        }
        switch ($method){
            case isset($ip):
                $Db = new Ip();
                try {
                    $country = $Db -> getCountry($ip);
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        'country' => $country
                    ));
                } catch (Exception $e) {
                    echo "<h1>". $e->getMessage(). "</h1>";                }
                break;
            case !isset($ip):
                echo "Let's insert/create data to DB" . "<br/>";
                $this -> loadContent('partial.csv');
                break;
            default:
                echo "<h1>Bad request</h1>";
        }
    }


    /**
     * Function to handle data insert into Redis creating ip country pairs parsing csv file ranges.
     * Set could be done using phpredis but it takes too long and dev env seems not able to handle it
     * Instead, procedure creating file and importing it using redis-cli will be used. That's why code to insert
     * is commented.
     *
     * @param $file
     */
    private function loadContent ($file) {
        //$Db = new Ip();
        $file = fopen(SETUPPATH . $file,"r");
        $fileToWrite = fopen(SETUPPATH . 'queries.txt',"w");

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            $keys = 0;
            for ($i = $data[0]; $i <= $data[1]; $i++ ) {
                /*try {
                    $Db->setIpCountry($i, $data[3]);
                    $keys++;
                    sleep(0.1);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }*/
                fwrite($fileToWrite, "SET $i '$data[3]'\n");
            }
        }
        fclose($file);
        echo $keys . " registers have been generated";
    }
}