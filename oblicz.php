<?php 

require_once("vendor/autoload.php");
include("krumo-master/class.krumo.php");

use NumPHP\Core\NumArray;
use NumPHP\LinAlg\LinAlg;

function srednia($tablica){
            $wartosc_srednia = [];

           for($i=0; $i < count($tablica); $i++){
               $wartosc_srednia[$i] = array_sum($tablica[$i])/10;
           }

            return $wartosc_srednia;
}

function wariancja($wartosc_srednia,$tablica, $ilosc){
        $wartosc_wariancja = [];
            for ($i = 0; $i < $ilosc; $i++)
            {
                $wartosc_wariancja[$i] = 0;
                for ($j = 0; $j < 10; $j++)
                {
                    $wartosc_wariancja[$i] += pow(($tablica[$i][$j] - $wartosc_srednia[$i]),2);
                    
                }
                
                $wartosc_wariancja[$i] /= 9;
            }
            return $wartosc_wariancja;
}

function odchylenie($wariancja, $ilosc){
    $wartosc_odchylenia = [];
        for ($i = 0; $i < $ilosc; $i++)
        {
            $wartosc_odchylenia[$i] = sqrt($wariancja[$i]);
        }

    return $wartosc_odchylenia;
};

function wspolczynnik_zm($odchylenie, $srednia, $ilosc){
    $wartosc_wspolczynnika = [];
        
            for ($i = 0; $i <$ilosc; $i++)
            {
                $wartosc_wspolczynnika[$i] = $odchylenie[$i] / $srednia[$i];
            }

            return $wartosc_wspolczynnika;
}

function sprawdz_zm($tablica, $wspolczynnik){
    $zmienne_do_modelu = [];
    $temp = 0;
    for($i=1; $i<count($tablica); $i++){
        if(abs($wspolczynnik[$i]) > 0.1){
            $zmienne_do_modelu[$temp] = $tablica[$i];
            $temp++;
        }
    }
    return $zmienne_do_modelu;
}

function wspolczynnik_kor_r_oblicz($tablica, $x, $y){
    $srednia1 = 0;
    $srednia2 = 0;
    $licznik = 0;
    $mianownik = 0;
    $m1 = 0;
    $m2 = 0;
    $tab1 = [];
    $tab2 = [];
   
    for($i=0;$i<count($tablica);$i++){
        if($i == $x){
            $srednia1 = array_sum($tablica[$i])/10;
            $tab1 = $tablica[$i];
        }
        if($i == $y){
            $srednia2 = array_sum($tablica[$i])/10;
            $tab2 = $tablica[$i];
        }
    }    
        for($j=0; $j<10; $j++){
            $licznik += ($tab1[$j] - $srednia1) * ($tab2[$j] - $srednia2);
        }

        for($i=0;$i<10;$i++){
            $m1 += pow(($tab1[$i] - $srednia1),2);
            $m2 += pow(($tab2[$i] - $srednia2),2);
        }
        $mianownik = sqrt($m1*$m2);
        $wspolczynnik = $licznik / $mianownik;
        //var_dump($wspolczynnik);
        return $wspolczynnik;

}

function wspolczynnik_kor_r0_oblicz($y, $zmienne, $x){
    $srednia1 = 0;
    $srednia2 = 0;
    $licznik = 0;
    $mianownik = 0;
    $m1 = 0;
    $m2 = 0;
    $tab1 = [];
    $tab2 = [];
   
    for($i=0;$i<count($zmienne);$i++){
        if($i == $x){
            $srednia1 = array_sum($zmienne[$i])/10;
            $tab1 = $zmienne[$i];
        }
    }
    $srednia2 =  array_sum($y)/10;

    for($j=0; $j<10; $j++){
        $licznik += ($tab1[$j] - $srednia1) * ($y[$j] - $srednia2);
    }

    for($i=0;$i<10;$i++){
        $m1 += pow(($tab1[$i] - $srednia1),2);
        $m2 += pow(($y[$i] - $srednia2),2);
    }
    $mianownik = sqrt($m1*$m2);
    $wspolczynnik = $licznik / $mianownik;
    return $wspolczynnik;
}

function wspolczynnik_kor_r($tablica){  
    $licznik = count($tablica);
    $wspolczynnik_kor_tab = [];
    for($i=0; $i<count($tablica);$i++){
       for($j=0;$j<count($tablica);$j++){
           $wspolczynnik_kor_tab[$i][$j] = wspolczynnik_kor_r_oblicz($tablica, $i, $j);
       }
   }
   return $wspolczynnik_kor_tab;
}

function wspolczynnik_kor_r0($tablica, $y){
    $wspolczynnik_kor_tab = [];
    for($i=0;$i<count($tablica);$i++){
        $wspolczynnik_kor_tab[$i] = wspolczynnik_kor_r0_oblicz($y,$tablica, $i);
    }
    return $wspolczynnik_kor_tab;
}

function wartosc_graniczna($rozmiar, $alpha){
    $wartosc_graniczna = 0;

    $wartosc_graniczna = sqrt(pow($alpha, 2) / (($rozmiar - 2) + pow($alpha, 2)));

    return $wartosc_graniczna;
}

function tablica_grafu($wsp_r ,$graniczna, $licznik){
    $tablica_grafu = [];
    for($i = 0; $i< $licznik;$i++){
        for($j = 0; $j < $licznik; $j++){
            if( abs($wsp_r[$i][$j]) < $graniczna){
                $tablica_grafu[$i][$j] = 0;
            }
            else{
                $tablica_grafu[$i][$j] = 1;
            }
        }
    }
    return $tablica_grafu;
}

function indeks($r0, $r, $graniczna ){
    for($i=0;$i<count($r0);$i++){
        $r0[$i] = abs($r0[$i]);
        if($r0[$i]<= $graniczna){
            $r0[$i] = 0;
        }
    }  
    $najlepszy_x = array_keys($r0, max($r0));
    $indeksy_zmiennych = [];
    array_push($indeksy_zmiennych,$najlepszy_x[0]);

    for($i=0; $i<count($r); $i++){
        for($j=0; $j<count($r); $j++){
           
            if($i == $najlepszy_x[0]){
               if($r0[$j] != 0){
                   if(abs($r[$i][$j]) < $graniczna && $r[$i][$j]!=1){
                       array_push($indeksy_zmiennych, $j);
                   }
               } 
            }
        }
    }

    asort($indeksy_zmiennych);
    return $indeksy_zmiennych;
}

function korelacja($indeksy_zmiennych, $zmienne_do_modelu){
    
    $tablicaZmiennych = [];
    $licznik = 0;
    foreach($indeksy_zmiennych as $indeks){
        for($i=0;$i<count($zmienne_do_modelu);$i++){
            for($j=0;$j<10;$j++){
                if($indeks == $i){
                    $tablicaZmiennych[$licznik][$j] = $zmienne_do_modelu[$i][$j];
                }
            }
        }
        $licznik++;
    }

    for($j=0;$j<10;$j++){
            $tablicaZmiennych[$licznik][$j] = 1;
    }
    return $tablicaZmiennych;
}

function wysw_rownanie($Q, $indeksy){
    if(count($indeksy) == 1){
        $rownanie = "Y = " . round($Q[0][0], 4) . "*x" . ($indeksy[0] + 1) . " + " . round($Q[0][1], 4);
    }
    else if(count($indeksy) == 2){
        $rownanie = "Y = " . round($Q[0][0], 4) . "*x" . ($indeksy[0] + 1) . " + " . round($Q[0][1], 4) . "*x" . ($indeksy[1] + 1) . " + " . round($Q[0][2], 1);
    }
    else if(count($indeksy) == 3){
        $rownanie = "Y = " . round($Q[0][0], 4) . "*x" . ($indeksy[0] + 1) . " + " . round($Q[0][1], 4) . "*x" . ($indeksy[1] + 1) . " + " . round($Q[0][1], 4) . "*x" . ($indeksy[2] + 1) . round($Q[0][2], 4);
    }
    return $rownanie;
}

function sr_blad($indeksy, $wybrane_x, $D2){
    $sr_blad = [];

             if (count($indeksy) == 1)
            {
                $sr_blad[0] = sqrt($D2[0][0]);
                $sr_blad[1] = sqrt($D2[1][1]);
            }

            else if (count($indeksy) == 2)
            {
                $sr_blad[0] = sqrt($D2[0][0]);
                $sr_blad[1] = sqrt($D2[1][1]);
                $sr_blad[2] = sqrt($D2[2][2]);
                
            }

            else if (count($indeksy) == 3)
            {
                $sr_blad[0] = sqrt($D2[0][0]);
                $sr_blad[1] = sqrt($D2[1][1]);
                $sr_blad[2] = sqrt($D2[2][2]);
                $sr_blad[3] = sqrt($D2[3][3]);
                
            }
            

            return $sr_blad;
}

function wspolczynnik_determinacji($indeksy,$Q, $wybrane_x, $tabY ){
    $wsp_det_licznik = 0;
    $wsp_det_mianownik = 0;
    $wspolczynnik_determinacji = 0;

    $y_teoretyczne = [];

    for ($i = 0; $i < 10; $i++)
    {
        if (count($indeksy) < 2)
        {
            $y_teoretyczne[$i] = $Q[0][0] * $wybrane_x[0][$i] + $Q[0][1];
        }
        else if (count($indeksy) == 2)
        {
            $y_teoretyczne[$i] = $Q[0][0] * $wybrane_x[0][$i] + $Q[0][1] * $wybrane_x[1][$i] + $Q[0][2];
        }
    }

    for ($i = 0; $i < 10; $i++)
    {
        $wsp_det_licznik += pow($y_teoretyczne[$i] - (array_sum($tabY)/10), 2);
    }

    for ($i = 0; $i < 10; $i++)
    {
        $wsp_det_mianownik += pow($tabY[$i] - (array_sum($tabY)/10), 2);
    }

    $wspolczynnik_determinacji = round($wsp_det_licznik / $wsp_det_mianownik, 4)* 100;

    return($wspolczynnik_determinacji.'%');
}

if(empty($_FILES)){
    echo "Nie wprowadzono pliku";
    die();
}
$filecontents = file_get_contents($_FILES['file']['tmp_name']);

$tablica = preg_split('/[\s]+/', $filecontents, -1, PREG_SPLIT_NO_EMPTY);

$zmienne = array(
    [],[],[],[],[]
);

$licznik = 0;
foreach($tablica as $tab){
    switch($licznik){
        case 0:
            array_push($zmienne[0], floatval($tab));
            $licznik++;
            break;
        case 1:
            array_push($zmienne[1], floatval($tab));
            $licznik++;
            break;
        case 2:
            array_push($zmienne[2], floatval($tab));
            $licznik++;
            break;
        case 3:
            array_push($zmienne[3], floatval($tab));
            $licznik++;
            break;
        case 4:
            array_push($zmienne[4], floatval($tab));
            $licznik = 0;
            break;
    }
}

// $zmienneTestoweK = [
//     [443.92,459.67,470.33,491.54,523.40,604.62,704.80,719.76,777.61,818.16],
//     [102.80,101.20,97.90,95.20,88.30,77.10,77.40,76.20,62.80,64.50],
//     [1.80,2.30,3.30,5.20,5.70,10.00,10.70,13.90,15.00,10.20],
//     [24.10,22.70,20.30,16.20,12.50,7.40,8.20,10.20,5.90,6.30],
//     [2201,2289,2380,2477,2691,2943,3102,3224,3399,3521]
// ];
// $zmienneTestoweT = [
//     [3  ,  4  ,  3 ,   2   , 5 ,   7 ,   8   , 10 ,   5 ,   12],
//     [2  ,  2 ,   1  ,  1  ,  3  ,  4,    4 ,   5  ,  3,    6],
//     [5  ,  4 ,   5  ,  5  ,  4  ,  3 ,   2  ,  1,    4  ,  1],
//     [1  ,  3 ,   2  ,  4  ,  3  ,  5   , 7 ,   6  ,  7 ,   7],
//     [14  ,  12 ,   10  ,  8 ,   12  ,  15  ,  12  ,  10  ,  8 ,   6],
// ];

$return = [];
$return += ['zmienne' => $zmienne];

$tabY = $zmienne[0];
$srednia = srednia($zmienne);
$wariancja = wariancja($srednia, $zmienne, count($zmienne));
$odchylenie = odchylenie($wariancja, count($zmienne));
$wspolczynnik = wspolczynnik_zm($odchylenie, $srednia, count($zmienne));
$zmienne_do_modelu = sprawdz_zm($zmienne, $wspolczynnik);
$wsp_kor_r = wspolczynnik_kor_r($zmienne_do_modelu);
$wsp_kor_r0 = wspolczynnik_kor_r0($zmienne_do_modelu, $tabY);
$return+= ["r" => $wsp_kor_r];
$return+= ["r0" => $wsp_kor_r0];
$wartosc_graniczna = wartosc_graniczna(10,1.8595);
$tablica_grafu = tablica_grafu($wsp_kor_r ,$wartosc_graniczna, count($zmienne_do_modelu));
$return+= ["tablica_grafu" => $tablica_grafu];
$indeksy = indeks($wsp_kor_r0, $wsp_kor_r, $wartosc_graniczna);
$wybrane_x = korelacja($indeksy, $zmienne_do_modelu);

//rownanie
$X = new NumArray($wybrane_x);
$Y = new NumArray(array($tabY));
$XT = new NumArray(array_map(null, ...$X->getData()));
$XTX = new NumArray($wybrane_x);
$XTX = $XTX->dot($XT);
$XTY = new NumArray($Y->getData());
$XTY = $XTY->dot($XT);
$oXTX = new NumArray($XTX->getData());
$oXTX = LinAlg::inv($oXTX);
$QArr = new NumArray($XTY->getData());
$QArr->dot($oXTX);
$Q = $QArr->getData();
$rownanie = wysw_rownanie($Q, $indeksy);
$return+= ["rownanie" => $rownanie];
// echo $rownanie;

//Wariancja i odchylenie
$K = count($wybrane_x);
$YT = new NumArray(array_map(null, ...$Y->getData()));
$Y = new NumArray($tabY);
$YTY = new NumArray($YT->getData());
$YTY = $YTY->dot($YT);
$YTX = new NumArray($X->getData());;
$YTX = $YTX->dot($YT);
$YTXQ = new NumArray($QArr->getData());
$YTXQ = $YTXQ->dot($YTX);
$YTY = $YTY->getData();
$YTXQ = $YTXQ->getData();
$Su2 = 1 / (10 - $K) * ($YTY - $YTXQ[0]);
$Su = sqrt($Su2);
$return+= ["su2" => $Su2];
$return+= ["su" => $Su];

// bledy srednie 
$D2 = new NumArray($oXTX->getData());
$D2->dot($Su2);
$D2 = $D2->getData();
$sr_blad = sr_blad($indeksy, $wybrane_x, $D2);
// echo($sr_blad);
$return+= ["sr_blad" => $sr_blad];


//wspol zmienn losowej
$wsp_zm_los = $Su / (array_sum($tabY)/10);
$wsp_zm_los = (round($wsp_zm_los, 4) * 100);
// echo($wsp_zm_los.'%');
$return+= ["wsp_zm_los" => $wsp_zm_los];

//wsp determinacji
$wsp_det = wspolczynnik_determinacji($indeksy,$Q, $wybrane_x, $tabY);
// echo ($wsp_det);
$return+= ["wsp_det" => $wsp_det];

echo json_encode($return);


            





















?>


