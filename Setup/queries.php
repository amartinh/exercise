<?php
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