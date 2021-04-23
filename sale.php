<?php session_start(); ?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Wolne Sale</title>
    <link rel="Stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/8ba7f4d4aa.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
</head>

<?php
    require_once("db.php");

    // Create connection
    $conn = mysqli_connect($servername, $username, $password,$dbname);
    // Check connection
    if ($conn->connect_error) {
      //die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT `name`
        FROM `sale` ORDER BY `name` ASC";
    $result = mysqli_query($conn, $sql);
    $classes = [];
    
    while($row = mysqli_fetch_assoc($result)) {
        array_push($classes, $row['name']);
    }
	
	$sql = "
	SELECT `added` 
	FROM `zajecia` 
	ORDER BY `added` DESC 
	LIMIT 1";

	$result = mysqli_query($conn, $sql);
	$last_date = "";
	while($row = mysqli_fetch_assoc($result)) {
		$last_updated = explode(" ", $row['added'])[0];
		$last_date = explode("-", $last_updated);
	}
?>

<body>
    <div id='container' style="<?php if(isset($_GET['date']) && isset($_GET['fromHour']) && isset($_GET['tillHour'])) echo "background-color: whitesmoke;"; ?>">
        <div id="top" style="<?php if(!isset($_GET['date']) && !isset($_GET['fromHour']) && !isset($_GET['tillHour'])) echo "padding-top: 15%;"; ?>">
		<div class="content" style="height: 256px;">
			<span style="display: none;" id="tydz"><?php if(isset($_GET['tydzien'])) echo $_GET['tydzien']; else echo "0"; ?></span>
			<div id="banner">
				<a href="index.php"><img style="float: left; display: table;" src="logo.png" alt="Logo AWL" /></a>
				<div style="color: white;font-size: 22px;padding-top: 30px;font-weight: bold;">System wyszukiwania wolnych sal Akademii Wojsk Lądowych</div>
			</div>
			<div style="clear: both;" id="form">
				<form method="GET" style="display: table;">
					<div class="input">
					<label for="date">Data</label>
					<input type="date" id="date" name="date" value=<?php if(isset($_GET['date'])) echo $_GET['date']; else echo date("Y-m-d"); ?>>
					</div>
					<div class="input">
					<label for="fromHour">Godzina rozpoczęcia</label>
					<input type="time" id="from" name="fromHour" value=<?php if(isset($_GET['fromHour'])) echo $_GET['fromHour']; else echo date("H").":00"; ?> required>
					</div>
					<div class="input">
					<label for="tillHour">Godzina zakończenia</label>
					<input type="time" id="till" name="tillHour" value=<?php if(isset($_GET['tillHour'])) echo $_GET['tillHour']; else echo date("H").":45"; ?> required>
					</div>
					<input type="submit" id="seek1" value="WYSZUKAJ"/>
				</form>
			</div>
			<?php 
			if(isset($_GET['date']) && isset($_GET['fromHour']) && isset($_GET['tillHour'])) {
				$date = mysqli_real_escape_string($conn, $_GET['date']);
				$fromHour = new DateTime(mysqli_real_escape_string($conn, $_GET['fromHour']));
				$tillHour = new DateTime(mysqli_real_escape_string($conn, $_GET['tillHour']));
				if($_GET['fromHour'] != null && $_GET['tillHour'] != null){
					if($fromHour >= $tillHour ) echo "<div id='error'>Godzina rozpoczęcia musi być mniejsza od godziny zakończenia.</div>";
				} else echo "<div id='error'>Wybrano błędnyh zakres godzin.</div>";
			}
			?>
		</div>
		</div>
        
		<div class="rooms">
			
			<?php 
			if(isset($_GET['date']) && isset($_GET['fromHour']) && isset($_GET['tillHour'])) {
				if($fromHour < $tillHour && $_GET['fromHour'] != null && $_GET['tillHour'] != null){
					printf("<div class='day1'>Rozkład wolnych sal na dzień<span>%s<span style='font-weight: normal; font-size: 15px; margin: 0 10px 0 0;'>,</span></span>rozpoczynając od<span>%s</span>do<span>%s</span></div>", $date, date_format($fromHour, "H:i"), date_format($tillHour, "H:i"));
					$hour_start = 0;
					$hour_end = 15;
					$g_s = array(
					new DateTime('8:00'),
					new DateTime('8:45'),
					new DateTime('9:40'),
					new DateTime('10:25'),
					new DateTime('11:30'),
					new DateTime('12:15'),
					new DateTime('13:10'),
					new DateTime('13:55'),
					new DateTime('14:45'),
					new DateTime('15:30'),
					new DateTime('16:20'),
					new DateTime('17:05'),
					new DateTime('17:55'),
					new DateTime('18:40'),
					new DateTime('19:30')
					);
					
					for($i=0; $i<15; $i++) if($fromHour >= $g_s[$i]) $hour_start = $i+1;
					
					$g_e = array(
					new DateTime('8:45'),
					new DateTime('9:30'),
					new DateTime('10:25'),
					new DateTime('11:10'),
					new DateTime('12:15'),
					new DateTime('13:00'),
					new DateTime('13:55'),
					new DateTime('14:40'),
					new DateTime('15:30'),
					new DateTime('16:15'),
					new DateTime('17:05'),
					new DateTime('17:50'),
					new DateTime('18:40'),
					new DateTime('19:25'),
					new DateTime('20:15'),
					);
					
					for($i=14; $i>=0; $i--) if($tillHour <= $g_e[$i]) $hour_end = $i+1;
					
					echo "<div class='rooms1'>";
					
					$sql = "SELECT `room`, `start_hour`, `lenght` FROM `zajecia` WHERE `date`='{$date}';";
					$result = mysqli_query($conn, $sql);
					$rooms = array();
					while($row = mysqli_fetch_assoc($result)) {
						for($h = $row['start_hour']; $h < ($row['start_hour']+$row['lenght']); $h++){
							if($h >= $hour_start && $h <= $hour_end){
								$arr = explode(",", $row['room']);
								foreach ($arr as $item){
									$room = explode("=", $item);
									if(!in_array($room[1], $rooms)) array_push($rooms, $room[1]);
								}
								break;
							}
						}
						
					}
					$count = 0;
					foreach ($classes as $item){
						if(!in_array($item, $rooms)) {
							echo "<span class='room'>";
							$count++;
							echo "<a href='index.php?search={$item}'>".$item."</a>";
							echo "</span>";
							}
					}
					echo "</div>";
					if($count == 0) echo "<div class='day1' style='border: 0; display: table; padding-top: 20px; clear: both;'>Brak wolnych sal w zadanym przedziale czasu.</div>";
					else if($count <= 4) echo "<div class='day1' style='border: 0; display: table; padding-top: 20px; clear: both;'>Znaleziono<span style='margin-right: 10px;'>{$count}</span>wolne sale w zadanym przedziale czasu.</div>";
					else echo "<div class='day1' style='border: 0; display: table; padding-top: 20px; clear: both;'>Znaleziono<span style='margin-right: 10px;'>{$count}</span>wolnych sal w zadanym przedziale czasu.</div>";
				} 
			}
			?>
		</div>
		<div id="footer">
            <div>
				2021 Akademia Wojsk Lądowych<div style="font-size: 10px;">Realizacja Kulas Filip</div>
			</div>
			<div class="updated">Ostatnia aktualizacja bazy danych: <?php echo $last_date[2]."-".$last_date[1]."-".$last_date[0]; ?></div>
        </div>
	</div>
</body>
<script type="text/javascript">
function moveTank(){
    gif = document.getElementById('tankGIF');
    gif.style.display = 'block';
    
    setTimeout(function(){ gif.style.display = 'none'; }, 5700);
}
</script>
</html>
