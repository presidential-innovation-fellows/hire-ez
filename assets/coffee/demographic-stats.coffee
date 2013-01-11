$(document).on "ready page:load", ->
  if Rfpez.current_page("demographic-stats")
    genderChart = Raphael('gender-chart', 250, 180)
    gendChartOpts =
      legend:
        ['female', 'male', 'other']
      legendpos: "east"
    genderChart.piechart(75, 75, 75, demographicStats.gender, gendChartOpts)

    raceChart = Raphael('race-chart', 500, 200)
    raceChart.barchart(10, 10, 260, 160, demographicStats.race)
    # .label(['pacific_islander', 'hispanic_latino', 'american_indian', 'white', 'black', 'asian'])