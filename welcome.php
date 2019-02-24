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

		$(document).ready(function(){

			// Start button click
			$("#btn_start").click(function()
			{
				$('#start_modal').modal({
					centered: false,

					// OK gomb megnyomásásra
					onApprove: function()
					{
            console.log('Start/OK button');
        	},
					// Mégse gomb megnyomásásra
					onDeny: function()
					{
						console.log('Start/Mégse button');
					}
				}).modal('show');
    		//$('#wt_table tr:last').after('<tr><td data-label="Name">Bob</td></tr>');
				var datetime_now = "";
				var d = new Date();
				datetime_now = date_to_string(d);
				document.getElementById("start_time_input").value = datetime_now;
  		});

			$("#btn_stop").click(function()
			{
				$('#stop_modal').modal({
					centered: false,

					// OK gomb megnyomásásra
					onApprove: function()
					{
            console.log('Start/OK button');
        	},
					// Mégse gomb megnyomásásra
					onDeny: function()
					{
						console.log('Start/Mégse button');
					}
				}).modal('show');

				var datetime_now = "";
				var d = new Date();
				datetime_now = date_to_string(d);
				//document.getElementById("start_time_input").value = datetime_now;
			});
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
	    <div class="ui animated inverted teal button approve">
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
				<div class="ui input" >
					<input type="text" placeholder="YYYY-MM-DD HH:MM" id="stopm_start_time_in">
				</div>

				<br><label>Munkaidő vége</label><br>
				<div class="ui input" >
					<input type="text" placeholder="YYYY-MM-DD HH:MM" id="stopm_stop_time_in">
				</div>

				<br><label>Ebédidő</label><br>
				<div class="ui input" >
					<input type="text" placeholder="HH:MM" id="stopm_minus_time_in">
				</div>

				<br><label>MN vége</label><br>
				<div class="ui input" >
					<input type="text" placeholder="YYYY-MM-DD HH:MM" id="stopm_wt_stop_in">
				</div>

				<br><label>Munkaidő</label><br>
				<div class="ui input" >
					<input type="text" placeholder="SS" id="stopm_wt_sec_in">
				</div>

				<br><label>Munkaidő</label><br>
				<div class="ui input" >
					<input type="text" placeholder="HH:MM" id="stopm_wt_hour_in">
				</div>

				<br><label>Diszpó</label><br>
				<div class="ui input" >
					<input type="text" placeholder="..." id="stopm_diszpo_in">
				</div>

				<br><label>Modul</label><br>
				<div class="ui input" >
					<input type="text" placeholder="..." id="stopm_modul_in">
				</div>

				<br><label>Munkaleírás</label><br>
				<div class="ui input" >
					<input type="text" placeholder="..." id="stopm_modul_in">
				</div>

			</div>
	  </div>
	  <div class="actions" style="background:#1B1C1D; border-top-color:#404040;">
	    <div class="ui inverted grey button deny">Mégsem</div>
	    <div class="ui animated inverted teal button approve">
				<div class="visible content">OK</div>
				<div class="hidden content">
					<i class="check icon"></i>
				</div>
			</div>
	  </div>
	</div>

	<div class="ui two column grid" style="background:#1B1C1D;">
	<div class="stretched row">
	<div class="column">

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

		<div class="column">
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
	</div>
	</body>

</html>

<?php
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
