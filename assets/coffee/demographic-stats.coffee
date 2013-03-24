Rfpez.demographic_stats = (stats) ->
  genderChart = Raphael('gender-chart', 250, 180)
  gendChartOpts =
    legend:
      ['female', 'male', 'other']
    legendpos: "east"
  genderChart.piechart(75, 85, 75, stats.gender, gendChartOpts)

  raceChart = Raphael('race-chart', 600, 200)
  raceChart.hbarchart(120, 0, 360, 180, stats.race)
  Raphael.g.axis(120,153,165,null,null,6,1,stats.raceLabels.reverse(), "|", 0, raceChart)