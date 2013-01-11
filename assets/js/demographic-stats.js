
$(document).on("ready page:load", function() {
  var gendChartOpts, genderChart, raceChart;
  if (Rfpez.current_page("demographic-stats")) {
    genderChart = Raphael('gender-chart', 250, 180);
    gendChartOpts = {
      legend: ['female', 'male', 'other'],
      legendpos: "east"
    };
    genderChart.piechart(75, 85, 75, demographicStats.gender, gendChartOpts);
    raceChart = Raphael('race-chart', 600, 200);
    raceChart.hbarchart(120, 0, 360, 180, demographicStats.race);
    return Raphael.g.axis(120, 153, 165, null, null, 6, 1, demographicStats.raceLabels.reverse(), "|", 0, raceChart);
  }
});
