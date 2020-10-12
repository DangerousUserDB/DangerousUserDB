function getThisTime(t, n) {
    return t - n;
}

var one = getThisTime(time, 6);
var two = getThisTime(time, 5);
var three = getThisTime(time, 4);
var four = getThisTime(time, 3);
var five = getThisTime(time, 2);
var six = getThisTime(time, 1);

google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = new google.visualization.DataTable();
      data.addColumn('timeofday', 'Hour');
      data.addColumn('number', 'Request Ammount');

      data.addRows([
        [{v: [8, 0, 0], f: six+' am'}, 1],
        [{v: [9, 0, 0], f: five+' am'}, 2],
        [{v: [10, 0, 0], f: four+' am'}, 3],
        [{v: [11, 0, 0], f: three+' am'}, 4],
        [{v: [12, 0, 0], f: two+'12 pm'}, 5],
        [{v: [13, 0, 0], f: one+'1 pm'}, 6],
        [{v: [13, 0, 0], f: time+' pm'}, 6]
      ]);

      var options = {
        title: 'Traffic',
        hAxis: {
          title: 'Hour',
          format: 'h:mm a',
          viewWindow: {
            min: [7, 30, 0],
            max: [17, 30, 0]
          }
        }
      };

      var chart = new google.visualization.ColumnChart(
        document.getElementById('chart_div'));

      chart.draw(data, options);
    }