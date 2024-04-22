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

$object = new rgb(167, 25, 75);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(135, 0, 175);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(61, 0, 165);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(0, 0, 255);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(3, 146.29411764706, 206);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(0, 255, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(210, 235, 45);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(255, 255, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(250, 189.96960486322, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(250, 150, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

$object = new rgb(250, 94.000000000002, 0);
echo $object->viewColor("Current Color:");
echo $object->findColorByAngle(0)->viewColor("Angle: 0; Color:");
echo PHP_EOL;

echo "Color stops of the RYB color wheel";

echo PHP_EOL;

$red = new RYB(255, 0, 0);
echo $red->viewColor("Red:");
$redPurple = new RYB(167, 25, 75);
echo $redPurple->viewColor("Red-Purple:");
$purple = new RYB(135, 0, 175);
echo $purple->viewColor("Purple:");
$purpleBlue = new RYB(61, 0, 165);
echo $purpleBlue->viewColor("Purple-Blue:");
$blue = new RYB(0, 0, 255);
echo $blue->viewColor("Blue:");
$blueGreen = new RYB(3, 87, 206);
echo $blueGreen->viewColor("Blue-Green:");
$green = new RYB(0, 255, 255);
echo $green->viewColor("Green:");
$greenYellow = new RYB(45, 235, 70);
echo $greenYellow->viewColor("Green-Yellow:");
$yellow = new RYB(0, 255, 0);
echo $yellow->viewColor("Yellow:");
$yellowOrange = new RYB(79, 250, 0);
echo $yellowOrange->viewColor("Yellow-Orange:");
$orange = new RYB(167, 250, 0);
echo $orange->viewColor("Orange:");
$orangeRed = new RYB(250, 167, 0);
echo $orangeRed->viewColor("Orange-Red:");

echo "\n\n\nLooped angles\n\n\n";

$object = SetColor::fromString("rgb(255, 0, 0)");
echo $object->viewColor("Current Color:");

for ($i=0; $i <= 360; $i++) {
    echo $object->findColorByAngle($i)->viewColor("Angle: $i; Color:");
}
