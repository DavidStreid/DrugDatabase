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
                width: 200px;
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
                top: 10%;
                font-size: 40px;

            }

            #queryBox {
                position: relative;
                bottom: 10%;
                width: 10%;
                background-color: #3c4543;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                border: 2px solid #000000;
                font-family: Futura; 
            }
            #scramble {
                position: relative;
                left: 215px;
                top: 36px;
                width: 145px;
                background-color: #3c4543;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                border: 2px solid #000000;
                font-family: Futura; 
            }

            #sort{
                position: relative;
                top: 8px;
                left: 135px;
                width: 75px;
                background-color: #3c4543;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                border: 2px solid #000000;
                font-family: Futura; 
            }

            #glasses{
                position: initial;
            }
        </style>

	</head>
	<body>
        <!--BUTTON - Generates Random Data-->
        <div class="buttonex" id="scramble">
            <button type="button">Generate Random Data</button>
        </div>

        <!--BUTTON - Sorts Data-->
        <div class="buttonex" id="sort">
            <button type="button">Sort Data</button>
        </div>

        <h1>Drug Query Database</h1>  

        <div id="queryBox">
            <input type="text" id="txt_name">
        </div>

        <div class="glasses" style = "top: 100px;">
            <img src="lab_beaker_full.png" style="width:380px;height:150px">
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
                                $DRUG = array($CODE, $PRODUCT_NAME, $NUM_ORDERS, $NUM_PATIENTS);
                                array_push($drugList, $DRUG);
                            }
                        } else {
                            echo "Error reading File";
                        }
                        fclose($myfile);
                        echo json_encode($drugList);    
                    ?>
                ];


                var init = function()
                {
                    //Get .txt File
                    function reqListener() {
                        console.log(this.responseText);
                    }
                    var oReq = new XMLHttpRequest(); //New request object
                    oReq.onload = function(){
                        dL= JSON.parse(this.responseText); // Assign aT the JSON model - Can be accessed as array
                        oReq.open("get", "readFile.php", true);
                        oReq.send();
                        console.log(dL);
                    }
                    $( "#tags" )
                    //AUTOCOMPLETE FEATURE DISABLED
                    .autocomplete({
                        source: dL,
                        minLength: 4,
                        disabled: false,
                    })  
                };
                //MyPage Module will return available tags & initialize those tags
                return {
                    init: init,
                    availableTags: dL
                }
            
            })(jQuery); // Puts correct version of jQuery into MyPage module through function($) 
            

            //Creating the Search Box
            var queryList = []; // List of terms 
            $(document).ready(function(){
                //get input from search box - finds div id for queryBox and passes user
                //query into the input as a value
                $('#txt_name').val('query');
                var query;
                var subStr;
                var match;
                var query;
                var queryLen;
                var currentLen;
                var repeat;

                $("#txt_name").change(function(){
                    $('svg').empty(); // Emptying svg is necessary to clarify queries
                    queryList = [];
                    query = $('#txt_name').val();
                    queryLen = query.length;

                    //Search Logic - search for matching substrings
                    for (i = 0; i < MyPage.availableTags.length; i++){
                        for(j=0; j + queryLen <= MyPage.availableTags[i][1].length; j++){
                            subStr = MyPage.availableTags[i][1].substring(j, j+queryLen);
                            if (query == subStr){
                                match = MyPage.availableTags[i][1];
                                repeat = false;
                                for (k = 0; k<queryList.length; k++) {
                                    if (match == queryList[k][1]) {
                                        repeat = true;
                                        console.log("Match!");
                                    }
                                }
                                if (repeat == false) {
                                    queryList.push(MyPage.availableTags[i]); 
                                }
                            };
                        };
                    };
                    //Make Bars & Drug Labels
                    makeBars();
                    makeLabels();
                });
            });

            //Define Variables
            var w = 350;
            var h = 1000;
            var padding = 50;
            barHeight = 20;
            buffer = 10;

            //Defining maxWidth - the limit of the graph
            var maxWidth = d3.max(MyPage.availableTags, function(d) {
                return d[3]; //# Patients
            });

            var xScale = d3.scale.linear()
            .domain([0, maxWidth])
            .range([150, w]);

            var yScale = d3.scale.ordinal()
            .domain(d3.range(MyPage.availableTags.length))
            .rangeRoundBands([padding,h], 0.03);

            var svg = d3.select("body")
            .append("svg")
            .attr({
                width: w,
                height: h,
            });

            //Function to make Bars
            var makeBars = function() {
                svg.selectAll("rect")
                .data(queryList)
                .enter()
                .append("rect")
                .attr({
                    width: function(d) { return (xScale(d[3]))},//# Patients
                    height: barHeight,
                    fill: function(d){
                        return "rgb(10, 150, " + (Math.floor(d[3]/2)) + ")";
                    },
                    y: function(d, i) {return (yScale(i))},
                    x: padding
                })

                //Adding the mouseOver function - Hover to highlight
                .on("mouseover", function(d) {
                    var xPosition = w + (680 - xScale(d3.select(this).attr("width")));
                    var yPosition = parseFloat(d3.select(this).attr("y"));// - h/12;

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
            var makeLabels = function() {
                svg.selectAll("text")
                .data(queryList)
                .enter()
                .append("text")
                .text(function(d) {return d[1]})//Product Name
                .attr({
                    x: 0,
                    y: function(d,i) {return yScale(i)+buffer},
                    "font-size": 14,
                    fill: "blue",
                    v: function(d) { return (xScale(d[3]))}
                })
            }
            }
            //Sorting Feature - By magnitude of associated constant with drug
            var sortOrder = false;

            var sortBars = function() {

                sortOrder = !sortOrder;
                //Sort BarGraphs by magnitude
                svg.selectAll("rect")
                .sort(function(a,b) 
                      {
                    if (sortOrder) {
                        return d3.ascending(b[3],a[3]); //Sorting BARS by #Patients
                    }
                    else {
                        return d3.ascending(a[3],b[3]);
                    }
                })
                //TRANSITIONS
                .transition()
                .duration(1000)
                .attr("y", function(d, i) {
                    return yScale(i);
                })

                //Sort Drug Labels
                svg.selectAll("text")
                .sort(function(a,b)
                      {
                    if (sortOrder) {
                        return d3.ascending(b[3],a[3]); // Sorting LABELS by #Patients
                    }
                    else {
                        return d3.ascending(a[3],b[3]);
                    }
                })
                .transition()
                .duration(1000)
                .attr("y", function(d, i) {
                    return (yScale(i)+buffer);
                })
            };

            d3.select("#sort button")
            .on("click", function() {
                sortBars();
            })


            d3.select("#scramble button")
            .on("click", function() {
                dataset = [];
                //Create drugs
                for (var i=0; i<176826 ; i++){
                    constant = Math.floor(Math.random()*w);
                    rel = ["drug" + (i+1), constant];
                    dataset.push(rel);
                }

                svg.selectAll("rect")
                .data(dataset)
                .transition()
                .duration(150) // 250 ms is the default
                .delay(function(d, i){
                    return i/dataset.length*1000;
                })
                .ease("linear") 

                .attr({
                    width: function(d) { return (xScale(d[3]))},
                    height: barHeight,
                    fill: function(d){
                        return "rgb(10, 150, " + (Math.floor(d[3]/2)) + ")";
                    },
                    y: function(d, i) {return yScale(i)},
                    x: padding
                });

                svg.selectAll("text")
                .data(dataset)
                .attr({
                    v: function(d) { return (xScale(d[3]))}
                })
                .text(function(d){
                    return d[0];
                });
            });
            
	    </script>
	</body>