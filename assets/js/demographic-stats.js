
Rfpez.demographic_stats = function(stats) {
  var gendChartOpts, genderChart, raceChart;
  genderChart = Raphael('gender-chart', 250, 180);
  gendChartOpts = {
    legend: ['female', 'male', 'other'],
    legendpos: "east"
  };
  genderChart.piechart(75, 75, 75, stats.gender, gendChartOpts);
  raceChart = Raphael('race-chart', 500, 200);
  return raceChart.barchart(10, 10, 260, 160, stats.race);
};
