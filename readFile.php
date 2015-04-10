
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Search Engine</title>

<!--Adding the d3 javaascript-->
<script type="text/javascript" src="d3.min.js"></script>
<!--Adding the stylesheet for jquery-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script type='text/javascript' src='DAT.GUI.min.js'></script>

<style type="text/css">

</style>
</head>
<body>
    <script type="text/javascript"> 
        var dL = [
            <?php
                $drugList = array();
                ini_set('auto_detect_line_endings',TRUE);
                $handle = fopen('mwas_full.txt','r') or die("Unable to open file!"); 
                if($handle){
                    while ( ($data = fgetcsv($handle, 1000) ) !== FALSE ) {
                        $RANK = $x[0];
                        $MIN_DETECTABLE_RR = $x[1];
                        $SOURCE_ID = $x[2];
                        $DRUG_CONCEPT_ID = $x[3];
                        $CONDITION_CONCEPT_ID = $x[4];
                        $GROUND_TRUTH = $x[5];
                        $LOGRR = $x[6];
                        $LOGLB95RR = $x[7];
                        $P = $x[8];
                        $ANALYSIS_ID = $x[9];
                        $p_full = $x[10];
                        $source_name = $x[11];
                        $condition_name = $x[12];
                        $CONCEPT_ID = $x[13];
                        $rxnorm_concept_name = $x[14];
                        $atc5_concept_id = $x[15];
                        $atc5_concept_name = $x[16];
                        $atc3_concept_id = $x[17];
                        $atc3_concept_name = $x[18];
                        $atc1_concept_id = $x[19];
                        $atc1_concept_name = $x[20];
                        
                        array_push($drugList, $RANK);
                    }
                } else {
                    echo "Error reading File";
                }
                fclose($handle);     
                echo json_encode($drugList);
            ?>
        ];
        console.log(dL);
    </script>
</body>