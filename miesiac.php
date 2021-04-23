<?php 
session_start();

require_once("db.php");

$userArray = array(); //tablica nazw
$lecturers = array();
$groups = array();
$modules = array();
$rooms = array();

$conn = mysqli_connect($servername, $username, $password, $dbname, 3306);

$sql = "
SELECT DISTINCT `name`
FROM `prowadzacy`
ORDER BY `name` ASC";

$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {
    array_push($userArray, $row['name']);
	array_push($lecturers, $row['name']);
}

#$sql = "
#SELECT DISTINCT `name`
#FROM `przedmioty`
#ORDER BY `name` ASC";
#
#$result = mysqli_query($conn, $sql);
#                
#while($row = mysqli_fetch_assoc($result)) {
#    array_push($userArray, $row['name']);
#	array_push($modules, $row['name']);
#}

$sql = "
SELECT DISTINCT `name`
FROM `grupy`
ORDER BY `name` ASC";

$result = mysqli_query($conn, $sql);
                
while($row = mysqli_fetch_assoc($result)) {
    array_push($userArray, $row['name']);
	array_push($groups, $row['name']);
}

$sql = "
SELECT DISTINCT `name`
FROM `sale`
ORDER BY `name` ASC";

$result = mysqli_query($conn, $sql);
                
while($row = mysqli_fetch_assoc($result)) {
    array_push($userArray, $row['name']);
	array_push($rooms, $row['name']);
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

if(!isset($_GET['miesiac'])) $mies = (int)date("m"); else $mies = $_GET['miesiac'];
if($mies < 1 || $mies > 12) $mies = (int)date("m");
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Plan zajęć</title>
    <link rel="Stylesheet" href="style.css">
	<link rel="Stylesheet" href="plan_style.css">
    <script src="https://kit.fontawesome.com/8ba7f4d4aa.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
</head>

<style>
.plan {
	overflow: auto;
	border: 1px solid #ddd;
	margin-bottom: 10px;
}
#plan_content {
	width: auto;
}
#days {
	display: flex;
}
.content_body {
	display: table;
	margin: 0 auto;
}
.full {
	margin-bottom: 3px;
}
.full a {
	font-weight: bold;
	font-size: 14px;
	color: #0e4c27;
}
</style>
<script type="text/javascript">
  $(function() {
    var availableTags = <?php echo json_encode($userArray); ?>;
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#tags" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          var results = $.ui.autocomplete.filter( availableTags, extractLast( request.term ) );
		  response(results.slice(0, 7));
        }
		});
  });
  </script>
<body>
    <div id="container" style="<?php if(isset($_GET['search'])) echo "background-color: whitesmoke;"; ?>">
		<div id="top" style="<?php if(!isset($_GET['search'])) echo "padding-top: 15%;"; ?>">
			<div class="content">
				<span style="display: none;" id="tydz"><?php if(isset($_GET['tydzien'])) echo $_GET['tydzien']; else echo "0"; ?></span>
				<div id="banner">
					<a href="index.php"><img src="logo.png" alt="Logo AWL" /></a>
					<div style="color: white;font-size: 22px;padding-top: 30px;font-weight: bold;">System wyszukiwania prowadzących Akademii Wojsk Lądowych</div>
				</div>
				<div style="clear: both;" id="form">
					<form style="float: left;" method="GET">
						<input name="search" id="tags" maxlength="300" value=<?php if(isset($_GET['search'])) echo trim($_GET['search']); ?> />
						<select name="miesiac" id="tags2">
							<option <?php if($mies == 1) echo "selected";?> value="1">Styczeń</option>
							<option <?php if($mies == 2) echo "selected";?> value="2">Luty</option>
							<option <?php if($mies == 3) echo "selected";?> value="3">Marzec</option>
							<option <?php if($mies == 4) echo "selected";?> value="4">Kwiecień</option>
							<option <?php if($mies == 5) echo "selected";?> value="5">Maj</option>
							<option <?php if($mies == 6) echo "selected";?> value="6">Czerwiec</option>
							<option <?php if($mies == 7) echo "selected";?> value="7">Lipiec</option>
							<option <?php if($mies == 8) echo "selected";?> value="8">Sierpień</option>
							<option <?php if($mies == 9) echo "selected";?> value="9">Wrzesień</option>
							<option <?php if($mies == 10) echo "selected";?> value="10">Październik</option>
							<option <?php if($mies == 11) echo "selected";?> value="11">Listopad</option>
							<option <?php if($mies == 12) echo "selected";?> value="12">Grudzień</option>
						</select>
						<input type="submit" id="seek" value="WYSZUKAJ"/>
					</form>
				</div>
			</div>
		</div>
		<div class="content_body">
        <div class="plan">
			<?php
			if(isset($_GET['search'])) {
				$input = mysqli_real_escape_string($conn, $_GET['search']); 
				$input = trim($input);
				$dateObj   = DateTime::createFromFormat('!m', $mies);
				$monthName = $dateObj->format('F');
				$poczatek_mies = date( 'Y-m-d', strtotime('first day of '.$monthName.' this year'));
			}
				if(isset($input))
					echo   "<div id='plan'>
						<div id='plan_content'>
							<div id='days'>";
								$rok = date("Y");
								$d = 30;
								if($mies == 2) {
									$d = 28;
									if($rok % 4 == 0 && $rok % 100 != 0) $d = 29;
									if($rok % 400 == 0) $d = 29;
									}
								if($mies % 2) $d = 31;
								for($day=0;$day<$d;$day++){ 
									$date1 = date( 'Y-m-d', strtotime($poczatek_mies.' +'.$day.' day'));
									$day_name = date('l', strtotime($date1));
									if($day_name == "Monday") echo "<div>Poniedziałek<br>{$date1}</div>";
									if($day_name == "Tuesday") echo "<div>Wtorek<br>{$date1}</div>";
									if($day_name == "Wednesday") echo "<div>Środa<br>{$date1}</div>";
									if($day_name == "Thursday") echo "<div>Czwartek<br>{$date1}</div>";
									if($day_name == "Friday") echo "<div>Piątek<br>{$date1}</div>";
									if($day_name == "Saturday") echo "<div>Sobota<br>{$date1}</div>";
									if($day_name == "Sunday") echo "<div>Niedziela<br>{$date1}</div>";
								}
							echo "</div>
							<div id='hours'>";
							for($h=8;$h<=21;$h++){
								if($h == 8 || $h == 9) echo "<div>0{$h}:00</div>"; else echo "<div>{$h}:00</div>";
							}
							echo "</div>";
								for($day=0;$day<$d;$day++){
									$date1 = date( 'Y-m-d', strtotime($poczatek_mies.' +'.$day.' day'));
									$zajecia = array();
									$sql = "
									SELECT * 
									FROM `zajecia` 
									WHERE `date`='{$date1}' 
									AND (`lecturer` LIKE '%{$input}%' OR `groups` LIKE '%{$input}%' OR `name` LIKE '%{$input}%' OR `room` LIKE '%{$input}%')
									ORDER BY `added` DESC;"; 
									$result = mysqli_query($conn, $sql);
									$zajecia1 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
									while($row = mysqli_fetch_assoc($result)) {
										$zajecia1[$row['start_hour']-1] = $row;
									}
									array_push($zajecia, $zajecia1);	
									
							echo   "<div class='day'>";
									for($cell=1;$cell<15;$cell++){
										echo "<span class='sep'></span>";
									}
									$in = 0;
									$color = 0;
									foreach ($zajecia as $zajecia2){
										
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
										
										$in++;
								echo   "<div class='entry_group_{$in}'>";
											for($zaj=0; $zaj < 15; $zaj++){
												$entry = $zajecia2[$zaj];
												if($entry == 0) {
													continue;
												}
												$top = 27*($entry['start_hour']-1);
												for($i=0; $i < $entry['start_hour']; $i++){
													if($i == 4) $top += 12;
													if($i == 2 || $i == 6) $top += 6;
													if($i == 8 || $i == 10 || $i == 12 || $i == 14) $top += 3;
												}
												//3px == 5min
												$height = 27*$entry['lenght'];
												
												$hour = $g_s[$entry['start_hour']-1];
												$start_hour1 = date_format($hour, "H:i");
												$hour->add(new DateInterval('PT' . (45*$entry['lenght']) . 'M'));
												
												if($entry['lenght'] > 2) {
													for($j1=$entry['start_hour']; $j1<$entry['start_hour']+$entry['lenght']-1; $j1++) {
														if($j1 == 4) {$hour->add(new DateInterval('PT20M')); $height += 12;}
														if($j1 == 2 || $j1 == 6) {$hour->add(new DateInterval('PT10M')); $height += 6;}
														if($j1 == 8 || $j1 == 10 || $j1 == 12 || $j1 == 14) {$hour->add(new DateInterval('PT5M')); $height += 3;}
													}
												}
												$end_hour1 = date_format($hour, "H:i");
												
												echo "
													<div class='entry' style='top: {$top}px; height: {$height}px'>
														<div id='title'>
														<table>
															<tr>
																<td class='table_pro'>{$entry['name']} {$entry['info']}</td>
															</tr>
															<tr>
																<td class='table_pro'>Prowadzący</td><td class='table_sal'>Sala</td>
															</tr>";
															$lec = explode(",", $entry['room']);
															foreach ($lec as $item){
																$lec1 = explode("=", $item);
																echo "
																<tr>
																<td class='table_pro'><a href='index.php?search={$lec1[0]}'>{$lec1[0]}</a></td><td class='table_sal'><a href='index.php?search={$lec1[1]}'>{$lec1[1]}</a></td>
																</tr>";
															}
															$gro = explode(",", $entry['groups']);
															foreach ($gro as $gr) {
																echo "<tr><td colspan='2' class='table_gr'>".str_replace("|", "", $gr)."</td></tr>";
															}
												echo 	"</table>
														</div>
														<div class='module wrap'>
															<div class='time'>{$start_hour1}-{$end_hour1}</div>
															<div class='module_name'><a href='#'>{$entry['name']} {$entry['info']}</a></div>";
															if(!str_contains($entry['room'], ",")) {
																$l = explode("=", $entry['room']);
																echo "<div class='room1'><a href='index.php?search={$l[1]}'>{$l[1]}</a></div>";
																}
															if(!str_contains($entry['lecturer'], ",")) {
																$p = explode("=", $entry['lecturer']);
																echo "<div class='lecturer'><a href='index.php?search={$p[0]}'>{$p[0]}</a></div>";
																}
												echo "	</div>
													</div>
												";
											}
									echo "</div>";
									}
								echo "</div>";
								}
							$conn->close();
				echo "  </div>
					</div>";
			?>
		</div>
		<?php echo "<div class='full'><a target='_blank' href='ical.php?search={$_GET['search']}&miesiac={$mies}'>Pobierz plik iCalendar tego widoku</a></div>"; ?>
		</div>
        <div id="footer">
            <div>
				2021 Akademia Wojsk Lądowych<div style="font-size: 10px;">Realizacja Kulas Filip</div>
			</div>
			<div class="updated">Ostatnia aktualizacja bazy danych: <?php echo $last_date[2]."-".$last_date[1]."-".$last_date[0]; ?></div>
        </div>

	</div>
</body>
</html>
