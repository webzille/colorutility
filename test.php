<?php

require 'vendor/autoload.php';

use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;
use Webzille\ColorUtility\SetColor;

echo "<pre>";

$HexString = "#ffcc00";
$HexObject = SetColor::fromString($HexString);
echo $HexObject->viewColor("Hex Color from String");

$HexString2 = "aa77dd";
$HexObject2 = SetColor::fromString($HexString2);
echo $HexObject2->viewColor();

$RGBString = "rgb(255, 0, 0);";
$RGBObject = SetColor::fromString($RGBString);
echo $RGBObject->viewColor();

$RGBAString = "rgba(153, 255, 46, 0.4)";
$RGBAObject = SetColor::fromString($RGBAString);
echo $RGBAObject->viewColor();

$HSLAString = "hsla(0, 41%, 51%, 0.3)";
$HSLAObject = SetColor::fromString($HSLAString);
echo $HSLAObject->viewColor();

$HSLString = "hsl(146, 41%, 51%)";
$HSLObject = SetColor::fromString($HSLString);
echo $HSLObject->viewColor();

$NamedString = "steelblue";
$NamedObject = SetColor::fromString($NamedString);
echo $NamedObject->viewColor("steelblue");

$blackString = 'black';
$blackObject = SetColor::fromString($blackString);
echo $blackObject->asLAB()->viewColor("Converted from a non-websafe color format");

echo $RGBAObject . PHP_EOL . PHP_EOL;

$tetradicColors = $HexObject2->asHSL()->tetradic();
echo $HexObject2->asHSL()->viewColor();
echo $HexObject2->viewColor("Tetradic Colors of");
foreach ($tetradicColors as $tetradicColor) {
    echo $tetradicColor->viewColor();
}

$tet1 = "hsl(0, 60%, 67%)";
$tet2 = "hsl(90, 60%, 67%)";
$tet3 = "hsl(180, 60%, 67%)";

$tob1 = SetColor::fromString($tet1);
$tob2 = SetColor::fromString($tet2);
$tob3 = SetColor::fromString($tet3);

echo $tob1->viewColor();
echo $tob2->viewColor();
echo $tob3->viewColor();

echo PHP_EOL;
$tetradicColors2 = $HexObject2->asLAB()->tetradic();
echo $HexObject2->viewColor("Tetradic Color in LAB (LAB converts to RGB since it isn't websafe)");
echo $HexObject2->asHSL()->viewColor();
foreach ($tetradicColors2 as $tetradicColor2) {
    echo $tetradicColor2->asHSL()->viewColor();
}
echo PHP_EOL;
$white = 'rgb(255, 0, 0)';
$whiteObject = SetColor::fromString($white);
$whiteRGB = $whiteObject->asHSL();
$whiteRGBComplement = $whiteRGB->findColorByAngle(180);
echo $whiteObject->viewColor();
echo $whiteRGBComplement->viewColor("The complement to $white is");

$comt = "hsl(111.63, 3.15%, 36.5%)";
$como = SetColor::fromString($comt);

echo $whiteObject->visibleDifference($whiteRGBComplement);
echo PHP_EOL;
$hsla = $HexObject2->asHSLA();
echo $HexObject2->viewColor("For Reference");
echo $hsla->viewColor("The color");

$alphaRGBA = new RGBA(234, 21, 148, 0.5);
echo $alphaRGBA->viewColor("Transparency Here");
echo $alphaRGBA->asHSLA()->viewColor("As HSLA");
echo $alphaRGBA->asHEX()->viewColor("As HEX");

$namedColors = require "./src/NamedColorsLookupTable.php";

echo PHP_EOL;

$colorString = 'turquoise';
$colorObject = SetColor::fromString($colorString);
echo $colorObject->viewColor('The original color:');

echo PHP_EOL;

$hexObject = $colorObject->asHEX();
$rgbObject = $colorObject->asRGB();
$rgbaObject = $colorObject->asRGBA();
$hslObject = $colorObject->asHSL();
$hslaObject = $colorObject->asHSLA();

echo $hexObject . PHP_EOL;
echo $hexObject->viewColor();
echo $rgbObject . PHP_EOL;
echo $rgbObject->viewColor();
echo $rgbaObject . PHP_EOL;
echo $rgbaObject->viewColor();
echo $hslObject . PHP_EOL;
echo $hslObject->viewColor();
echo $hslaObject . PHP_EOL;
echo $hslaObject->viewColor();

echo PHP_EOL;

echo $hexObject . PHP_EOL;
echo $hexObject->asHSV()->viewColor("From HEX");
echo $rgbObject . PHP_EOL;
echo $rgbObject->asHSV()->viewColor("From RGB");
echo $rgbaObject . PHP_EOL;
echo $rgbaObject->asHSV()->viewColor("From RGBA");
echo $hslObject . PHP_EOL;
echo $hslObject->asHSV()->viewColor("From HSL");
echo $hslaObject . PHP_EOL;
echo $hslaObject->asHSV()->viewColor("From HSLA");

echo PHP_EOL;

$colorObject = SetColor::fromString($colorString);
$colorHSV = $colorObject->asHSV();
echo $colorHSV;
echo $colorHSV->viewColor("The $colorString from HSV:");

$colorHEX = $colorHSV->asHEX();
echo $colorHEX;
echo $colorHEX->viewColor("The $colorString from HSV");

$colorHSL = $colorHSV->asHSL();
echo $colorHSL;
echo $colorHSL->viewColor("The $colorString from HSV");

$colorLAB = $colorHSV->asLAB();
echo $colorLAB;
echo $colorLAB->viewColor("The $colorString from HSV");

$angledColor = $colorHSV->findColorByAngle(180);
echo $angledColor->viewColor("The complementary color to $colorString is:");
echo $colorHSV->visibleDifference($angledColor);

echo PHP_EOL;

echo $colorHSV->calculateAngle($angledColor);
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;

$string = "yellow";
$object = SetColor::fromString($string);

$string2 = "blue";
$object2 = SetColor::fromString($string2);

echo $object->viewColor("Main color");
$lab = $object->asLAB();
echo $lab . PHP_EOL;

$distance = $object->digitalDistance($object2);

echo $distance . PHP_EOL;

$newColor = $object->findColorAtDistance(60);
echo $newColor->viewColor("The resulting color");
echo $newColor->asLAB();

$newDistance = $object->digitalDistance($newColor);

echo $newDistance . PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;

$lab = new LAB(91.11, -128, -128);
echo "Orignal LAB: $lab\n";
$rgb = $lab->asRGB();
echo "Converted to RGB: $rgb\n";
echo PHP_EOL;
$rgb2 = new RGB(0, 255, 255);
echo "The RGB: $rgb2\n";
$lab2 = $rgb2->asLAB();
echo "Converted to LAB: $lab2\n";


$string = "red";
$difference = 20;
$object = SetColor::fromString($string);
echo $object->viewColor("The original ($string) color:");
$newColor = $object->findColorAtDifference($difference);
echo $newColor->viewColor("The resulting color at difference ($difference) from $string:");
$difference = $newColor->visibleDifference($object);
echo "The resulting difference between the new color and the original color is: $difference\n";
