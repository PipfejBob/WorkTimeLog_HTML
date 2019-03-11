<?php
include("config.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stop_time']))
{
  // Stop time menü gomb
  if($_POST['stop_time'] == "?"){
    // Az utolsó mentett munka lekérdezése
    $sql = "SELECT ID, ID_Diszpo, ID_ModuleName, ID_WorkDescription
      FROM worktime ORDER BY StopTime DESC LIMIT 1";
    $result = mysqli_query($db, $sql);
    $row['LastRow'] = mysqli_fetch_array($result,MYSQLI_ASSOC);

    // Az aktuális ID és Starttime lekérdezése
    $sql = "SELECT ID, StartTime FROM worktime WHERE StopTime IS NULL AND
      ID = (SELECT MAX(ID) FROM worktime)";
    $result = mysqli_query($db, $sql);
    $row['ActRow'] = mysqli_fetch_array($result,MYSQLI_ASSOC);

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
?>
