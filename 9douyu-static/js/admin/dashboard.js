jQuery(document).ready(function(){
	
	function showTooltip(x, y, contents) {
		jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css( {
		  position: 'absolute',
		  display: 'none',
		  top: y + 5,
		  left: x + 5
		}).appendTo("body").fadeIn(200);
	 }
    var TickArr     = eval(jQuery("#fiftenDateStr").val());
    var uploads     = eval(jQuery("#fiftenDayInvest").val());
    var downloads   = eval(jQuery("#fiftenDayRefund").val());
    var maxVal      = jQuery("#maxVal").val();
    var plot = jQuery.plot(jQuery("#basicflot"),
		[ { data: uploads,
          label: "投资",
          color: "#1CAF9A"
        },
        { data: downloads,
          label: "回款",
          color: "#428BCA"
        }
        ],
        {
		    series: {
			    lines: {
                show: true,
                fill: true,
                lineWidth: 1,
                fillColor: {
                    colors: [
                        { opacity: 0.5 },
                        { opacity: 0.5 }
                    ]
                }
                },
                points: {
                     show: true
                },
                shadowSize: 0
		    },
		    legend: {
                position: 'nw'
            },
            grid: {
                hoverable: true,
                clickable: true,
                borderColor: '#ddd',
                borderWidth: 1,
                labelMargin: 10,
                backgroundColor: '#fff'
            },
            yaxis: {
                min: 0,
                max: maxVal,
                color: '#eee'
            },
            xaxis: {
                ticks: TickArr, //x轴自定义刻度数据
                color: '#eee'
            }
		});

	var previousPoint = null;
    jQuery("#basicflot").bind("plothover", function (event, pos, item) {
        jQuery("#x").text(pos.x.toFixed(2));
        jQuery("#y").text(pos.y.toFixed(2));

		if(item) {
		    if (previousPoint != item.dataIndex) {
			    previousPoint = item.dataIndex;

			    jQuery("#tooltip").remove();
			    var x = item.datapoint[0].toFixed(2),
			    y = item.datapoint[1].toFixed(2);

			    showTooltip(item.pageX, item.pageY,
				item.series.label +  y);
		    }

		} else {
		    jQuery("#tooltip").remove();
		    previousPoint = null;
		}

	});

	jQuery("#basicflot").bind("plotclick", function (event, pos, item) {
		if (item) {
		    plot.highlight(item.series, item.datapoint);
		}
	});

    var sourcePc	=  jQuery("#chart2_pc").val()
    var sourceIos	=  jQuery("#chart2_ios").val()
    var sourceAndroid= jQuery("#chart2_android").val()
    var sourceWap	=  jQuery("#chart2_wap").val()
    // Donut Chart
    new Morris.Donut({
        element: 'donut-chart2',
        data: [
            {label: "PC", 	value: sourcePc},
            {label: "IOS", 	value: sourceIos},
            {label: "Android", value: sourceAndroid},
            {label: "WAP", 	value: sourceWap}
        ],
        colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
    });

    var fiftenDayOrder  = eval(jQuery("#fiftenDayOrder").val());
    var fiftenDayOrderStr       = "[";
    var fiftenDayOrderStr1      = "";
    var fiftenDayOrderStr2      = "]";
    if(fiftenDayOrder){
        $.each( fiftenDayOrder, function(iorder,norder){
            var ndate = norder.date
            fiftenDayOrderStr1 += "{y:"+'\''+ndate+'\''+",a:"+norder.rechangeCash+",b:"+norder.withdrawCash+"},"

        })
    }

    fiftenDayOrderStr1=fiftenDayOrderStr1.substring(0,fiftenDayOrderStr1.length-1)
    fiftenDayOrderStr = fiftenDayOrderStr + fiftenDayOrderStr1 + fiftenDayOrderStr2
    console.log(fiftenDayOrderStr);
    new Morris.Area({
        // ID of the element in which to draw the chart.
        element: 'area-chart',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: eval(fiftenDayOrderStr),
        xkey:   'y',
        ykeys:  ['a', 'b'],
        labels: ['充值', '提现'],
        lineColors: ['#1CAF9A', '#F0AD4E'],
        lineWidth:  '1px',
        fillOpacity: 0.8,
        smooth:     false,
        hideHover:  true
    });

    // new Morris.Line({
    //     // ID of the element in which to draw the chart.
    //     element: 'line-chart',
    //     // Chart data records -- each entry in this array corresponds to a point on
    //     // the chart.
    //     data: [
    //         { y: '2006', a: 50, b: 0 },
    //         { y: '2007', a: 60,  b: 25 },
    //         { y: '2008', a: 45,  b: 30 },
    //         { y: '2009', a: 40,  b: 20 },
    //         { y: '2010', a: 50,  b: 35 },
    //         { y: '2011', a: 60,  b: 50 },
    //         { y: '2012', a: 65, b: 55 }
    //     ],
    //     xkey: 'y',
    //     ykeys: ['a', 'b'],
    //     labels: ['Series A', 'Series B'],
    //     gridTextColor: 'rgba(255,255,255,0.5)',
    //     lineColors: ['#fff', '#fdd2a4'],
    //     lineWidth: '2px',
    //     hideHover: 'always',
    //     smooth: false,
    //     grid: false
    // });
    //
    // jQuery('#sparkline').sparkline([4,3,3,1,4,3,2,2,3,10,9,6], {
		// type: 'bar',
		// height:'30px',
    //     barColor: '#428BCA'
    // });
    //
    //
    // jQuery('#sparkline2').sparkline([9,8,8,6,9,10,6,5,6,3,4,2], {
		// type: 'bar',
    //     height:'30px',
    //     barColor: '#999'
    // });
    //
    //
    // jQuery('#table1').dataTable({
    //     "iDisplayLength": 5,
    //     "bLengthChange": false
    // });
    //
    // // Chosen Select
    // jQuery("select").chosen({
    //     'min-width': '100px',
    //     'white-space': 'nowrap',
    //     disable_search_threshold: 10
    // });

});