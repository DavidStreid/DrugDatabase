
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

            body {
                background-image:url(gray_jean/gray_jean.png)
            }

            h1 {
                position: relative;
                left: 1%;
                top: 2%;
                font-size: 50px;
            }
        </style>
    </head>
    <body>
        <script type="text/javascript">    
    
            var drugList = [
                
                <?php
                    $drugList = array();
                    $myfile = fopen("MWAS_data.txt", "r") or die("Unable to open file!");
                    if ($myfile) {

                        while (($line = fgets($myfile)) !== false) {
                            $x = explode(chr(9), $line); // Tab = Ascii 9  
                            $RANK = $x[0];
                            $MIN_DETECTABLE_RR = $x[1];
                            $SOURCE_ID = $x[2];
                            $DRUG_CONCEPT_ID = $x[3];
                            $CONDITION_CONCEPT_ID = $x[4];
                            $GROUND_TRUTH = $x[5];
                            $LOGRR = $x[6];
                            $LOGLB95RR = $x[7];
                            $P = $x[8];

                            $DRUG = array(
                                $RANK,
                                $MIN_DETECTABLE_RR,
                                $SOURCE_ID,
                                $DRUG_CONCEPT_ID,
                                $CONDITION_CONCEPT_ID,
                                $GROUND_TRUTH,
                                $LOGRR,
                                $LOGLB95RR,
                                $P
                            );

                            array_push($drugList, $DRUG);
                        }

                    } else {
                        echo "Error reading File";
                    }
                    fclose($myfile);
                    echo json_encode($drugList);
                ?>
                
                
            ];  
            
            
        </script>
    </body>
</html>