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
        
        
        <style type="text/css">
            /*Modify Rectangles for tooltip - small overlays over data*/
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
            #tooltip {
                position: absolute;
                width: auto;
                height: auto;
                padding: 10px;
                background-color: white;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                -webkit-box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
                -moz-box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
                box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
                pointer-events: none;
            }
            #tooltip.hidden {
                display: none;
            }
            #tooltip p {
                margin: 0;
                font-family: sans-serif;
                font-size: 16px;
                line-height: 20px;
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

            #queryBox {
                position: relative;
                top: 10%;
                left: 1%;
                width: 10%;
                background-color: #3c4543;
                border-top-left-radius: 10%;
                border-top-right-radius: 10%;
                border: 2px solid #000000;
                font-family: Futura; 
            }
            #sort{
                position: relative;
                top: 10%;
                left: 1%;
                width: 10%;
                background-color: #3c4543;
                font-family: Futura; 
                border-top-left-radius: 10%;
                border-top-right-radius: 10%;
                border: 2px solid #000000;
            }

            #enrichment{
                position: relative;
                top: 0%;
                left: 1%;
                width = 30%;
            }
            #glasses{
                position: initial;
            }
        </style>

	</head>
	<body>
        <h1>Drug Query Database</h1> 
        
        <div id="queryBox">
            <input type="text" id="txt_name">
        </div>
        <!--BUTTON - Sorts Data-->
        <div class="buttonex" id="sort">
            <button type="button">Sort By Order Count</button>
        </div>
        
        <div class="buttonex" id="sortPOE">
            <button type="button">Sort by Patient to Order Ratio</button>
        </div>
        <!--<div class="glasses"><img src="lab_beaker_full.png"></div>-->
            
        <div id="tooltip" class="hidden">
            <p><strong>Drug Data</strong></p>
            <p><span id="value">100</span></p>
        </div>
        
        <div id="enrichment">
            <input type="text" id="e1" style="width: 90%">
        </div>
        <div id="enrichment">
            <input type="text" id="e2" style="width: 90%">
        </div>
        <div id="enrichment">
            <input type="text" id="e3" style="width: 90%">
        </div>
        <div id="enrichment">
            <input type="text" id="e4" style="width: 90%">
        </div>
        <div id="enrichment">
            <input type="text" id="e5" style="width: 90%">
        </div>
        
		<script type="text/javascript"> 
            var MyPage = (function($) { 
                var dL = [
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
                                $OrderPatient_ENRICHMENT = $x[3]/$x[2];
                                $DRUG = array($CODE, $PRODUCT_NAME, $NUM_ORDERS, $NUM_PATIENTS, $OrderPatient_ENRICHMENT);
                                array_push($drugList, $DRUG);
                            }
                        } else {
                            echo "Error reading File";
                        }
                        fclose($myfile);
                        echo json_encode($drugList);
                    ?>
                ];
                
                var init = function(){}; /*{$( "#tags" ).autocomplete({source: dL, minLength: 4,disabled: false,})};*/
                
                //MyPage Module will return available tags & initialize those tags
                return {
                    init: init,
                    availableTags: dL
                }
            })(jQuery); // Puts correct version of jQuery into MyPage module through function($) 
            

            //Creating the Search Box
            var queryList = [];
            $(document).ready(function(){
                $('#txt_name').val('query');
                var query;
                var subStr;
                var match;
                var query;
                var queryLen;
                var currentLen;
                var repeat;

                $("#txt_name").change(function(){
                    $('svg').empty(); // Clears last query
                    queryList = [];
                    query = $('#txt_name').val();
                    queryLen = query.length;
                    /*  MyPage.availableTags - Array (MyPage.availableTags.length = 1)
                        MyPage.availableTags[0] - Array of size MyPage.availableTags[0].length
                        MyPage.availableTags[0][i] - individual JSON argument
                        MyPage.availableTags[0][i][1] - Product Name
                    */
                    //Search Logic - search for matching substrings
                    for (i = 0; i < MyPage.availableTags[0].length; i++){
                        for(j=0; j + queryLen <= MyPage.availableTags[0][i][1].length; j++){
                        subStr = MyPage.availableTags[0][i][1].substring(j, j+queryLen);
                            if (query == subStr){
                                match = MyPage.availableTags[0][i][1];
                                repeat = false;
                                for (k = 0; k<queryList.length; k++) {
                                    if (match == queryList[k][1]) {
                                        repeat = true;
                                    }
                                }
                                if (repeat == false) {
                                    queryList.push(MyPage.availableTags[0][i]); 
                                }
                            };
                        };
                    };   
                    //initBars();
                    makeBars("drugCount");
                    makeLabels("drugCount");
                });
            });
            
            //Define Variables
            var w = 5000;
            var h = 2000;
            var padding = 10;
            barHeight = 20;
            buffer = 10;

            //Defining maxWidth - the limit of the graph
            var maxWidth = d3.max(MyPage.availableTags[0], function(d, i) {
                return parseInt(d[3]); // Order Numbers - Need to parseInt otherwise will return the last value
            });
            
            var xScale = d3.scale.linear()
                .domain([0, maxWidth])
                .range([0, w]);
            
            var yScale = d3.scale.linear()
                .domain([0, MyPage.availableTags[0].length])
                .range([padding*4, h-(padding*4)]);
            
            var svg = d3.select("body")
                .append("svg")
                .attr({
                    width: w,
                    height: h,
                });

            var displayEnrichments = function(){
                $('#enrichment1').val(findEnrichment(2,5));
                $('#enrichment2').val(findEnrichment(2,5));
                $('#enrichment3').val(findEnrichment(2,5));
                $('#enrichment4').val(findEnrichment(2,5));
                $('#enrichment5').val(findEnrichment(2,5));
            }
            
            /*
            var initBars = function() {
                svg.selectAll("rect")
                .data(queryList)
                .enter()
                .append("rect")
            }
            */
            
            //Function to make Bars
            var makeBars = function(visual) {     
                if (visual == "drugCount"){i = 3}
                else if (visual == "patientOrder"){i = 4}

                svg.selectAll("rect")
                .data(queryList)
                .enter()
                .append("rect")
                .attr({
                    width: function(d) { return (xScale(parseInt(d[i])))},//# of Orders
                    height: barHeight,
                    fill: function(d){
                        return "rgb(10, 150, " + (Math.floor(d[i]/2)) + ")";
                    },
                    y: function(d, j) {return (yScale(j*40))}, // Adjust input for proper spacing
                    x: padding
                })

                //Adding the mouseOver function - Hover to highlight
                .on("mouseover", function(d) {
                    var xPosition = (xScale(d3.select(this).attr("width")));
                    var yPosition = 300+ parseFloat(d3.select(this).attr("y"));

                    d3.select("#tooltip")
                        .style("right", xPosition + "px")
                        .style("top", yPosition + "px")
                        .select("#value")
                        .text(d);

                    d3.select("#tooltip").classed("hidden", false);
                })

                .on("mouseout", function() {
                    d3.select("#tooltip").classed("hidden", true);
                })
            }

            //Function to makeLabels
            var makeLabels = function(visual) {
                if (visual == "drugCount"){i = 3}
                else if (visual == "patientOrder"){i = 4}
                
                svg.selectAll("text")
                .data(queryList)
                .enter()
                .append("text")
                .text(function(d) {return d[1]})//Product Name
                .attr({
                    x: padding,
                    y: function(d,j) {return yScale(j*40)},
                    "font-size": 14,
                    fill: "blue",
                    v: function(d) { return (xScale(parseInt(d[i])))}
                })
            }
            //Sorting Feature - By magnitude of associated constant with drug
            var sortOrder = false;
            
            var sortBars = function(sortCondition) {
                if (sortCondition == "drugCount"){i = 3}
                else if (sortCondition == "patientOrder"){i = 4}
                
                sortOrder = !sortOrder;
                //Sort BarGraphs by magnitude
                svg.selectAll("rect")
                .sort(function(a,b) {
                    if (sortOrder) {
                        return d3.ascending(b[i],a[i]); //Sorting BARS by #Orders
                    }
                    else {
                        return d3.ascending(a[i],b[i]);
                    }
                })
                //TRANSITIONS
                .transition()
                .duration(1000)
                .attr("y", function(d, j) {
                    return yScale(j*40);
                })

                //Sort Drug Labels
                svg.selectAll("text")
                .sort(function(a,b){
                    if (sortOrder) {
                        return d3.ascending(b[i],a[i]); // Sorting LABELS by #Patients
                    }
                    else {
                        return d3.ascending(a[i],b[i]);
                    }
                })
                .transition()
                .duration(1000)
                .attr("y", function(d, j) {
                    return (yScale(j*40));
                })
            };

            d3.select("#sort button")
                .on("click", function() {
                    sortBars("drugCount");
                })

            d3.select("#sortPOE button")
                .on("click", function() {
                    sortBars("patientOrder");
                    getEnrichments();
                })
            
            var getEnrichments = function() {
                var maxEnrichment = new Array(5);
                queryList.sort(function(a,b){
                    return d3.descending(a[4], b[4]);
                })
                for (i = 0; i< queryList.length; i++){
                    maxEnrichment[i] =  
                        "Enrichment Value: " + queryList[i][4] + "; " +
                        "Code: " + queryList[i][0] + "; " + 
                        "ProductName: " + queryList[i][1] + "; " + 
                        "Order: " + queryList[i][2] + "; " + 
                        "Patient: " + queryList[i][3] + "; "; 

                }
                $('#e1').val(maxEnrichment[0]);
                $('#e2').val(maxEnrichment[1]);
                $('#e3').val(maxEnrichment[2]);
                $('#e4').val(maxEnrichment[3]);
                $('#e5').val(maxEnrichment[4]);
            }
	    </script>
	</body>