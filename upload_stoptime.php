<?php
    // upload_stoptime.php
    function sec_to_time($sec){
      $h = floor($sec / 3600);
      $m = floor($sec / 60 % 60);
      $s = floor($sec % 60);
      $time = sprintf('%02d:%02d:%02d', $h, $m, $s);
      return $time;
    }

    include("config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //$sql = "UPDATE ...";
      // TODO: Mindenhol szükséges a formátum ellenőrzése!!!
      $RowID = $_POST['RowID'];
      $Time_Start = $_POST['Time_Start'];
      $Time_Stop = $_POST['Time_Stop'];
      $Time_Minus = $_POST['Time_Minus'];
      $Time_WorkStop = $_POST['Time_WorkStop'];
      $WorkTime_InSec = $_POST['WorkTime_InSec'];
      $WorkTime_InHour = $_POST['WorkTime_InHour'];
      $ID_Diszpo = $_POST['ID_Diszpo'];
      $ID_ModuleName = $_POST['ID_ModuleName'];
      $ID_WorkDescription = $_POST['ID_WorkDescription'];

      $sql = "UPDATE WorkTime SET StopTime='$Time_Stop',
                                  MinusTime='$Time_Minus',
                                  WorkTimeStop='$Time_WorkStop',
                                  WorkTimeInSec='$WorkTime_InSec',
                                  WorkTimeHour='$WorkTime_InHour'
                                  WHERE ID='$RowID'";
      $result_time = mysqli_query($db, $sql);

      $sql = "UPDATE WorkTime SET ID_Diszpo ='$ID_Diszpo' WHERE ID = '$RowID'";
      $result_diszpo = mysqli_query($db, $sql);

      $sql = "UPDATE WorkTime SET ID_ModuleName ='$ID_ModuleName' WHERE ID = '$RowID'";
      $result_modul = mysqli_query($db, $sql);

      $sql = "UPDATE WorkTime SET ID_WorkDescription ='$ID_WorkDescription' WHERE ID = '$RowID'";
      $result_desc = mysqli_query($db, $sql);

      $resp = array("Header"=>"Valmi válasz a webszervertőléáű!!! :D",
                    "Time_Start"=> $Time_Start,
                    "Time_Stop"=> $Time_Stop,
                    "Time_Minus"=> $Time_Minus,
                    "Time_WorkStop"=> $Time_WorkStop,
                    "WorkTime_InSec"=> $WorkTime_InSec,
                    "WorkTime_InHour"=> $WorkTime_InHour,
                    "MYSQL UPDATE Time"=> $result_time,
                    "MYSQL UPDATE Diszpo"=> $result_diszpo,
                    "MYSQL UPDATE Modul"=> $result_modul,
                    "MYSQL UPDATE Desc"=> $result_desc,
                  );

      echo json_encode($resp);
    }

    mysqli_close($db);

?>
