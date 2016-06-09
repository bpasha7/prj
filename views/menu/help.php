<div id="stat">
    <button id="stat-1">
        Лоты
    </button>
    <button id="stat-2">
        Аrтивность пользователей
    </button>
    <!--<button id="stat-3">Label Formatter</button>-->
</div>
<div id="stat-Container">

    <h3 id="title">
    </h3>
    <div class="demo-container">
        <div id="placeholder" class="demo-placeholder">
        </div>

    </div>
</div>
<script type="text/javascript" src="<?php echo URL; ?>public/scripts/jquery.flot.js">
</script>
<script type="text/javascript" src="<?php echo URL; ?>public/scripts/jquery.flot.pie.js">
</script>
<script type="text/javascript" src="<?php echo URL; ?>public/scripts/jquery.flot.categories.js">
</script>
<script type="text/javascript">

    $(function() {

            var data = {
            };

            var placeholder = $("#placeholder");

            $("#stat-2").click(function() {
                    data ={
                    };
                    $.ajax({
                            url: 'http://wts.dev/menu/bars',
                            success: function(stat){
                                data =  JSON.parse(stat);

                                placeholder.unbind();

                                $("#title").text("Активность пользователей");
                                $.plot("#placeholder", [ data ], {
                                        series: {
                                            bars: {
                                                show: true,
                                                barWidth: 0.6,
                                                align: "center"
                                            }
                                        },
                                        xaxis: {
                                            mode: "categories",
                                            tickLength: 0
                                        }
                                    });
                            }
                        });

                });


            $("#stat-1").click(function() {
                    //data ={};
                    $.ajax({
                            url: 'http://wts.dev/menu/pie',
                            success: function(stat){
                                data = JSON.parse(stat);
                                placeholder.unbind();
                                $("#title").text("Лоты по группам");
                                $.plot(placeholder, data, {
                                        series: {
                                            pie: {
                                                show: true,
                                                radius: 1,
                                                label: {
                                                    show: true,
                                                    radius: 2/3,
                                                    formatter: labelFormatter,
                                                    threshold: 0.1
                                                }
                                            }
                                        },
                                        legend: {
                                            show: false
                                        }
                                    });
                            }
                        });
                    /*setCode([
                    "$.plot('#placeholder', data, {",
                    "    series: {",
                    "        pie: {",
                    "            show: true,",
                    "            radius: 1,",
                    "            label: {",
                    "                show: true,",
                    "                radius: 2/3,",
                    "                formatter: labelFormatter,",
                    "                threshold: 0.1",
                    "            }",
                    "        }",
                    "    },",
                    "    legend: {",
                    "        show: false",
                    "    }",
                    "});"
                    ]);*/
                });

            placeholder.bind("plothover", function(event, pos, obj) {

                    if (!obj) {
                        return;
                    }

                    var percent = parseFloat(obj.series.percent).toFixed(2);
                    $("#hover").html("<span style='font-weight:bold; color:" + obj.series.color + "'>" + obj.series.label + " (" + percent + "%)</span>");
                });

            placeholder.bind("plotclick", function(event, pos, obj) {

                    if (!obj) {
                        return;
                    }

                    percent = parseFloat(obj.series.percent).toFixed(2);
                    alert(""  + obj.series.label + ": " + percent + "%");
                });
        });

    // Show the initial default chart

    // $("#stat-1").click();

    // Add the Flot version string to the footer


    // A custom label formatter used by several of the plots

    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }

    //

    function setCode(lines) {
        $("#code").text(lines.join("\n"));
    }

</script>
