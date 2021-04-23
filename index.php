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
<script type="text/javascript">
    function poprzedni(k) {
        var tydzien = document.getElementById("tydz");
        var tydzien2 = parseInt(document.getElementById("tydz").innerHTML);
        tydzien.innerHTML = (tydzien2 + k);
        var url = new URL(window.location.href);
        var search_params = url.searchParams;
        search_params.set('tydzien', (tydzien2 + k));
        url.search = search_params.toString();
        var new_url = url.toString();
        window.location.href = new_url;
    }
</script>

<script type="text/javascript">
  $( function() {
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
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  } );
  </script>
<script type="text/javascript">
function hide(id){
	var id = id + "";
    var element = document.getElementsByClassName("entry_group_" + id + "");
	for (i = 0; i < element.length; i++) {
		if (element[i].classList) {
		  element[i].classList.toggle("hide");
		} else {
		  // For IE9
		  var classes = element[i].className.split(" ");
		  var i = classes.indexOf("hide");

		  if (i >= 0)
			classes.splice(i, 1);
		  else
			classes.push("hide");
			element[i].className = classes.join(" ");
		}
	}
}

</script>

<style>
.plan {
	margin-bottom: 10px;
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
						<input name="search" id="tags" maxlength="300"/>
						<input type="submit" id="seek" value="WYSZUKAJ"/>
					</form>
					<div>
						<span class="or">LUB</span><span class="or2"><a href="sale.php">przejdź do wyszukiwarki wolnych sal</a></span>
					</div>
				</div>
			</div>
		</div>
		 <?php
			if(isset($_GET['search'])) {
				$input = mysqli_real_escape_string($conn, $_GET['search']); 
				$input = trim($input);
				$input = rtrim($input, ',');
				if(isset($_GET['tydzien'])) $tydzien = mysqli_real_escape_string($conn, $_GET['tydzien']); else $tydzien = 0;
				if(strftime("%u") == 1) $tydzien = $tydzien + 1;
				$poczatek_tyg = date( 'Y-m-d', strtotime('monday '.($tydzien-1).' week'));
				$poniedzialek = $poczatek_tyg;
				$wtorek = date( 'Y-m-d', strtotime($poczatek_tyg.' +1 day'));
				$sroda = date( 'Y-m-d', strtotime($poczatek_tyg.' +2 day'));
				$czwartek = date( 'Y-m-d', strtotime($poczatek_tyg.' +3 day'));
				$piatek = date( 'Y-m-d', strtotime($poczatek_tyg.' +4 day'));
				$dzien = array($poniedzialek, $wtorek, $sroda, $czwartek, $piatek);
			}
		?>
		<div class="content_body">
        <div class="plan">
			<div id="control" style="float: left; display: block; width: 200px;">
				<div class="ctrl_list">
				<?php
				$colors = array("#f3b6b7", "#ffcc5c","#96ceb4","#ffeead","#f3c083","#dbceb0","#cab577","#abffb0","#b2ad7f","#ada397");
				if(isset($input)){
					$inputs = array();
					foreach(explode(",", $input) as $a) if($a != "") array_push($inputs, $a);
					$no = 0;
					$color1 = 0;
					$set = false;
					foreach($inputs as $input1){
						$input1 = trim($input1);
						if(in_array($input1, $lecturers)) {
							$no++;
							$color1++;
							if($color1 == 10) $color1 = 1;
							if(!$set) {
								echo "<div><div class='type'>Prowadzący:</div>";
								$set = true;
								}
							echo "<div class='entr'><span style='width: 5px; background-color: {$colors[($color1-1)]};'>&nbsp;&nbsp;&nbsp;&nbsp;</span><input class='check' type='checkbox' id='entry_{$no}' onclick='hide({$no})' checked>{$input1}</div>";
							}
					}
					if($set) echo "</div>";
					$set = false;
					foreach($inputs as $input1){
						$input1 = trim($input1);
						if(in_array($input1, $groups)) {
							$no++;
							$color1++;
							if($color1 == 10) $color1 = 1;
							if(!$set) {
								echo "<div style='margin-top: 20px;'><div class='type'>Grupy:</div>";
								$set = true;
								}
							echo "<div class='entr'><span style='width: 5px; background-color: {$colors[($color1-1)]};'>&nbsp;&nbsp;&nbsp;&nbsp;</span><input class='check' type='checkbox' id='entry_{$no}' onclick='hide({$no})' checked>{$input1}</div>";
							}
					}
					if($set) echo "</div>";
					$set = false;
					foreach($inputs as $input1){
						$input1 = trim($input1);
						if(in_array($input1, $modules)) {
							$no++;
							$color1++;
							if($color1 == 10) $color1 = 1;
							if(!$set) {
								echo "<div style='margin-top: 20px;'><div class='type'>Przedmioty:</div>";
								$set = true;
								}
							echo "<div class='entr'><span style='width: 5px; background-color: {$colors[($color1-1)]};'>&nbsp;&nbsp;&nbsp;&nbsp;</span><input class='check' type='checkbox' id='entry_{$no}' onclick='hide({$no})' checked>{$input1}</div>";
							}
					}
					if($set) echo "</div>";
					$set = false;
					foreach($inputs as $input1){
						$input1 = trim($input1);
						if(in_array($input1, $rooms)) {
							$no++;
							$color1++;
							if($color1 == 10) $color1 = 1;
							if(!$set) {
								echo "<div style='margin-top: 20px;'><div class='type'>Sale:</div>";
								$set = true;
								}
							echo "<div class='entr'><span style='width: 5px; background-color: {$colors[($color1-1)]};'>&nbsp;&nbsp;&nbsp;&nbsp;</span><input class='check' type='checkbox' id='entry_{$no}' onclick='hide({$no})' checked>{$input1}</div>";
							}
					}
					if($set) echo "</div>";
					if($no == 0) echo "<div class='type'>Brak wyników</div>";
				}
				?>
				</div>
			</div>
			<?php
				if(isset($input)) if($no > 0) {
			echo   "<div id='plan'>
						<div class='data'>
							<div class='prev' onclick='poprzedni(-1)'>
								<i class='fas fa-arrow-circle-left'></i>
							</div>
							<div class='prev'>{$dzien[0]} - {$dzien[4]}</div>
							<div class='prev' onclick='poprzedni(1)'>
								<i class='fas fa-arrow-circle-right'></i>
							</div>
						</div>
						<div id='plan_content'>
							<div id='days'>
								<div>Poniedziałek<br>{$dzien[0]}</div>
								<div>Wtorek<br>{$dzien[1]}</div>
								<div>Środa<br>{$dzien[2]}</div>
								<div>Czwartek<br>{$dzien[3]}</div>
								<div>Piątek<br>{$dzien[4]}</div>
							</div>
							<div id='hours'>";
							for($h=8;$h<=21;$h++){
								if($h == 8 || $h == 9) echo "<div>0{$h}:00</div>"; else echo "<div>{$h}:00</div>";
							}
							echo "</div>";
								for($day=0;$day<5;$day++){
									$zajecia = array();
									foreach($inputs as $input1){
										$input1 = trim($input1);
										$sql = "
										SELECT * 
										FROM `zajecia` 
										WHERE `date`='{$dzien[$day]}' 
										AND (`lecturer` LIKE '%{$input1}%' OR `groups` LIKE '%{$input1}%' OR `name` LIKE '%{$input1}%' OR `room` LIKE '%{$input1}%')
										ORDER BY `added` DESC;"; 
										$result = mysqli_query($conn, $sql);
										$zajecia1 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
										while($row = mysqli_fetch_assoc($result)) {
											$zajecia1[$row['start_hour']-1] = $row;
										}
									array_push($zajecia, $zajecia1);
									}		
									
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
										$color++;
										if($color == 10) $color = 1;
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
												echo 	"</table>";
												
												echo	"</div>
														<div style='background-color: {$colors[($color-1)]};' class='module wrap'>
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
				}
				
			?>
		</div>
		<?php 
		if(isset($input)){
		foreach($inputs as $input1){
			$input1 = trim($input1);
			echo "<div class='full'><a href='miesiac.php?search={$input1}'>Rozkład na cały miesiąc dla {$input1}</a></div>";
		}
		}
		?>
		</div>
		<div id='tankGIF'><img src="lightTank.gif" alt="eloooo" width="510" height="510"></div>
        <div id="footer">
            <div>
				2021 <span onclick="moveTank()">&copy;</span> Akademia Wojsk Lądowych<div style="font-size: 10px;">Realizacja Kulas Filip</div>
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
