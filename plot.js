var data = [[5,3,2,1,2], [10,17,4,5,8], [15,4,2,6,7], [2,8,3,10,14]]; //first is mean, second is std, 3rd is entropy, 4th is start, 5th is end
   
var margin = {top: 20, right: 15, bottom: 60, left: 60}
  , width = 500 - margin.left - margin.right
  , height = 500 - margin.top - margin.bottom;

var x = d3.scale.linear()
          .domain([0, d3.max(data, function(d) { return d[1]; })])
          .range([ 0, width ]);

var y = d3.scale.linear()
  	      .domain([0, d3.max(data, function(d) { return d[0]; })])
  	      .range([ height, 0 ]);

var chart = d3.select('.meanStd')
              .append('svg:svg')
              .attr('width', width + margin.right + margin.left)
              .attr('height', height + margin.top + margin.bottom)
              .attr('class', 'chart')

var main = chart.append('g')
                .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')')
                .attr('width', width)
                .attr('height', height)
                .attr('class', 'main')   
    
// draw the x axis
var xAxis = d3.svg.axis()
                  .scale(x)
                  .orient('bottom');

// add the tooltip area to the webpage
var tooltip = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0);

main.append('g')
    .attr('transform', 'translate(0,' + height + ')')
    .attr('class', 'main axis date')
    .call(xAxis)
    .append("text")
    .attr("class", "label")
    .attr("x", width)
    .attr("y", -6)
    .style("text-anchor", "end")
    .text("Standard Deviation");

// draw the y axis
var yAxis = d3.svg.axis()
              .scale(y)
              .orient('left');

main.append('g')
    .attr('transform', 'translate(0,0)')
    .attr('class', 'main axis date')
    .call(yAxis)
    .append("text")
    .attr("class", "label")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end")
    .text("Mean");

var g = main.append("svg:g"); 

g.selectAll("scatter-dots")
  .data(data)
  .enter().append("svg:circle")
  .attr("cx", function (d,i) { return x(d[1]); } )
  .on("mouseover", function(d) {
      tooltip.transition()
           .duration(200)
           .style("opacity", .9);
      tooltip.html("intron: [" + d[3]+", "+d[4]+"]")//d["Cereal Name"] + "<br/> (" + xValue(d) + ", " + yValue(d) + ")"
           .style("left", (d3.event.pageX + 5) + "px")
           .style("top", (d3.event.pageY - 28) + "px");
  })
  .on("mouseout", function(d) {
      tooltip.transition()
           .duration(500)
           .style("opacity", 0);
  })
  .attr("cy", function (d) { return y(d[0]); } )
  .attr("class","ms")
  .attr("r", 2);

//new chart
var x1 = d3.scale.linear()
          .domain([0, d3.max(data, function(d) { return d[2]; })])
          .range([ 0, width ]);
console.log(d3.max(data, function(d) { return d[2]; }));

var chart1 = d3.select('.meanEntropy')
              .append('svg:svg')
              .attr('width', width + margin.right + margin.left)
              .attr('height', height + margin.top + margin.bottom)
              .attr('class', 'chart')

var main1 = chart1.append('g')
                .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')')
                .attr('width', width)
                .attr('height', height)
                .attr('class', 'main')   
    
// draw the x axis
var xAxis1 = d3.svg.axis()
                  .scale(x1)
                  .orient('bottom');


main1.append('g')
    .attr('transform', 'translate(0,' + height + ')')
    .attr('class', 'main axis date')
    .call(xAxis1)
    .append("text")
    .attr("class", "label")
    .attr("x", width)
    .attr("y", -6)
    .style("text-anchor", "end")
    .text("Entropy");



main1.append('g')
    .attr('transform', 'translate(0,0)')
    .attr('class', 'main axis date')
    .call(yAxis)
    .append("text")
    .attr("class", "label")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end")
    .text("Mean");

var g1 = main1.append("svg:g"); 

g1.selectAll("scatter-dots")
  .data(data)
  .enter().append("svg:circle")
  .attr("cx", function (d,i) { return x1(d[2]); } )
  .on("mouseover", function(d) {
      tooltip.transition()
           .duration(200)
           .style("opacity", .9);
      tooltip.html("intron: [" + d[3]+", "+d[4]+"]")//d["Cereal Name"] + "<br/> (" + xValue(d) + ", " + yValue(d) + ")"
           .style("left", (d3.event.pageX + 5) + "px")
           .style("top", (d3.event.pageY - 28) + "px");
  })
  .on("mouseout", function(d) {
      tooltip.transition()
           .duration(500)
           .style("opacity", 0);
  })
  .attr("cy", function (d) { return y(d[0]); } )
  .attr("class","me")
  .attr("r", 2);

//new chart
var x2 = d3.scale.linear()
          .domain([0, d3.max(data, function(d) { return (d[4]-d[3]+1); })])
          .range([ 0, width ]);


var chart2 = d3.select('.meanLength')
              .append('svg:svg')
              .attr('width', width + margin.right + margin.left)
              .attr('height', height + margin.top + margin.bottom)
              .attr('class', 'chart')

var main2 = chart2.append('g')
                .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')')
                .attr('width', width)
                .attr('height', height)
                .attr('class', 'main')   
    
// draw the x axis
var xAxis2 = d3.svg.axis()
                  .scale(x2)
                  .orient('bottom');


main2.append('g')
    .attr('transform', 'translate(0,' + height + ')')
    .attr('class', 'main axis date')
    .call(xAxis2)
    .append("text")
    .attr("class", "label")
    .attr("x", width)
    .attr("y", -6)
    .style("text-anchor", "end")
    .text("Length");



main2.append('g')
    .attr('transform', 'translate(0,0)')
    .attr('class', 'main axis date')
    .call(yAxis)
    .append("text")
    .attr("class", "label")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end")
    .text("Mean");

var g2 = main2.append("svg:g"); 

g2.selectAll("scatter-dots")
  .data(data)
  .enter().append("svg:circle")
  .attr("cx", function (d,i) { return x2(d[4]-d[3]+1); } )
  .on("mouseover", function(d) {
      tooltip.transition()
           .duration(200)
           .style("opacity", .9);
      tooltip.html("intron: [" + d[3]+", "+d[4]+"]")//d["Cereal Name"] + "<br/> (" + xValue(d) + ", " + yValue(d) + ")"
           .style("left", (d3.event.pageX + 5) + "px")
           .style("top", (d3.event.pageY - 28) + "px");
  })
  .on("mouseout", function(d) {
      tooltip.transition()
           .duration(500)
           .style("opacity", 0);
  })
  .attr("cy", function (d) { return y(d[0]); } )
  .attr("class","ml")
  .attr("r", 2);