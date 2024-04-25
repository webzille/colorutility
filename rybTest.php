<?php

require 'vendor/autoload.php';

use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;
use Webzille\ColorUtility\Colors\RYB;
use Webzille\ColorUtility\SetColor;

echo "<pre>";

$object = new rgb(255, 0, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(199, 21, 133);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(148, 0, 211);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(138, 43, 226);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(0, 0, 255);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(0, 137, 139);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(0, 128, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(173, 255, 47);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(255, 255, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(255, 215, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(255, 165, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(200, 76, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

echo "Color stops of the RYB color wheel";

echo PHP_EOL;

$Red = new RYB(255, 0, 0);
echo $Red->viewColor('Red');
$RedViolet = new RYB(199, 21, 133);
echo $RedViolet->viewColor('Red-Violet');
$Violet = new RYB(148, 0, 211);
echo $Violet->viewColor('Violet');
$BlueViolet = new RYB(138, 43, 226);
echo $BlueViolet->viewColor('Blue-Violet');
$Blue = new RYB(0, 0, 255);
echo $Blue->viewColor('Blue');
$BlueGreen = new RYB(0, 69, 139);
echo $BlueGreen->viewColor('Blue-Green');
$Green = new RYB(0, 128, 128);
echo $Green->viewColor('Green');
$YellowGreen = new RYB(47, 255, 129);
echo $YellowGreen->viewColor('Yellow-Green');
$Yellow = new RYB(0, 255, 0);
echo $Yellow->viewColor('Yellow');
$YellowOrange = new RYB(47, 255, 0);
echo $YellowOrange->viewColor('Yellow-Orange');
$Orange = new RYB(139, 255, 0);
echo $Orange->viewColor('Orange');
$RedOrange = new RYB(200, 123, 0);
echo $RedOrange->viewColor('Red-Orange');

echo "\n\n\nThe Color wheel\n\n";

$object = SetColor::fromString("rgb(255, 0, 0)");
echo $object->viewColor("Current Color:");

$colors = [];
for ($i=0; $i <= 360; $i++) {
    $newAngle = $object->findColorByAngle($i);
    echo $newAngle->viewColor("Angle: $i; Color:");
}

echo PHP_EOL;

$object = new rgb(154, 44, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(180)->viewColor("Angle: 180; Color:");
echo PHP_EOL;

$colorWheel = [
    'Red'          => [255, 0, 0],
    'Red-Orange'   => [255, 83, 0],
    'Orange'       => [255, 165, 0],
    'Yellow-Orange' => [255, 215, 0],
    'Yellow'       => [255, 255, 0],
    'Yellow-Green' => [173, 255, 47],
    'Green'        => [0, 128, 0],
    'Blue-Green'   => [0, 139, 139],
    'Blue'         => [0, 0, 255],
    'Blue-Violet'  => [138, 43, 226],
    'Violet'       => [148, 0, 211],
    'Red-Violet'   => [199, 21, 133]
];
