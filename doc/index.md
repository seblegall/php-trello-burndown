# Php Trello Burndown

## Usage

To create a story point burndown, you need to initialize some classes and then, generate the burndown.

### Create a Trello client

First, start by creating a Trello client with you api key and token.
Trello API key and token can be found at [https://trello.com/app-key](https://trello.com/app-key).

Then, let's create our client :

```PHP
$trelloClient = new \TrelloBurndown\Client\TrelloClient('{your_api_key}', '{your_api_token}');
```
### Create a burndown generator

After creating a Trello client, the next step is to create a burndown generator and add one or more board then, add one or more todo list, work in progress list and done list.

```PHP
// Create a new generator and pass your client as argument.
$burndownGenerator = new \TrelloBurndown\BurndownGenerator($trelloClient);
/*
 * Add on or more board with the board full name.
 * Pay attention, if the board name is wrong or cannot
 * be find with your Trello client connection, this will do nothing.
 */
$burndownGenerator->addBoard('FFB / EASI');
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
$burndownGenerator->addDoneList('To Validate (Dev)');
$burndownGenerator->addDoneList('To Validate (Préprod)');
```

### Create a new sprint

With PHP Trello Burndown, you will be able to get a burndown of a past sprint. So, you need to create a sprint with a start date and a duration. The Sprint class will do the rest for you.

```PHP
/*
 * Create a new sprint
 */
$sprint = new \TrelloBurndown\Model\Sprint();
/*
 * Set a start date
 */
$sprint->setStart(new \DateTime('2016-05-24'));
/*
 * Set a duration
 */
$sprint->setDuration(new \DateInterval('P14D'));
```

### Generate a burndown

After adding at least one todo list, one wip list and one done list, you'll be able to generate a story point burndown.

**Story Point will be read in the card name. Example : '(8) New task' will return 8**

```PHP
/*
 * Generate the Story Point Burndown by passing the sprint as agument.
 * This method will return a StoryPointBurndown Object
 */
$burndown = $burndownGenerator->getStoryPointBurndown($sprint);
/*
 * Call the generate() method to get an array representing your burndown
 */
 echo $burndown->generate();
```

Here is an example of what the `generate()` method will return :

```PHP
$expectedBrundown =
            [
                'real' => [
                        '2016-05-24' => 42,
                        '2016-05-25' => 42,
                        '2016-05-26' => 42,
                        '2016-05-27' => 42,
                        '2016-05-30' => 37,
                        '2016-05-31' => 28,
                        '2016-06-01' => 16,
                        '2016-06-02' => 2,
                        '2016-06-03' => -12,
                    ],
                'theorical' => [
                        '2016-05-24' => 42,
                        '2016-05-25' => 37.33,
                        '2016-05-26' => 32.66,
                        '2016-05-27' => 27.99,
                        '2016-05-30' => 23.32,
                        '2016-05-31' => 18.65,
                        '2016-06-01' => 13.98,
                        '2016-06-02' => 9.31,
                        '2016-06-03' => 4.64,
                        '2016-06-06' => -0.03,
                    ],
            ];
```

## Generate a chart

The `generate()` method return an array you can use with any JavaScript lib that let you generate a chart.

Here is an example with [HighChart](www.highcharts.com)

```
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
```

## Live Demo

Use the demo.php file to see a live demo.

(You may configure you vhost root file to be `php-trello-burndown/doc/demo.php`)