<?php

require 'vendor/autoload.php';

use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\LAB;

echo "<pre>";

$color1 = new LAB(50, -52, -25);
$color2 = new LAB(50, -52, 25);
$color3 = new LAB(50, 52, 25);
$color4 = new LAB(50, 52, -25);
$color4AsHEX = $color4->asHEX();

echo PHP_EOL . $color4AsHEX->viewColor("Monochromatic Shades for");
foreach($color4AsHEX->monochromaticShadeColors() as $shadeHEX) {
    echo $shadeHEX->viewColor();
}

echo PHP_EOL . $color2->viewColor("Monochromatic Shades for");
foreach($color2->monochromaticShadeColors() as $shade) {
    echo $shade->viewColor("RGB (". $shade->asRGB() .")");
}

$distance = $color1->visibleDifference($color2);
echo PHP_EOL . $distance . PHP_EOL . PHP_EOL;
echo $color4->viewColor("Reference Color");
$linearColor = $color4->linearDeviance($distance);
echo $linearColor->viewColor("Linear Color");
echo $linearColor->visibleDifference($color4) . PHP_EOL;
$angularColor = $color4->angularDeviance($distance);
echo $angularColor->viewColor("Angular Color");
echo $angularColor->visibleDifference($color4) . PHP_EOL;
echo $color4->findColorByAngle($distance)->viewColor("'$distance' Degrees");
$ref = $color4;
$difference = 20;
echo $ref->viewColor("The reference color");
$distance = $ref->findColorAtDifference($difference);
echo $distance->viewColor("Distance Color");
echo $distance->visibleDifference($ref) . PHP_EOL;
echo "Expected distance is: $difference\n";

$hex = new HEX('fff');
echo $hex->viewColor('White HEX');
echo $hex->asLAB() . PHP_EOL;
$cylindrical = $hex->asCylindrical();
echo $cylindrical . PHP_EOL;