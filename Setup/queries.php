<?php
/*
 * Script to generate SET commands to be used in Redis from given csv
 * For every range, a IP, Country set is being generated
 * FI. Register 1959248384	1959249919	INDONESIA from csv file will create..
 * SET 1959248384 'INDONESIA'
 * SET 1959248385 'INDONESIA'
 * SET 1959248386 'INDONESIA'
 * ...
 * SET 1959249919 'INDONESIA'
 *
 * */
$file = fopen('partial.csv',"r");
$fileToWrite = fopen('queries.txt',"w");

while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
    $keys = 0;
    for ($i = $data[0]; $i <= $data[1]; $i++ ) {
        fwrite($fileToWrite, "SET $i '$data[3]'\n");
        echo "SET $i '$data[3]'\n";
    }
}
fclose($file);
echo $keys . " registers have been generated";
?>