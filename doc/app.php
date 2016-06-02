<?php

require __DIR__.'../vendor/autoload.php';

// Create a new sprint
$sprint = new \TrelloBurndown\Model\Sprint();
// Set a start date
$sprint->setStart(new \DateTime('2016-05-24'));
// Set a duration
$sprint->setDuration(new \DateInterval('P14D'));

//Create a new Trello Client
$trelloClient = new \TrelloBurndown\Client\TrelloClient('{Your Key}', '{Your Token');

// Create a new generator and pass your client as argument.
$burndownGenerator = new \TrelloBurndown\BurndownGenerator($trelloClient);
/*
 * Add on or more board with the board full name.
 * Pay attention, if the board name is wrong or cannot
 * be find with your Trello client connection, this will do nothing.
 */
$burndownGenerator->addBoard('My Board');
/*
 * Add one or more Todo List with the list full name.
 * If you have add more than one board and some of the boards you've  add
 * contain lists with the same name, you can specify the board name as second parameter.
 * addTodoList('Todo', 'My second Board');
 *
 */
$burndownGenerator->addTodoList('Todo');
/*
 * Add one or more Work In Progress list with the list full name.
 */
$burndownGenerator->addWipList('In Progress');
/*
 * Add one or more Done list with the list full name.
 */
$burndownGenerator->addDoneList('To Validate 1');
$burndownGenerator->addDoneList('To Validate 2');

/*
 * Generate the Story Point Burndown by passing the sprint as agument.
 * This method will return a StoryPointBurndown Object
 */
$burndown = $burndownGenerator->getStoryPointBurndown($sprint);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example with a side menu that hides on mobile, just like the Pure website.">
    <title>Trello Burndown</title>
</head>
<body>
<div id="layout">
    <div class="content">
        <div id="burnDown" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
    function formatKeyToDate(data) {
        var series = [];
        for (var id in data) {
            var date = id.split('-');
            series.push([Date.UTC(date[0], date[1] - 1, date[2]), data[id]]);
        }
        return series;
    }

    var realBurnDown = <?php echo json_encode($burndown->generate()['real']) ?>;
    var idealBurnDown = <?php echo json_encode($burndown->generate()['theorical']) ?>;

    realBurnDown = formatKeyToDate(realBurnDown);
    idealBurnDown = formatKeyToDate(idealBurnDown);

    console.log(realBurnDown);


    $(function () {
        $('#burnDown').highcharts({
            title: {
                text: 'BurnDown Chart',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Days'
                },
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%e of %b'
                }
            },
            yAxis: {
                title: {
                    text: 'Story Points'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'Story Points'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Real BurnDown',
                data: realBurnDown
            }, {
                name: 'Ideal BurnDown',
                data: idealBurnDown
            }]
        });
    });
</script>

</body>
</html>

