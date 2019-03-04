<?php
    // upload_stoptime.php
    include("config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stop_time']))
  	{
      // Stop time menü gomb
      if($_POST['stop_time'] == "?"){
        $sql = "SELECT ID, StartTime FROM worktime WHERE StopTime IS NULL AND
          ID = (SELECT MAX(ID) FROM worktime)";
        $result = mysqli_query($db, $sql);
        $row['LastRow'] = mysqli_fetch_array($result,MYSQLI_ASSOC);
        /*
        if($result != NULL)
        {
          $id = $row['ID'];
          $start_time = $row['StartTime'];
        }
        else
        {
          echo "Nincs lezáratlan munka!!!";
        }
        */
        // Diszpók átadása
        $sql = "SELECT * FROM diszpo";
        $result = mysqli_query($db, $sql);
        $row['diszpo'] = mysqli_fetch_all($result,MYSQLI_ASSOC);

        // Modulok átadása
        $sql = "SELECT * FROM modulename";
        $result = mysqli_query($db, $sql);
        $row['modulename'] = mysqli_fetch_all($result,MYSQLI_ASSOC);
        
        // Munkaleírások átadása
        $sql = "SELECT * FROM workdescription";
        $result = mysqli_query($db, $sql);
        $row['workdescription'] = mysqli_fetch_all($result,MYSQLI_ASSOC);

        echo json_encode($row);
      }

    }
    mysqli_close($db);

?>
