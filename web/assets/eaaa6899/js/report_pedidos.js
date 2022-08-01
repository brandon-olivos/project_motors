 $(document).ready(function () {
       var data = [];
  //var data=[];
  $.ajax({
        type: "get",
        dataType: 'json',
        url: APP_URL + '/dashboard/default/totales-por-cliente',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

        success: function (response) {

           // var produce = []

            $(response.data).each(function(i, v) {
                //push values as x & y
                data.push({
                    entidad: v.razon_social,
                    visits: parseInt(v.cantidad),
                    id_entidad:parseInt(v.id_cliente),
                   
                })

            })
            
        console.log(data);
        am5.ready(function() {
  
    


// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv1");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: false,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX",
  layout: root.verticalLayout
}));

var colors = chart.get("colors");

prepareParetoData();

function prepareParetoData() {
  var total = 0;

  for (var i = 0; i < data.length; i++) {
    var value = data[i].visits;
    total += value;
  }

  var sum = 0;
  for (var i = 0; i < data.length; i++) {
    var value = data[i].visits;
    sum += value;
    data[i].pareto = sum / total * 100;
  }
}



// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: "entidad",
  renderer: am5xy.AxisRendererX.new(root, {
    minGridDistance: 30
  })
}));


xAxis.get("renderer").labels.template.setAll({
  paddingTop: 1,
   rotation: -90,
    centerY: am5.p50,
  //centerX: 0,
  /// centerY: am5.p50,
  centerX: 0,
  //paddingRight: 15
});

xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: am5xy.AxisRendererY.new(root, {})
}));

var paretoAxisRenderer = am5xy.AxisRendererY.new(root, {opposite:true});
var paretoAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: paretoAxisRenderer,
  min:0,
  max:200,
  strictMinMax:true
}));

paretoAxisRenderer.grid.template.set("forceHidden", true);
paretoAxis.set("numberFormat", "#'%");


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.ColumnSeries.new(root, {
 
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "visits",
  categoryXField: "entidad"
}));

series.columns.template.setAll({
  tooltipText: "{categoryX}: {valueY}",
  tooltipY: 0,
  strokeOpacity: 0,
  cornerRadiusTL: 6,
  cornerRadiusTR: 6
});

series.columns.template.events.on("click", function(e) {
 //console.log(e.target)
 selectSlice(e.target)
});

series.columns.template.adapters.add("fill", function(fill, target) {
     
  return chart.get("colors").getIndex(series.dataItems.indexOf(target.dataItem));
})


// pareto series
var paretoSeries = chart.series.push(am5xy.LineSeries.new(root, {
  xAxis: xAxis,
  yAxis: paretoAxis,
  valueYField: "pareto",
  categoryXField: "entidad",
  stroke: root.interfaceColors.get("alternativeBackground"),
  maskBullets:false
}));

paretoSeries.bullets.push(function() {
  return am5.Bullet.new(root, {
    locationY: 1,
    sprite: am5.Circle.new(root, {
      radius: 5,
      fill: series.get("fill"),
      stroke:root.interfaceColors.get("alternativeBackground")
    })
  })
})

series.data.setAll(data);
paretoSeries.data.setAll(data);

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear();
chart.appear(1000, 100);

});     
        }});
    
// end am5.ready()
function selectSlice(slice) {
  selectedSlice = slice;
  var dataItem = slice.dataItem;
  var dataContext = dataItem.dataContext;

  if (dataContext) { 
    console.log(dataContext.id_entidad);
 
  }
  
}

 });