<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
    <title>Progress Bar</title>
</head>
<body>
<h3>Semestr I</h3>
<div id="progress_s1" style="width:500px;border:1px solid #ccc;"></div>
<div id="information_s1"></div>
<h3>Semestr II</h3>
<div id="progress_s2" style="width:500px;border:1px solid #ccc;"></div>
<div id="information_s2"></div>
<?php
set_time_limit(0);
class Prowadzacy{
	public $imie = "";
	public $nazwisko = "";
	public $stopien_woj = "";
	public $stopien_nauk = "";
	public $komorka = "";
	public $stanowisko = "";
	public $pensum_ustalone_ds = "";
	public $pensum_ustalone = "";
	public $liczba_ponad_wymiar = "";
	public $wna = "";
	public $SCS;
	public $SCN;
	public $SW_JSM;
	public $SO;
	public $K;
	public $SPd;
	public $Z1;
	public $Z2;
	public $LOG1;
	public $LOG2;
	public $INF1;
	public $INF2;
	public $SW_JSM_D;
	public $SW_JSM_LOG;
	public $SW_JSM_IB;
	public $SW_JSM_ZA;
	public $D1;
	public $ZN1;
	public $BN1;
	public $BN2;
	public $BNN1;
	public $BNN2;
	public $IB1;
	public $IB2;
	public $MED;
	public function __construct($row) { 
		$this->imie = $row['first_name'];
		$this->nazwisko = $row['name'];
		$this->stopien_woj = $row['st_woj'];
		$this->stopien_nauk = $row['st_nauk'];
		$this->komorka = $row['komorka'];
		$this->stanowisko = $row['stanowisko'];
		$this->pensum_ustalone_ds = $row['pensum_st'];
		$this->pensum_ustalone = $row['pensum_ustalone'];
		$this->liczba_ponad_wymiar = $row['ponadwymiarowe'];
		$this->SCS = 0;
		$this->SCN = 0;
		$this->SW_JSM = 0;
		$this->SO = 0;
		$this->K = 0;
		$this->SPd = 0;
		$this->Z1 = 0;
		$this->Z2 = 0;
		$this->LOG1 = 0;
		$this->LOG2 = 0;
		$this->INF1 = 0;
		$this->INF2 = 0;
		$this->SW_JSM_D = 0;
		$this->SW_JSM_LOG = 0;
		$this->SW_JSM_IB = 0;
		$this->SW_JSM_ZA = 0;
		$this->D1 = 0;
		$this->ZN1 = 0;
		$this->BN1 = 0;
		$this->BN2 = 0;
		$this->BNN1 = 0;
		$this->BNN2 = 0;
		$this->IB1 = 0;
		$this->IB2 = 0;
		$this->MED = 0;
	}

	public function addZaj(Zajecie $zaj) {
		$gr = $zaj->groups;
		if( str_contains($gr, "SCP-ZA-I-") ) { $this->Z1 += $zaj->lenght; $gr = str_replace("SCP-ZA-I-", "", $gr); }
		if( str_contains($gr, "SCO-ZA-II-") ) { $this->Z2 += $zaj->lenght; $gr = str_replace("SCO-ZA-II-", "", $gr); }
		if( str_contains($gr, "SCP-ZA-II-") ) { $this->Z2 += $zaj->lenght; $gr = str_replace("SCP-ZA-II-", "", $gr); }
		if( str_contains($gr, "SCP-LOG-I-") ) { $this->LOG1 += $zaj->lenght; $gr = str_replace("SCP-LOG-I-", "", $gr); }
		if( str_contains($gr, "SCO-LOG-II-") ) { $this->LOG2 += $zaj->lenght; $gr = str_replace("SCP-LOG-II-", "", $gr); }
		if( str_contains($gr, "SCP-INF-I-") ) { $this->INF1 += $zaj->lenght; $gr = str_replace("SCP-INF-I-", "", $gr); }
		if( str_contains($gr, "SCO-INF-II-") ) { $this->INF2 += $zaj->lenght; $gr = str_replace("SCP-INF-II-", "", $gr); }
		if( str_contains($gr, "SCO-BN-I-") ) { $this->BN1 += $zaj->lenght; $gr = str_replace("SCO-BN-I-", "", $gr); }
		if( str_contains($gr, "SCO-BN-II-") ) { $this->BN2 += $zaj->lenght; str_replace("SCO-BN-II-", "", $gr); }
		if( str_contains($gr, "SCP-IB") ) { $this->IB1 += $zaj->lenght; $gr = str_replace("SCP-IB", "", $gr); }
		#if( str_contains($gr, "SCP-IB-II-") ) { $this->IB2 += $zaj->lenght; $gr = str_replace("SCP-IB-II-", "", $gr); }
		if( str_contains($gr, "SWP-D-JSM-") ) { $this->SW_JSM_D += $zaj->lenght; $gr = str_replace("SWP-D-JSM-", "", $gr); }
		if( str_contains($gr, "SWP-LOG-") || str_contains($gr, "SWP-L-") ) { $this->SW_JSM_LOG += $zaj->lenght; $gr = str_replace("SWP-LOG-", "", $gr); $gr = str_replace("SWP-L-", "", $gr); }
		if( str_contains($gr, "SWP-IB-") ) { $this->SW_JSM_IB += $zaj->lenght; $gr = str_replace("SWP-IB-", "", $gr); }
		if( str_contains($gr, "SWP-ZA-") ) { $this->SW_JSM_ZA += $zaj->lenght; $gr = str_replace("SWP-ZA-", "", $gr); }
		if( str_contains($gr, "SWP-D-") ) { $this->D1 += $zaj->lenght; $gr = str_replace("SWP-D-", "", $gr); }
		if ( str_starts_with($gr, 'SO') ) { $this->SO += $zaj->lenght; $gr = str_replace("SO", "", $gr); }
		if ( str_starts_with($gr, 'JA') ) { $this->K += $zaj->lenght; $gr = str_replace("JA", "", $gr); }
		if ( str_starts_with($gr, 'KPKR') ) { $this->K += $zaj->lenght; $gr = str_replace("KPKR", "", $gr); }
		if ( str_starts_with($gr, '82500') ) { $this->K += $zaj->lenght; $gr = str_replace("82500", "", $gr); }
	}

	public function zero() {
		$this->SCS = 0;
		$this->SCN = 0;
		$this->SW_JSM = 0;
		$this->SO = 0;
		$this->K = 0;
		$this->SPd = 0;
		$this->Z1 = 0;
		$this->Z2 = 0;
		$this->LOG1 = 0;
		$this->LOG2 = 0;
		$this->INF1 = 0;
		$this->INF2 = 0;
		$this->SW_JSM_D = 0;
		$this->SW_JSM_LOG = 0;
		$this->SW_JSM_IB = 0;
		$this->SW_JSM_ZA = 0;
		$this->D1 = 0;
		$this->ZN1 = 0;
		$this->BN1 = 0;
		$this->BN2 = 0;
		$this->BNN1 = 0;
		$this->BNN2 = 0;
		$this->IB1 = 0;
		$this->IB2 = 0;
		$this->MED = 0;
	}
}

class Zajecie {
	public $date;
	public $name;
	public $info;
	public $lecturer;
	public $room;
	public $start_h;
	public $lenght;
	public $groups;
	public $type;
	public $added;
	public function __construct($row) {
		$this->date = $row['date'];
		$this->name = $row['name'];
		$this->info = $row['info'];
		$this->lecturer = $row['lecturer'];
		$this->room = $row['room'];
		$this->start_h = $row['start_hour'];
		$this->lenght = $row['lenght'];
		$this->groups = str_replace("|", "", str_replace(",", " ", $row['groups']));
		$this->type = $row['type'];
		$this->added = $row['added'];
	} 
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$sem1 = array("October", "November", "December", "January", "February");
$sem2 = array("March", "April", "May", "June", "July", "August", "September");

require "db.php";
$conn = mysqli_connect($servername, $username, $password, $dbname, 3306);

$sql_g = "
SELECT DISTINCT *
FROM `prowadzacy`
WHERE `komorka` = 'WZ'
ORDER BY `name` ASC";

$sql_g1 = "
SELECT DISTINCT *
FROM `prowadzacy`
WHERE `name` = 'DEBITA'
ORDER BY `name` ASC";

$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load("template.xlsx");

$worksheet = $spreadsheet->getSheet(0);

$startRow = 7;
$rocznik = 2020;
$result = mysqli_query($conn, $sql_g);
$prow[] = array();
$x = 0;
while($prowadzacy = mysqli_fetch_assoc($result)) {
	$prow[$x] = new Prowadzacy($prowadzacy);
	$x++;
}
$total = count($prow);
for ($prow_id=0; $prow_id < $total; $prow_id++) {
	$rocznik = 2020;
	$startCol = 36;

	write_init($worksheet, $prow_id, $prow[$prow_id], $startRow);

	foreach($sem1 as $m) {
		if($m == "January") $rocznik = 2021;
		$poczatek_mies = date( 'Y-m-d', strtotime('first day of '.$m.' '.$rocznik));
		$mies = date('m', strtotime('first day of '.$m.' '.$rocznik));
		$rok = date('Y', strtotime('first day of '.$m.' '.$rocznik));
		$d = date('t', strtotime('first day of '.$m.' '.$rocznik));
		$sql = "
		SELECT COUNT(`code`) as total
		FROM `zajecia` 
		WHERE `date` LIKE '{$rok}-{$mies}%' 
		AND `lecturer` LIKE '%{$prow[$prow_id]->nazwisko}%'";
		$result = mysqli_query($conn, $sql);
		$data=mysqli_fetch_assoc($result);
		if( $data['total'] == 0) { $startCol += 38; continue; }
		$zajecia = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		for($day=0;$day<$d;$day++){
			$date1 = date( 'Y-m-d', strtotime($poczatek_mies.' +'.$day.' day'));
			#$sql = "SELECT * FROM `zajecia` WHERE `date`='{$date1}' AND `lecturer` LIKE '%{$prow[$prow_id]->nazwisko}%' ORDER BY `added` ASC;";
			$sql = "
			SELECT * 
			FROM `zajecia` 
			WHERE `code` IN (
				SELECT `code` FROM (
					SELECT `code`, MAX(`added`) 
					FROM `zajecia` 
					WHERE `date` = '{$date1}' 
					AND `lecturer` LIKE '%{$prow[$prow_id]->nazwisko}%' 
					GROUP BY `start_hour`) AS HELP
			)
			ORDER BY `start_hour` ASC;";

			$result = mysqli_query($conn, $sql);
			$zajecia = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			while($row = mysqli_fetch_assoc($result)) {
				$zajecia[$row['start_hour']-1] = new Zajecie($row);
			}
			#if($m == "November") echo "<pre><div >".print_r($zajecia, true)."</div></pre>";
			for ($a=0; $a < 15; $a++) {
				if(!is_int($zajecia[$a])) $prow[$prow_id]->addZaj($zajecia[$a]);
			}
			#if($m == "November") echo "<pre><div >".print_r($prow[$prow_id], true)."</div></pre>";
		}
		write($worksheet, $prow[$prow_id], $startRow, $startCol);
		$startCol += 38;
		#echo "<pre><div style='float: left; dsplay: block;'>".print_r($prow[$prow_id], true)."</div></pre>";
		$prow[$prow_id]->zero();
	}
	$startRow++;
	$progress_s1 = intval(($prow_id+1)/$total * 100)."%";
	echo '<script language="javascript">
    document.getElementById("progress_s1").innerHTML="<div style=\"width:'.$progress_s1.';background-color:#ddd;\">&nbsp;</div>";
    document.getElementById("information_s1").innerHTML="Podsumowano dla: '.($prow_id+1).' prowadzących.";
    </script>';
    echo str_repeat(' ',1024*64);
    #flush();
}


$worksheet = $spreadsheet->getSheet(1);
$startRow = 7;

$result = mysqli_query($conn, $sql_g);
for ($prow_id=0; $prow_id < $total; $prow_id++) {
	$rocznik = 2021;
	$startCol = 36;

	write_init($worksheet, $prow_id, $prow[$prow_id], $startRow, 2);

	foreach($sem2 as $m) {
		$poczatek_mies = date( 'Y-m-d', strtotime('first day of '.$m.' '.$rocznik));
		$mies = date('m', strtotime('first day of '.$m.' '.$rocznik));
		$rok = date('Y', strtotime('first day of '.$m.' '.$rocznik));
		$d = date('t', strtotime('first day of '.$m.' '.$rocznik));
		$sql = "
		SELECT COUNT(`code`) as total
		FROM `zajecia` 
		WHERE `date` LIKE '{$rok}-{$mies}%' 
		AND `lecturer` LIKE '%{$prow[$prow_id]->nazwisko}%'";
		$result = mysqli_query($conn, $sql);
		$data=mysqli_fetch_assoc($result);
		if( $data['total'] == 0) { $startCol += 38; continue; }
		for($day=0;$day<$d;$day++){
			$date1 = date( 'Y-m-d', strtotime($poczatek_mies.' +'.$day.' day'));
			
			$sql = "
			SELECT * 
			FROM `zajecia` 
			WHERE `code` IN (
				SELECT `code` FROM (
					SELECT `code`, MAX(`added`) 
					FROM `zajecia` 
					WHERE `date` = '{$date1}' 
					AND `lecturer` LIKE '%{$prow[$prow_id]->nazwisko}%' 
					GROUP BY `start_hour`) AS HELP
			)
			ORDER BY `start_hour` ASC;";

			$result = mysqli_query($conn, $sql);
			$zajecia = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			while($row = mysqli_fetch_assoc($result)) {
				$zajecia[$row['start_hour']-1] = $row;
			}
			foreach ($zajecia as $zaj) {
				#echo "<pre><div style='float: left; dsplay: block;'>".print_r($zaj)."</div></pre>";
				if(!is_int($zaj)) $prow[$prow_id]->addZaj(new Zajecie($zaj));
			}
		}
		write($worksheet, $prow[$prow_id], $startRow, $startCol);
		$startCol += 38;
		#echo "<pre><div style='float: left; dsplay: block;'>".print_r($prow[$prow_id], true)."</div></pre>";
		$prow[$prow_id]->zero();
	}
	$startRow++;
	$progress_s2 = intval(($prow_id+1)/$total * 100)."%";
	echo '<script language="javascript">
    document.getElementById("progress_s2").innerHTML="<div style=\"width:'.$progress_s2.';background-color:#ddd;\">&nbsp;</div>";
    document.getElementById("information_s2").innerHTML="Podsumowano dla: '.($prow_id+1).' prowadzących.";
    </script>';
    echo str_repeat(' ',1024*64);
    #flush();
}

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('pensum.xlsx');

echo "<a href='pensum.xlsx'>Pobierz podsumowanie</a>";

#header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
#header('Content-Disposition: attachment;filename="pensum.xlsx"');
#header('Cache-Control: max-age=0');
#flush();
#$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
#$writer->save('php://output');

function write_init($worksheet, $prow_id, $prow, $startRow, $sem = 1) {
	$worksheet->insertNewRowBefore($startRow+1,1);
	$worksheet->setCellValue('A'.$startRow, $prow_id+1);
	$worksheet->setCellValue('B'.$startRow, $prow->stopien_woj);
	$worksheet->setCellValue('C'.$startRow, $prow->stopien_nauk);
	$worksheet->setCellValue('D'.$startRow, $prow->nazwisko." ".$prow->imie);
	$worksheet->setCellValue('E'.$startRow, $prow->komorka);
	$worksheet->setCellValue('F'.$startRow, $prow->stanowisko);
	$worksheet->setCellValue('G'.$startRow, $prow->wna);
	$worksheet->setCellValue('H'.$startRow, $prow->pensum_ustalone_ds);
	$worksheet->setCellValue('I'.$startRow, $prow->pensum_ustalone);
	$worksheet->setCellValue('J'.$startRow, $prow->liczba_ponad_wymiar);
	$x = 0;
	if($sem == 1) $start = Coordinate::columnIndexFromString("HR"); else { $start = Coordinate::columnIndexFromString("KP"); $x = 2; }
	$worksheet->setCellValueByColumnAndRow($start, $startRow, "=BT".$startRow);
	$worksheet->setCellValueByColumnAndRow($start + 1, $startRow, "=DF".$startRow);
	$worksheet->setCellValueByColumnAndRow($start + 2, $startRow, "=ER".$startRow);
	$worksheet->setCellValueByColumnAndRow($start + 3, $startRow, "=GD".$startRow);
	$worksheet->setCellValueByColumnAndRow($start + 4, $startRow, "=HP".$startRow);
	if($sem == 2) {
		$worksheet->setCellValueByColumnAndRow($start + 5, $startRow, "=JB".$startRow);
		$worksheet->setCellValueByColumnAndRow($start + 6, $startRow, "=KN".$startRow);
		$worksheet->setCellValueByColumnAndRow($start + 7, $startRow, "=SUM(KP".$startRow.":KV".$startRow.")");
		$worksheet->setCellValueByColumnAndRow($start + 8, $startRow, "=KW".$startRow."/Z".$startRow);
		$worksheet->setCellValueByColumnAndRow($start + 7 + $x, $startRow, str_replace("7",$startRow,"=SUM(BK7+CW7+EI7+FU7+HG7+IS7+KE7)-(KZ7)-(LA7)"));
		$worksheet->setCellValueByColumnAndRow($start + 8 + $x, $startRow, str_replace("7",$startRow,"=SUM(AZ7+BB7+BF7+CL7+CN7+CR7+DX7+DZ7+ED7+FJ7+FL7+FP7+GV7+GX7+HB7+IH7+IJ7+IN7+JT7+JV7+JZ7)"));
		$worksheet->setCellValueByColumnAndRow($start + 9 + $x, $startRow, str_replace("7",$startRow,"=SUM(BJ7+CV7+EH7+FT7+HF7+IR7+KD7)"));
		$worksheet->setCellValueByColumnAndRow($start + 10 + $x, $startRow, str_replace("7",$startRow,"=KY7+KZ7+LA7"));
		$worksheet->setCellValueByColumnAndRow($start + 11 + $x, $startRow, str_replace("7",$startRow,"=SUM(AJ7+BV7+DH7+ET7+GF7+HR7+JD7)"));
		$worksheet->setCellValueByColumnAndRow($start + 12 + $x, $startRow, str_replace("7",$startRow,"=SUM(AK7+BW7+DI7+EU7+GG7+HS7+JE7)"));
		$worksheet->setCellValueByColumnAndRow($start + 13 + $x, $startRow, str_replace("7",$startRow,"=SUM(AL7+BX7+DJ7+EV7+GH7+HT7+JF7)"));
		$worksheet->setCellValueByColumnAndRow($start + 14 + $x, $startRow, str_replace("7",$startRow,"=SUM(AM7+BY7+DK7+EW7+GI7+HU7+JG7)"));
		$worksheet->setCellValueByColumnAndRow($start + 15 + $x, $startRow, str_replace("7",$startRow,"=SUM(AN7+BZ7+DL7+EX7+GJ7+HV7+JH7)"));
		$worksheet->setCellValueByColumnAndRow($start + 16 + $x, $startRow, str_replace("7",$startRow,"=SUM(AP7+CB7+DN7+EZ7+GL7+HX7+JJ7)"));
		$worksheet->setCellValueByColumnAndRow($start + 17 + $x, $startRow, str_replace("7",$startRow,"=SUM(AQ7+CC7+DO7+FA7+GM7+HY7+JK7)"));
		$worksheet->setCellValueByColumnAndRow($start + 18 + $x, $startRow, str_replace("7",$startRow,"=SUM(AR7+CD7+DP7+FB7+GN7+HZ7+JL7)"));
		$worksheet->setCellValueByColumnAndRow($start + 19 + $x, $startRow, str_replace("7",$startRow,"=SUM(AS7+CE7+DQ7+FC7+GO7+IA7+JM7)"));
		$worksheet->setCellValueByColumnAndRow($start + 20 + $x, $startRow, str_replace("7",$startRow,"=SUM(AT7+CF7+DR7+FD7+GP7+IB7+JN7)"));
		$worksheet->setCellValueByColumnAndRow($start + 21 + $x, $startRow, str_replace("7",$startRow,"=SUM(AU7+CG7+DS7+FE7+GQ7+IC7+JO7)"));
		$worksheet->setCellValueByColumnAndRow($start + 22 + $x, $startRow, str_replace("7",$startRow,"=SUM(AW7+CI7+DU7+FG7+GS7+IE7+JQ7)"));
		$worksheet->setCellValueByColumnAndRow($start + 23 + $x, $startRow, str_replace("7",$startRow,"=SUM(LC7+LD7+LE7+LG7+LH7+LI7+LL7+LM7)"));
		$worksheet->setCellValueByColumnAndRow($start + 24 + $x, $startRow, str_replace("7",$startRow,"=SUM(LF7+LJ7+LK7+LN7)"));
		$worksheet->setCellValueByColumnAndRow($start + 25 + $x, $startRow, str_replace("7",$startRow,"=SUM(LO7+LP7)"));
		$worksheet->setCellValueByColumnAndRow($start + 26 + $x, $startRow, str_replace("7",$startRow,"=SUM(BL7+CX7+EJ7+FV7+HH7+IT7+KF7)"));
		$worksheet->setCellValueByColumnAndRow($start + 27 + $x, $startRow, str_replace("7",$startRow,"=SUM(BM7+CY7+EK7+FW7+HI7+IU7+KG7)"));
		$worksheet->setCellValueByColumnAndRow($start + 28 + $x, $startRow, str_replace("7",$startRow,"=SUM(BN7+CZ7+EL7+FX7+HJ7+IV7+KH7)"));
		$worksheet->setCellValueByColumnAndRow($start + 29 + $x, $startRow, str_replace("7",$startRow,"=SUM(BO7+DA7+EM7+FY7+HK7+IW7+KI7)"));
		$worksheet->setCellValueByColumnAndRow($start + 30 + $x, $startRow, str_replace("7",$startRow,"=SUM(BP7+DB7+EN7+FZ7+HL7+IX7+KJ7)"));
		$worksheet->setCellValueByColumnAndRow($start + 31 + $x, $startRow, str_replace("7",$startRow,"=SUM(BQ7+DC7+EO7+GA7+HM7+IY7+KK7)"));
		$worksheet->setCellValueByColumnAndRow($start + 32 + $x, $startRow, str_replace("7",$startRow,"=SUM(BR7+DD7+EP7+GB7+HN7+IZ7+KL7)"));
		$worksheet->setCellValueByColumnAndRow($start + 33 + $x, $startRow, str_replace("7",$startRow,"=SUM(BS7+DE7+EQ7+GC7+HO7+JA7+KM7)"));
		$worksheet->setCellValueByColumnAndRow($start + 34 + $x, $startRow, str_replace("7",$startRow,"=LB7+LQ7+SUM(LR7:LY7)"));
		$worksheet->setCellValueByColumnAndRow($start + 35 + $x, $startRow, str_replace("7",$startRow,"=LZ7/I7"));
		$worksheet->setCellValueByColumnAndRow($start + 36 + $x, $startRow, str_replace("7",$startRow,"=SUM(BU7+DG7+ES7+GE7+HQ7+JC7+KO7)"));
		$worksheet->setCellValueByColumnAndRow($start + 37 + $x, $startRow, str_replace("7",$startRow,'=IF(LZ7>I7,LZ7-I7,"-")'));
	} else {
	$worksheet->setCellValueByColumnAndRow($start + 5 + $x, $startRow, "=SUM(HR".$startRow.":HV".$startRow.")");
	$worksheet->setCellValueByColumnAndRow($start + 6 + $x, $startRow, "=HW".$startRow."/R".$startRow);
	$worksheet->setCellValueByColumnAndRow($start + 7 + $x, $startRow, str_replace("7",$startRow,"=SUM(BK7+CW7+EI7+FU7+HG7)-(HZ7)-(IA7)"));
	$worksheet->setCellValueByColumnAndRow($start + 8 + $x, $startRow, str_replace("7",$startRow,"=SUM(AZ7+BB7+BF7+CL7+CN7+CR7+DX7+DZ7+ED7+FJ7+FL7+FP7+GV7+GX7+HB7)"));
	$worksheet->setCellValueByColumnAndRow($start + 9 + $x, $startRow, str_replace("7",$startRow,"=SUM(BJ7+CV7+EH7+FT7+HF7)"));
	$worksheet->setCellValueByColumnAndRow($start + 10 + $x, $startRow, str_replace("7",$startRow,"=SUM(HY7+HZ7+IA7)"));
	$worksheet->setCellValueByColumnAndRow($start + 11 + $x, $startRow, str_replace("7",$startRow,"=SUM(AJ7+BV7+DH7+ET7+GF7)"));
	$worksheet->setCellValueByColumnAndRow($start + 12 + $x, $startRow, str_replace("7",$startRow,"=SUM(AK7+BW7+DI7+EU7+GG7)"));
	$worksheet->setCellValueByColumnAndRow($start + 13 + $x, $startRow, str_replace("7",$startRow,"=SUM(AL7+BX7+DJ7+EV7+GH7)"));
	$worksheet->setCellValueByColumnAndRow($start + 14 + $x, $startRow, str_replace("7",$startRow,"=SUM(AM7+BY7+DK7+EW7+GI7)"));
	$worksheet->setCellValueByColumnAndRow($start + 15 + $x, $startRow, str_replace("7",$startRow,"=SUM(AN7+BZ7+DL7+EX7+GJ7)"));
	$worksheet->setCellValueByColumnAndRow($start + 16 + $x, $startRow, str_replace("7",$startRow,"=SUM(AP7+CB7+DN7+EZ7+GL7)"));
	$worksheet->setCellValueByColumnAndRow($start + 17 + $x, $startRow, str_replace("7",$startRow,"=SUM(AQ7+CC7+DO7+FA7+GM7)"));
	$worksheet->setCellValueByColumnAndRow($start + 18 + $x, $startRow, str_replace("7",$startRow,"=SUM(AR7+CD7+DP7+FB7+GN7)"));
	$worksheet->setCellValueByColumnAndRow($start + 19 + $x, $startRow, str_replace("7",$startRow,"=SUM(AS7+CE7+DQ7+FC7+GO7)"));
	$worksheet->setCellValueByColumnAndRow($start + 20 + $x, $startRow, str_replace("7",$startRow,"=SUM(AT7+CF7+DR7+FD7+GP7)"));
	$worksheet->setCellValueByColumnAndRow($start + 21 + $x, $startRow, str_replace("7",$startRow,"=SUM(AU7+CG7+DS7+FE7+GQ7)"));
	$worksheet->setCellValueByColumnAndRow($start + 22 + $x, $startRow, str_replace("7",$startRow,"=SUM(AW7+CI7+DU7+FG7+GS7)"));
	$worksheet->setCellValueByColumnAndRow($start + 23 + $x, $startRow, str_replace("7",$startRow,"=IC7+ID7+SUM(IE7:IG7)+IH7+II7+IL7+IM7"));
	$worksheet->setCellValueByColumnAndRow($start + 24 + $x, $startRow, str_replace("7",$startRow,"=SUM(IF7+IJ7+IK7+IN7)"));
	$worksheet->setCellValueByColumnAndRow($start + 25 + $x, $startRow, str_replace("7",$startRow,"=SUM(IO7+IP7)"));
	$worksheet->setCellValueByColumnAndRow($start + 26 + $x, $startRow, str_replace("7",$startRow,"=SUM(BL7+CX7+EJ7+FV7+HH7)"));
	$worksheet->setCellValueByColumnAndRow($start + 27 + $x, $startRow, str_replace("7",$startRow,"=SUM(BM7+CY7+EK7+FW7+HI7)"));
	$worksheet->setCellValueByColumnAndRow($start + 28 + $x, $startRow, str_replace("7",$startRow,"=SUM(BN7+CZ7+EL7+FX7+HJ7)"));
	$worksheet->setCellValueByColumnAndRow($start + 29 + $x, $startRow, str_replace("7",$startRow,"=SUM(BO7+DA7+EM7+FY7+HK7)"));
	$worksheet->setCellValueByColumnAndRow($start + 30 + $x, $startRow, str_replace("7",$startRow,"=SUM(BP7+DB7+EN7+FZ7+HL7)"));
	$worksheet->setCellValueByColumnAndRow($start + 31 + $x, $startRow, str_replace("7",$startRow,"=SUM(BQ7+DC7+EO7+GA7+HM7)"));
	$worksheet->setCellValueByColumnAndRow($start + 32 + $x, $startRow, str_replace("7",$startRow,"=SUM(BR7+DD7+EP7+GB7+HN7)"));
	$worksheet->setCellValueByColumnAndRow($start + 33 + $x, $startRow, str_replace("7",$startRow,"=SUM(BS7+DE7+EQ7+GC7+HO7)"));
	$worksheet->setCellValueByColumnAndRow($start + 34 + $x, $startRow, str_replace("7",$startRow,"=IB7+IQ7+SUM(IR7:IY7)"));
	$worksheet->setCellValueByColumnAndRow($start + 35 + $x, $startRow, str_replace("7",$startRow,"=IZ7/I7"));
	$worksheet->setCellValueByColumnAndRow($start + 36 + $x, $startRow, str_replace("7",$startRow,"=SUM(BU7+DG7+ES7+GE7+HQ7)"));
	$worksheet->setCellValueByColumnAndRow($start + 37 + $x, $startRow, str_replace("7",$startRow,'=IF(IZ7>I7,IZ7-I7,"-")'));
	}
}

function write($worksheet, $prow, $startRow, $startCol) {
	$worksheet->setCellValueByColumnAndRow(0 + $startCol, $startRow, $prow->Z1);
	$worksheet->setCellValueByColumnAndRow(1 + $startCol, $startRow, $prow->Z2);
	$worksheet->setCellValueByColumnAndRow(2 + $startCol, $startRow, $prow->INF1);
	
	$worksheet->setCellValueByColumnAndRow(4 + $startCol, $startRow, $prow->LOG1);
	$worksheet->setCellValueByColumnAndRow(5 + $startCol, $startRow, "=SUM(".Coordinate::stringFromColumnIndex(0 + $startCol).$startRow.":".Coordinate::stringFromColumnIndex(4 + $startCol).$startRow.")");
	$worksheet->setCellValueByColumnAndRow(6 + $startCol, $startRow, $prow->BN1);
	$worksheet->setCellValueByColumnAndRow(7 + $startCol, $startRow, $prow->BN2);

	$worksheet->setCellValueByColumnAndRow(9 + $startCol, $startRow, $prow->BNN2);
	$worksheet->setCellValueByColumnAndRow(10 + $startCol, $startRow, $prow->IB1);
	$worksheet->setCellValueByColumnAndRow(11 + $startCol, $startRow, $prow->IB2);
	$worksheet->setCellValueByColumnAndRow(12 + $startCol, $startRow, "=SUM(".Coordinate::stringFromColumnIndex(6 + $startCol).$startRow.":".Coordinate::stringFromColumnIndex(11 + $startCol).$startRow.")");
	$worksheet->setCellValueByColumnAndRow(13 + $startCol, $startRow, $prow->SPd);
	$worksheet->setCellValueByColumnAndRow(14 + $startCol, $startRow, "=SUM(".Coordinate::stringFromColumnIndex(5 + $startCol).$startRow."+".Coordinate::stringFromColumnIndex(12 + $startCol).$startRow.")");
	#$worksheet->setCellValueByColumnAndRow(14 + $startCol, $startRow, "=SUM(".Coordinate::stringFromColumnIndex(5 + $startCol).$startRow.";".Coordinate::stringFromColumnIndex(12 + $startCol).$startRow.")");
	$worksheet->setCellValueByColumnAndRow(16 + $startCol, $startRow, $prow->SW_JSM_D);
	$worksheet->setCellValueByColumnAndRow(17 + $startCol, $startRow, $prow->D1);
	$worksheet->setCellValueByColumnAndRow(18 + $startCol, $startRow, $prow->SW_JSM_LOG);
	$worksheet->setCellValueByColumnAndRow(19 + $startCol, $startRow, "POZOST LOG");
	$worksheet->setCellValueByColumnAndRow(20 + $startCol, $startRow, $prow->SW_JSM_ZA);
	$worksheet->setCellValueByColumnAndRow(21 + $startCol, $startRow, "=SUM(".Coordinate::stringFromColumnIndex(16 + $startCol).$startRow.":".Coordinate::stringFromColumnIndex(20 + $startCol).$startRow.")");
	$worksheet->setCellValueByColumnAndRow(22 + $startCol, $startRow, $prow->SW_JSM_IB);
	$worksheet->setCellValueByColumnAndRow(23 + $startCol, $startRow, "IB1");
	$worksheet->setCellValueByColumnAndRow(24 + $startCol, $startRow, "IB2");
	$worksheet->setCellValueByColumnAndRow(25 + $startCol, $startRow, "=SUM(".Coordinate::stringFromColumnIndex(22 + $startCol).$startRow.":".Coordinate::stringFromColumnIndex(24 + $startCol).$startRow.")");
	$worksheet->setCellValueByColumnAndRow(26 + $startCol, $startRow, $prow->MED);
	$worksheet->setCellValueByColumnAndRow(27 + $startCol, $startRow, "=SUM(".Coordinate::stringFromColumnIndex(21 + $startCol).$startRow."+".Coordinate::stringFromColumnIndex(25 + $startCol).$startRow."+".Coordinate::stringFromColumnIndex(26 + $startCol).$startRow.")");
	$worksheet->setCellValueByColumnAndRow(28 + $startCol, $startRow, $prow->SO);
	$worksheet->setCellValueByColumnAndRow(29 + $startCol, $startRow, $prow->K);
	#$worksheet->setCellValueByColumnAndRow(30 + $startCol, $startRow, $prow->SW_JSM_ZA); SJO Kursy JO
	#$worksheet->setCellValueByColumnAndRow(31 + $startCol, $startRow, $prow->); Zaj. nieregularne
	#$worksheet->setCellValueByColumnAndRow(32 + $startCol, $startRow, $prow->); 
	#$worksheet->setCellValueByColumnAndRow(33 + $startCol, $startRow, $prow->); Zaj. nieregularne
	#$worksheet->setCellValueByColumnAndRow(34 + $startCol, $startRow, $prow->); 
	#$worksheet->setCellValueByColumnAndRow(35 + $startCol, $startRow, $prow->); Zaj. nieregularne
	$worksheet->setCellValueByColumnAndRow(36 + $startCol, $startRow, "=".Coordinate::stringFromColumnIndex(14 + $startCol).$startRow."+".Coordinate::stringFromColumnIndex(15 + $startCol).$startRow."+SUM(".Coordinate::stringFromColumnIndex(27 + $startCol).$startRow.":".Coordinate::stringFromColumnIndex(35 + $startCol).$startRow.")");

}



?>
</body>
</html>