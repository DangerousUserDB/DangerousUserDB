function getThisTime(t, n) {
    return t - n;
}

var one = getThisTime(time, 6);
var two = getThisTime(time, 5);
var three = getThisTime(time, 4);
var four = getThisTime(time, 3);
var five = getThisTime(time, 2);
var six = getThisTime(time, 1);

new Chart(document.getElementById("traff"),{"type":"bar","data":{"labels":[one, two, three, four, five, six, time],"datasets":[{"label":"My First Dataset","data":[65,59,80,81,56,55,40],"fill":false,"backgroundColor":["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)"],"borderColor":["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)"],"borderWidth":1}]},"options":{"scales":{"yAxes":[{"ticks":{"beginAtZero":true}}]}}});
