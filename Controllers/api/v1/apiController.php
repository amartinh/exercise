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
     * Standalone script to create SET commands to be used in Redis-CLI is present in Setup/queries.php
     * since phpredis takes too long to do such bulk insert.
     *
     * For every range, a IP, Country set is being generated
     * FI. Register 1959248384	1959249919	INDONESIA from csv file will create..
     * SET 1959248384 'INDONESIA'
     * SET 1959248385 'INDONESIA'
     * SET 1959248386 'INDONESIA'
     * ...
     * SET 1959249919 'INDONESIA'
     *
     * @param $file
     */
    private function loadContent ($file) {
        $Db = new Ip();
        $file = fopen(SETUPPATH . $file,"r");

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            $keys = 0;
            for ($i = $data[0]; $i <= $data[1]; $i++ ) {
                try {
                    $Db->setIpCountry($i, $data[3]);
                    $keys++;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

            }
        }
        fclose($file);
        echo $keys . " registers have been generated";
    }
}