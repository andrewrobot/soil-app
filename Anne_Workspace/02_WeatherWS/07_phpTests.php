<!DOCTYPE html>
<html>
<body>
  <?php
    $time = date("Ymdh:i:sa");
    
    $timeAr = str_split($time);
    
    $dateAr = array_slice($timeAr, 0, 8);
    $yyAr = array_slice($dateAr, 0, 4);
    $mmAr = array_slice($dateAr, 4, 2);
    $ddAr = array_slice($dateAr, 6, 2);
    $yy = implode($yyAr);
    $mm = implode($mmAr);
    $dd = implode($ddAr);
    $date = $dd . "/" . $mm . "/" . $yy ." (dd/mm/yyyy)";
    
    $hmsAr = array_slice($timeAr, 8);
    $hms = implode("", $hmsAr);
    
    echo "Time: " . $time . "<br><br>Date: " . $date .  "<br><br>hhmmss: " . $hms;
  ?>
</body>
<html>
