<?php
    // upload_starttime.php
    $start_time = $_POST["start_time"];

    include("config.php");
    $sql = "INSERT INTO worktime (StartTime) VALUES ('$start_time')";
    $result = mysqli_query($db, $sql);

    if($result != NULL)
    {
      $sql = "SELECT MAX(ID) FROM worktime LIMIT 1";
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

      $result = $row['MAX(ID)'];
    }
    else
    {
      $result = "Database error";
    }

    mysqli_close($db);
    echo $result;
?>
