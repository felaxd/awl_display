<?php
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

class ICS {
    var $data = "";
    var $name;
    var $start = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VTIMEZONE\nTZID:Europe/Warsaw\nX-LIC-LOCATION:Europe/Warsaw\nBEGIN:DAYLIGHT\nTZOFFSETFROM:-0100\nTZOFFSETTO:+0200\nTZNAME:CEST\nDTSTART:19700329T020000\nRRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\nEND:DAYLIGHT\nBEGIN:STANDARD\nTZOFFSETFROM:-0000\nTZOFFSETTO:+0100\nTZNAME:CET\nDTSTART:19701025T030000\nRRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\nEND:STANDARD\nEND:VTIMEZONE\n";
    var $end = "END:VCALENDAR\n";
    function ICS($name) {
        $this->name = $name;
    }
    function add($start,$end,$name,$description,$location) {
		
        $this->data .= "BEGIN:VEVENT\nDTSTART;TZID=Europe/Warsaw:".date("Ymd\THis",strtotime($start))."\nDTEND;TZID=Europe/Warsaw:".date("Ymd\THis",strtotime($end))."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:".strtotime($start)."\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT15M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\n";
    }
    function show($filename) {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="'.$filename.'.ics"');
        Header('Content-Length: '.strlen($this->getData()));
        Header('Connection: close');
        echo $this->getData();
    }
    function getData() {
        return $this->start . $this->data . $this->end;
    }
}

if(isset($_GET['search']) && isset($_GET['miesiac']) && !isset($_GET['tydzien']) ) {
	$event = new ICS("kalendarz");
	$input = mysqli_real_escape_string($conn, $_GET['search']); 
	$input = trim($input);
	if(isset($_GET['miesiac'])) $miesiac = mysqli_real_escape_string($conn, $_GET['miesiac']); else $miesiac = 0;
	$dateObj   = DateTime::createFromFormat('!m', $miesiac);
	$monthName = $dateObj->format('F');
	$poczatek_mies = date( 'Y-m-d', strtotime('first day of '.$monthName.' this year'));
					
	$rok = date("Y");
	$d = 30;
	if($miesiac % 2) $d = 31;
	if($miesiac == 2) {
		$d = 28;
		if($rok % 4 == 0 && $rok % 100 != 0) $d = 29;
		if($rok % 400 == 0) $d = 29;
		}

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

			for($zaj=0; $zaj < 15; $zaj++){
				$entry = $zajecia2[$zaj];
				if($entry == 0) {
					continue;
				}
				
				$hour = $g_s[$entry['start_hour']-1];
				$start_hour1 = date_format($hour, "H:i");
				$hour->add(new DateInterval('PT' . (45*$entry['lenght']) . 'M'));
				
				if($entry['lenght'] > 2) {
					for($j1=$entry['start_hour']; $j1<$entry['start_hour']+$entry['lenght']-1; $j1++) {
						if($j1 == 4) {$hour->add(new DateInterval('PT20M')); }
						if($j1 == 2 || $j1 == 6) {$hour->add(new DateInterval('PT10M')); }
						if($j1 == 8 || $j1 == 10 || $j1 == 12 || $j1 == 14) {$hour->add(new DateInterval('PT5M'));}
					}
				}
				$loc = "Wiele lokalizacji";
				foreach (explode(",", $entry['room']) as $help_loc) {
					if(str_contains($help_loc, $input)) $loc = explode("=", $help_loc)[1];
				}
				$end_hour1 = date_format($hour, "H:i");
				$event->add("".$date1." ".$start_hour1."","".$date1." ".$end_hour1."", $entry['name']." ".$entry['info'], "Prowadzący/Sala:\\n".str_replace(",","\\n", str_replace("="," ",$entry['room']))."\\n\\nGrupy:\\n".str_replace(",","\\n",$entry['groups']), $loc);
			}
		}
	}
	$mo = array(
		"STYCZEŃ",
		"LUTY",
		"MARZEC",
		"KWIECIEŃ",
		"MAJ",
		"CZERWIEC",
		"LIPIEC",
		"SIERPIEŃ",
		"WRZESIEŃ",
		"PAŹDZIERNIK",
		"LISTOPAD",
		"GRUDZIEŃ"
	);
	$filename = "".$input."_".$mo[($miesiac-1)]."_".$rok."";
	$event->show($filename);
	$conn->close();
}
?>
