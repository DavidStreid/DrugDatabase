<!--http://stackoverflow.com/questions/23740548/how-to-pass-variables-and-data-from-php-to-javascript-->
<?php
    $drugList = array();
    $myfile = fopen("cumc_product_num_orders_num_patients.txt", "r") or die("Unable to open file!");
    if ($myfile) {
        while (($line = fgets($myfile)) !== false) {
            $x = explode(chr(9), $line); // Tab = Ascii 9
            $CODE = $x[0];
            $PRODUCT_NAME = $x[1];
            $NUM_ORDERS = $x[2];
            $NUM_PATIENTS = $x[3];
            $DRUG = array($CODE, $PRODUCT_NAME, $NUM_ORDERS, $NUM_PATIENTS);
            array_push($drugList, $DRUG);
        }
    } else {
        echo "Error reading File";
    }
    fclose($myfile);
    echo json_encode($drugList);     
?>
