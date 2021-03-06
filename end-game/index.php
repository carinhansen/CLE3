<?php
include 'dbh.php';
session_start();
include 'FootballData.php';



?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CLE 3 - mobile</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <iframe id="iframe" width="100%" height="750px" src="https://www.youtube.com/embed/Hs6vJ8W3H4g?rel=0&amp;controls=1&amp;showinfo=0;autoplay=1;loop=1; frameborder="0" allowfullscreen ></iframe>
            </div>
                <div class="col-md-4">
                    <img src="img/fey-logo.png" height="100px" width="auto" style="margin-left: auto; margin-right: auto; display: block; margin-top: 30px; margin-bottom:30px;"/>
                    <hr>
                    <?php
                    // Create instance of API class
                    $api = new FootballData();
                    // fetch and dump summary data for premier league' season 2015/16
                    $soccerseason = $api->getSoccerseasonById(398);
                    // search for desired team
                    $searchQuery = $api->searchTeam(urlencode("Feyenoord"));

                    // var_dump searchQuery and inspect for results
                    $response = $api->getTeamById($searchQuery->teams[0]->id);
                    $fixtures = $response->getFixtures('')->fixtures;
                    array_slice($fixtures, -3, 3, true);
                    $new = array_filter($fixtures, function ($var) {
                        return ($var -> status == 'FINISHED');
                    });
                    $count = count($new) - 1;
                    $tot = count($new) - 5;
                    ?>
                    <h3>De laatste 5 wedstrijden:</h3>
                    <table class="table table-striped">
                        <tr>
                            <th>Thuis</th>
                            <th></th>
                            <th>Uit</th>
                            <th colspan="3">Resultaat</th>
                        </tr>
                        <?php for ($x = $count; $x >= $tot; $x--) { ?>
                            <tr>
                                <td><?php echo $new[$x]->homeTeamName; ?></td>
                                <td>-</td>
                                <td><?php echo $new[$x]->awayTeamName; ?></td>
                                <td><?php echo $new[$x]->result->goalsHomeTeam; ?></td>
                                <td>:</td>
                                <td><?php echo $new[$x]->result->goalsAwayTeam; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <hr>
                    <div>
                        <?php
                        $vibe = "";
                        $thuis = $new[$count]->homeTeamName;
                        $uit = $new[$count]->awayTeamName;
                        $goalthuis = $new[$count]->result->goalsHomeTeam;
                        $goaluit = $new[$count]->result->goalsAwayTeam;
                        if ($thuis = 'Feyenoord Rotterdam'){
                            if ($goalthuis >= $goaluit){
                                $vibe = "goed";
                            } else {
                                $vibe = "slecht";
                            }
                            $_SESSION['vibe'] = $vibe;
                        }
                        if ($thuis =! 'Feyenoord Rotterdam'){
                            if ($goaluit >= $goalthuis){
                                $vibe = "goed";
                            } else {
                                $vibe = "slecht";
                            }
                            $_SESSION['vibe'] = $vibe;
                        }
                        ?>
                        <h2>Winnaars</h2>
                        <table class="table">
						<thead>
							<tr>

                                <th>mensje id</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<?php
								$sql = "SELECT * FROM scores, users WHERE home='$goalthuis' AND away='$goaluit' AND scores.users_id=users.id";

								$result = mysqli_query($conn, $sql);
								if (mysqli_num_rows($result) > 0) {
                                    // output data from each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo '<td>'.$row['username'].'</td></tr>';
                                    }
                                } else {
                                    echo "0 results";
                                }
                            ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul>
        <li><a href="#" onclick="playSomeSound(Rock)"class="genre">Rock</a></li>
    </ul>
    <div id="target"></div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="karaoke.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script><!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//connect.soundcloud.com/sdk.js"></script>
    <script src="main.js"></script>

</body>
</html>