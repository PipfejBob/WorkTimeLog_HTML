<html>

	<head>
		<title>Work Time Log</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta charset="utf-8" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css"/>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
	</head>

	<script>
		function date_to_string(d)
		{
			var date_str = "";
			var month = d.getMonth()+1;
			var day = d.getDate();
			var hour = d.getHours();
			var min = d.getMinutes();

			date_str = d.getFullYear() + "-" +
				(month<10 ? '0' : '') + month + '-' +
				(day<10 ? '0' : '') + day + ' ' +
				(hour<10 ? '0' : '') + hour + ':' +
				(min<10 ? '0' : '') + min;

			return date_str;
		}

		function ms_to_string(t)
		{
				var h = Math.floor(t / 3600000);
				var m = t % 3600000 / 60 / 1000;
				return (h<10 ? '0' : '') + h + ':' + (m<10 ? '0' : '') + m;
		}

		$(document).ready(function(){

			// START MENÜ
			$("#btn_start").click(function()
			{
				$('#start_modal').modal({
					centered: false,

					// OK gomb megnyomásásra
					onApprove: function()
					{
            console.log('Start Menü / OK button');
						start_time_text = document.getElementById("start_time_input").value;
						var data = {
							username: "PipfejBob",
            	start_time: start_time_text
        		};

						$.post("upload_starttime.php", data)
						.done(function( resp ) {
								var new_html_row = '<tr><td>' + resp + '</td><td>' + start_time_text + '</td>' +
									'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
								$('#wt_table tr:last').after(new_html_row);
						});
						document.title = "Idő";
        	},

					// Mégse gomb megnyomásásra
					onDeny: function()
					{
						console.log('Start Menü / Mégse button');
					}
				}).modal('show');
    		//$('#wt_table tr:last').after('<tr><td data-label="Name">Bob</td></tr>');
				var datetime_now = "";
				var d = new Date();
				datetime_now = date_to_string(d);
				document.getElementById("start_time_input").value = datetime_now;
  		});

			// START MODAL: ha nem helyes a dátum, akkor az OK gomb disabled
			$('#start_time_input').on('input', function() {
				var d = Date.parse($(this).val());
				// hibakezelés ha nem jó a formátum
				if(isNaN(d)){
					console.log('hibás dátum formátum!');
					document.getElementById("start_modal_btn_start").classList.add('disabled');
				}
				// ha jó a formátum
				else{
					d = new Date(parseInt(d, 10));
					d.setSeconds(0);
					console.log($(this).val() + ' -> parse: ' + d); 	// get the current value of the input field.
					document.getElementById("start_modal_btn_start").classList.remove('disabled');
				}
			});

			// STOP MENÜ
			$("#btn_stop").click(function()
			{
				$('.ui.dropdown').dropdown();

				$('.ui.dropdown.majom')
			  .dropdown({values: [
			  	{name: 'Munka 1', value: 'm1'},
			  	{name: 'Munka 2', value: 'm2', selected : true}
			  ]});

				$('#stop_modal').modal({
					centered: false,
					duration: 100,
					// OK gomb megnyomásásra
					onApprove: function()
					{
            console.log('Stop Menü / OK button');
        	},
					// Mégse gomb megnyomásásra
					onDeny: function()
					{
						console.log('Stop Menü / Mégse button');
					}
				}).modal('show');

				// MYSQL lekérdezés: az utolsó munka lezáratlan?
				// válasz: az ID vagy NULL
				var data = {
					username: "PipfejBob",
					stop_time: "?"
				};

				var Work = {
					RowID: "",
					Time_Start: "",
					Time_Stop: "",
					Time_Minus: "",
					Time_WorkStop: "",
					WorkTime: "",
					WorkTime_InSec: "",
					WorkTime_InHour: "",
					Diszpo_ID: "",
					Diszpo_Name: "",
					Work_On: "",
					Work_Desc: ""
				};

				$.post("upload_stoptime.php", data)
				.done(function( resp ) {
						//debugger;
						//myObj = JSON.parse(resp);
						JSON_data = jQuery.parseJSON(resp);
						console.log(JSON_data);

						if(JSON_data.LastRow == null){

							alert("Hiba: Nincs elindított munka!");
						}
						else{
							// Az utolsó elindított munka lesz a munkaidő kezdete
							Work.RowID = JSON_data.LastRow.ID;

							tmp = Date.parse(JSON_data.LastRow.StartTime.substr(0, 16));
							if(isNaN(tmp)){}
							else{
								Work.Time_Start = new Date(tmp);
							}

							document.getElementById("stopm_start_time_in").value = date_to_string(Work.Time_Start);

							// Az aktuális idő lesz a munkaidő vége
							Work.Time_Stop = new Date();
							Work.Time_Stop.setSeconds(0);
							Work.Time_Stop.setMilliseconds(0);
							document.getElementById("stopm_stop_time_in").value = date_to_string(Work.Time_Stop);

							// Ebédidő kiszámítása
							console.log("Work.Time_Start = " + Work.Time_Start);
							console.log("Work.Time_Stop = " + Work.Time_Stop);
							minus_time_calc(Work);
							console.log("Work.Time_Minus = " + Work.Time_Minus + ' ms = ' + ms_to_string(Work.Time_Minus));
							document.getElementById("stopm_minus_time_in").value = ms_to_string(Work.Time_Minus);

							// Munkaidők kiszámítása: Time_WorkStop, WorkTime_InHour, WorkTime_InSec
							work_time_calc(Work);
							document.getElementById("stopm_wt_stop_in").value = date_to_string(Work.Time_WorkStop);
							document.getElementById("stopm_wt_hour_in").value = ms_to_string(Work.WorkTime);
							document.getElementById("stopm_wt_sec_in").value = Work.WorkTime_InSec;

							// Diszpó kiválasztása (legördülőből)

							// Modul kiválasztása (legördülőből)

							// Munkaleírás kiválasztása (legördülőből)

						}
				});	// post -> php

				// Minus time kiszámítása
				function minus_time_calc(Work){
					var wt = Work.Time_Stop.getTime() - Work.Time_Start.getTime();
					//console.log("Bent töltött idő = " + wt + ' ms = ' + ms_to_string(wt));

					if(wt <= 14400000){
						//console.log("wt <= 3:00");
						Work.Time_Minus = 0;
					}
					else if(wt > 14400000 && wt <= 31500000){
						//console.log("4:00 < wt <= 8:45");
						Work.Time_Minus = 2700000;	// 00:45
					}
					else if(wt > 31500000 && wt <= 32400000){
						//console.log("8:45 < wt <= 9:00");
						//Work.Time_Minus = wt - 28000000;
						Work.Time_Minus = 2700000;	// 00:45
					}
					else{
						//console.log("wt > 9:00");
						Work.Time_Minus = 2700000;	// 00:45
					}
					return 0;
				}

				// Munkaidők kiszámítása
				function work_time_calc(Work){
					Work.Time_WorkStop = new Date(Work.Time_Stop - Work.Time_Minus);
					Work.WorkTime = Work.Time_WorkStop - Work.Time_Start;
					Work.WorkTime_InSec = (Work.Time_Stop.getTime() - Work.Time_Start.getTime() - Work.Time_Minus)/1000;

					if(Work.WorkTime_InSec < 0){
						document.getElementById("error_stopm_wt_sec_in").classList.remove('hidden');
					}
					else{
						document.getElementById("error_stopm_wt_sec_in").classList.add('hidden');
					}
					return 0;
				}

				function stopm_approve_enable(Work){
					if(Work.Time_Start == "Invalid Date" || Work.Time_Stop == "Invalid Date" || Work.Time_Stop < Work.Time_Start || Work.Time_Minus == null){
						document.getElementById("stopm_btn_start").classList.add('disabled');
					}
					else{
						document.getElementById("stopm_btn_start").classList.remove('disabled');
					}

				}
				// STOP MODAL: Start Time input változás
				$('#stopm_start_time_in').on('input', function() {
					// ebédidő kiszámítás
					Work.Time_Start = new Date(Date.parse(document.getElementById("stopm_start_time_in").value));
					stopm_approve_enable(Work);

					if(Work.Time_Start != "Invalid Date")
					{
						document.getElementById("error_stopm_start_time_in").classList.add('hidden');
						Work.Time_Start.setSeconds(0);
						Work.Time_Start.setMilliseconds(0);
						//console.log(Work.Time_Start);
						minus_time_calc(Work);
						document.getElementById("stopm_minus_time_in").value = ms_to_string(Work.Time_Minus);
						work_time_calc(Work);
						document.getElementById("stopm_wt_stop_in").value = date_to_string(Work.Time_WorkStop);
						document.getElementById("stopm_wt_hour_in").value = ms_to_string(Work.WorkTime);
						document.getElementById("stopm_wt_sec_in").value = Work.WorkTime_InSec;
					}
					else{
						document.getElementById("error_stopm_start_time_in").classList.remove('hidden');
					}

				});
				// STOP MODAL: Stop Time input változás
				$('#stopm_stop_time_in').on('input', function() {
					Work.Time_Stop = new Date(Date.parse(document.getElementById("stopm_stop_time_in").value));
					stopm_approve_enable(Work);
					if(Work.Time_Stop != "Invalid Date"){
						document.getElementById("error_stopm_stop_time_in").classList.add('hidden');
						Work.Time_Stop.setSeconds(0);
						Work.Time_Stop.setMilliseconds(0);
						//console.log(Work.Time_Stop);
						minus_time_calc(Work);
						document.getElementById("stopm_minus_time_in").value = ms_to_string(Work.Time_Minus);
						work_time_calc(Work);
						document.getElementById("stopm_wt_stop_in").value = date_to_string(Work.Time_WorkStop);
						document.getElementById("stopm_wt_hour_in").value = ms_to_string(Work.WorkTime);
						document.getElementById("stopm_wt_sec_in").value = Work.WorkTime_InSec;
					}
					else{
						document.getElementById("error_stopm_stop_time_in").classList.remove('hidden');
					}

				});
				// STOP MODAL: Minus_time input változás
				$('#stopm_minus_time_in').on('input', function() {
					var str = document.getElementById("stopm_minus_time_in").value;
					var patt = "^([0-5]{1}[0-9]{1}):([0-5]{1}[0-9]{1})$";
					var result = str.match(patt);

					if(result != null){
						document.getElementById("error_stopm_minus_time_in").classList.add('hidden');
						str = document.getElementById("stopm_minus_time_in").value;
						//console.log(Work.Time_Minus);
						Work.Time_Minus = parseInt(result[1])*3600000 + parseInt(result[2])*60000;
						//console.log(Work.Time_Minus);
						work_time_calc(Work);
						document.getElementById("stopm_wt_stop_in").value = date_to_string(Work.Time_WorkStop);
						document.getElementById("stopm_wt_hour_in").value = ms_to_string(Work.WorkTime);
						document.getElementById("stopm_wt_sec_in").value = Work.WorkTime_InSec;
					}
					else{
						Work.Time_Minus = null;
						document.getElementById("error_stopm_minus_time_in").classList.remove('hidden');
					}
					stopm_approve_enable(Work);

				});

			}); // stop button click function


			// egyszer mindenképpen lefut, ha betöltődik minden objektum

		});
	</script>
	<body>

	<div class="ui mini modal" id="start_modal">
	  <i class="close icon"></i>
	  <div class="header" style="background:#1B1C1D; color:white; border-bottom-color:#404040;">
	    Munkaidő kezdete
	  </div>
	  <div class="content" style="background:#1B1C1D; color:white">
			<div class="ui inverted form">
				<div class="ui input" >
					<input type="text" name="username" placeholder="YYYY-MM-DD HH:MM" id="start_time_input">
				</div>
			</div>
	  </div>
	  <div class="actions" style="background:#1B1C1D; border-top-color:#404040;">
	    <div class="ui inverted grey button deny">Mégsem</div>
	    <div class="ui animated inverted teal button approve" id="start_modal_btn_start">
				<div class="visible content">OK</div>
				<div class="hidden content">
					<i class="check icon"></i>
				</div>
			</div>
	  </div>
	</div>

	<div class="ui tiny modal" id="stop_modal">
	  <i class="close icon"></i>
	  <div class="header" style="background:#1B1C1D; color:white; border-bottom-color:#404040;">
	    Munkaidő vége
	  </div>
	  <div class="content" style="background:#1B1C1D; color:white">
			<div class="ui inverted form">

				<label>Munkaidő kezdete</label><br>
				<div class="ui input" id="ui_stopm_start_time_in">
					<input type="text" placeholder="YYYY-MM-DD HH:MM" id="stopm_start_time_in">
					<div class="floating ui red label hidden" id="error_stopm_start_time_in">!</div>
				</div>

				<br><label>Munkaidő vége</label><br>
				<div class="ui input" id="ui_stopm_stop_time_in">
					<input type="text" placeholder="YYYY-MM-DD HH:MM" id="stopm_stop_time_in">
					<div class="floating ui red label hidden" id="error_stopm_stop_time_in">!</div>
				</div>

				<br><label>Ebédidő</label><br>
				<div class="ui input" id="ui_stopm_minus_time_in">
					<input type="text" placeholder="HH:MM" id="stopm_minus_time_in">
					<div class="floating ui red label hidden" id="error_stopm_minus_time_in">!</div>
				</div>

				<br><label>MN vége</label><br>
				<div class="ui disabled input" >
					<input type="text" placeholder="YYYY-MM-DD HH:MM" id="stopm_wt_stop_in">
				</div>

				<br><label>Munkaidő</label><br>
				<div class="ui disabled input" >
					<input type="text" placeholder="SS" id="stopm_wt_sec_in">
					<div class="floating ui red label hidden" id="error_stopm_wt_sec_in">!</div>
				</div>

				<br><label>Munkaidő</label><br>
				<div class="ui disabled input" >
					<input type="text" placeholder="HH:MM" id="stopm_wt_hour_in">
				</div>

				<br><label>Diszpó</label><br>
				<div class="ui selection dropdown" id="ui_stopm_diszpo_in">
				  <input type="hidden" name="gender">
				  <i class="dropdown icon"></i>
				  <div class="default text">Diszpó</div>
				  <div class="menu" id="stopm_diszpo_in">
				    <div class="item" data-value="1">Diszpó 1</div>
				    <div class="item" data-value="0">Diszpó 2</div>
				  </div>
				</div>

				<br><label>Modul</label><br>
				<div class="ui dropdown majom">
				  <div class="text"></div>
				  <i class="dropdown icon"></i>
				</div>

				<br><label>Munkaleírás</label><br>
				<div class="ui input" id="ui_stopm_modul_in">
					<input type="text" placeholder="..." id="stopm_modul_in">
				</div>

			</div>
	  </div>
	  <div class="actions" style="background:#1B1C1D; border-top-color:#404040;">
	    <div class="ui inverted grey button deny">Mégsem</div>
	    <div class="ui animated inverted teal button approve" id="stopm_btn_start">
				<div class="visible content">OK</div>
				<div class="hidden content">
					<i class="check icon"></i>
				</div>
			</div>
	  </div>
	</div>

	<div class="ui two column grid" style="background:#1B1C1D;">

		<div class="four wide column">

		<form class="ui form" action="" method="post">
			<div class="ui massive vertical inverted menu">
				<div class="item">

					<button class="ui icon inverted button teal">
	  					<i class="home icon"></i>
					</button>

				</div>
				<div class="item">

					<div class="header">
						<i class="clock icon"></i>
						Munkaidő
					</div>
					<div class="menu">
						<a class="item" id="btn_start">Start</a>
						<a class="item" id="btn_stop">Stop</a>
						<a class="item">Folytatás</a>
					</div>
				</div>
				<div class="item">
					<div class="header">
						<i class="edit icon"></i>
						Új
					</div>
					<div class="menu">
						<a class="item">Projekt</a>
						<a class="item">Termék/modul</a>
						<a class="item">Munkaleírás</a>
						<a class="item">Speciális dátumok</a>
					</div>
				</div>
				<div class="item">
					<div class="header">
						<i class="microchip icon"></i>
						Rendszer
					</div>
					<div class="menu">
						<a class="item">Statisztika</a>
						<a class="item">Munkaidő letöltése</a>
						<a class="item">Munkaidő feltöltése</a>
						<a class="item">Beállítások</a>
					</div>
				</div>
				<div class="item">
					<input type="submit" value="Kijelentkezés" class="ui inverted button teal" name="logout1"/>
						<i class="sign out icon"></i>
					<div class="ui action input">
						<button class="ui inverted icon button teal" name="logout">
							<i class="sign out icon"></i>
							Kilépés
						</button>
					</div>
				</div>


			</div>
		</form>
		</div>

		<div class="twelve wide column">
			<table class="ui celled inverted table">
			  <thead>
			    <tr>
					<th>ID</th>
					<th>Munkaidő kezdete</th>
			    <th>Munkaidő vége</th>
			    <th>Ebédidő</th>
					<th>Munkaidő</th>
					<th>Munkaidő</th>
					<th>Diszpó név</th>
					<th>Diszpó ID</th>
					<th>Modul</th>
					<th>Munkaleírás</th>
					<th>StopDaWork</th>
			  </tr></thead>
			  <tbody id="wt_table">
					<?php
						wt_list_all();
					?>
			  </tbody>
			</table>
		</div>


	</div>
	</body>

</html>

<?php
	// kilistázza az összes munkát az adatbáziból
	function wt_list_all()
	{
		include("config.php");
		$sql = "SELECT * FROM worktime";
		$result = mysqli_query($db, $sql);
		while ($row = mysqli_fetch_assoc($result))
		{
				echo "<tr>";
				foreach ($row as $field => $value)
				{
						echo "<td>" . $value . "</td>";
				}
				echo "</tr>";
		}
		mysqli_close($db);
	}

	#header('Content-Type: text/html; charset=utf-8');
	//include("config.php");
	//session_start();
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout']))
	{
		header("location: logout.php");
	}

	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["start_time"]))
	{
		$start_time = $_POST["start_time"];
/*
		echo "<tr>";
		echo "<td>" . $start_time . "</td>";
		echo "</tr>";
*/
		// Válasz a JS-nek, benne legyen az ID az adatbázisból!
		echo "Majom";
	}


	// minden oldalbetöltésnél lefut
	/*
	$sql = "SELECT * FROM worktime";
	$result = mysqli_query($db, $sql); // First parameter is just return of "mysqli_connect()" function

	echo "<br>";
	echo "<table border='1'>";
	while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
	    echo "<tr>";
	    foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
	        echo "<td>" . $value . "</td>"; // I just did not use "htmlspecialchars()" function.
	    }
	    echo "</tr>";
	}
	echo "</table>";
	*/
?>
