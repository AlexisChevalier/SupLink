var chartTop;
var chartLeft;
var chartRight;

//Gestion des graphiques
$(document).ready(function () {
    chartTop = new Highcharts.Chart({
        chart:{
            renderTo:'clicChart',
            type:'area',
            backgroundColor:'#a5defc'
        },
        colors:[
            '#3b68a5'
        ],
        legend:{
            enabled:false
        },
        title:{
            text:'Clicks over the time'
        },
        xAxis:{
            type:'datetime',
            maxZoom:24 * 3600000
        },
        yAxis:{
            title:{
                text:'Number of clicks'
            },
            labels:{
                formatter:function () {
                    return this.value;
                }
            }
        },
        tooltip:{
            xDateFormat:'%Y-%m-%d',
            shared:true
        },
        series:[
            {
                name:'Clicks'
            }
        ]
    });

    chartLeft = new Highcharts.Chart({
        chart:{
            renderTo:'referrerChart',
            plotBackgroundColor:null,
            plotBorderWidth:null,
            plotShadow:false,
            backgroundColor:'#a5defc',
            spacingLeft:100,
            spacingRight:100,
            width:480
        },
        title:{
            text:'Link referers'
        },
        tooltip:{
            pointFormat:'{series.name}: <b>{point.y}</b>',
            percentageDecimals:1
        },
        series:[
            {
                type:'pie',
                name:'Clicks',
                data:[
                ]
            }
        ]
    });
    chartRight = new Highcharts.Chart({
        chart:{
            renderTo:'countryChart',
            plotBackgroundColor:null,
            plotBorderWidth:null,
            plotShadow:false,
            backgroundColor:'#a5defc',
            spacingLeft:100,
            spacingRight:100,
            width:480
        },
        title:{
            text:'Clicks by country'
        },
        tooltip:{
            pointFormat:'{series.name}: <b>{point.y}</b>',
            percentageDecimals:1
        },
        series:[
            {
                type:'pie',
                name:'Clicks',
                data:[
                ]
            }
        ]
    });
});