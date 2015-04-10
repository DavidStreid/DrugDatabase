
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
            rect {
                -moz-transition: all 0.3s;
                -o-transition: all 0.3s;
                -webkit-transition: all 0.3s;
                transition: all 0.3s;
            }
            /*hover function*/
            rect:hover {
                fill: orange;
            }
            
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
        
        <body>
            <table id="myTable" border="1">
                <tr>
                    <td>DRUG</td>
                    <td>Adverse Risk</td>
                    <td>No Adverse Risk</td>
                </tr>
                <tr>
                    <td>Exposed to Drug</td>
                    <td>    </td>
                    <td>    </td>
                </tr>
                <tr>
                    <td>Not Exposed to Drug</td>
                    <td>    </td>
                    <td>    </td>
                </tr>
            </table>
            <form>
                <input type="button" onclick="changeContent()" value="Next Drug">
            </form>
        </body>
        
        <script type="text/javascript">             
                var dL = [
                    <?php
                        $drugList = array();
                        $myfile = fopen("mwasData.txt", "r") or die("Unable to open file!"); // pre-processed (first 9 columns)
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
            
                //console.log(typeof(parseInt(dL[0][2][2])));
            
            //Define Variables
            barHeight = 20;
            var w = 5000;
            var h = 10; // Height to include every entry
            var buffer = 15;
            var textbuffer = 40
            var svg = d3.select("body")
            .append("svg")
            .attr({
                width: w,
                height: h,
            });
            
            //console.log(dL[0]);
            //Function to make Bars
            var makeBars = function() {     
                svg.selectAll("rect")
                .data(dL[0])
                .enter()
                .append("rect")
                .attr({
                    //width: 100,
                    width: function(d,i) {
                        if (!isNaN(parseInt(d[8]))){
                            return parseInt(d[8])*100
                        }
                    }, //GROUND_TRUTH = d[5], LOGRR = d[6] 
                    height: 100000,
                    fill: function(d){
                        return "rgb(100, 150, 100)";
                    },
                    y: function(d, i) {return i*1000}, // Adjust input for proper spacing
                    x: 100
                })
                //Adding the mouseOver function - Hover to highlight
                .on("mouseover", function(d) {
                    var xPosition = (xScale(d3.select(this).attr("width")));
                    var yPosition = 300 + parseFloat(d3.select(this).attr("y"));

                    d3.select("#tooltip")
                    .style("right", xPosition + "px")
                    .style("top", yPosition + "px")
                    .select("#value")
                    .text(getRelativeEnrichments(d, queryList));
                    //.text(d)

                    d3.select("#tooltip").classed("hidden", false);
                })

                .on("mouseout", function() {
                    d3.select("#tooltip").classed("hidden", true);
                })
            }

            makeBars();
            
            var i = 0
            function changeContent(){

                var x=document.getElementById('myTable').rows
                var header =x[0].cells   // 
                header[0].innerHTML=dL[0][i][0]
                var drug = x[1].cells
                drug[0].innerHTML = "drug"
                drug[1].innerHTML = "drug2"
            }
        </script>
    </body>
