<div class="team_list_wrapper">
  
  <h1>NFL Team</h1>
  <p>Sorted by their division.</p>

  <?php
    // debug helper function
    function pvar($var) {
      echo '<pre>'. var_dump($var) .'</pre>';
    }
    // Get API data using PHP cURL
    $ch = curl_init();
    $url = 'http://delivery.chalk247.com/team_list/NFL.JSON?api_key=74db8efa2a6db279393b433d97c2bc843f8e32b0';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
      echo $err;
    } else {
      $decoded = json_decode($response, true);
    }

    // pvar( $decoded );

    $teams = $decoded['results']['data']['team'];

    $division = [];
    // sort by division
    foreach ($teams as $key => $val) {
      $division[$key] = $val['division'];
    }
    array_multisort($division, SORT_DESC, $teams);
    $sorted_teams_by_division = $teams;

    // group and restructure the array
    $group_teams_by_division = [];
    foreach ($sorted_teams_by_division as $key => $val) {
      $group_teams_by_division[$val['division']][] = array(
        'name' => $val['name'],
        'nickname' => $val['nickname'],
        'display_name' => $val['display_name'],
        'id' => $val['id'],
        'conference' => $val['conference'],
      );
    }

    ?>
    <div class="team_list_container">

      <?php foreach($group_teams_by_division as $key => $val):?>

        <ul class="team_list">
          <h3><?php echo $key; ?></h3>
          <?php foreach($val as $data): ?>
            <li>
              <ul>
                <li>
                  <p>Team name:</p>
                  <span><?php echo $data['name']; ?></span>
                </li>
              </ul>
              <ul>
                <li>
                  <p>Team nickname:</p>
                  <span><?php echo $data['nickname']; ?></span>
                </li>
              </ul>
              <ul>
                <li>
                  <p>Team state:</p>
                  <span><?php echo $data['display_name']; ?></span>
                </li>
              </ul>

              <ul>
                <li>
                  <p>Conference:</p>
                  <span><?php echo $data['conference']; ?></span>
                </li>
              </ul>
            </li>
          <?php endforeach; ?>
        </ul>

      <?php endforeach; ?>

    </div>

</div>