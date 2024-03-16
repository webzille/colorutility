<?php

require 'vendor/autoload.php';

use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\LAB;
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
echo $NamedObject->viewColor();

$blackString = 'black';
$blackObject = SetColor::fromString($blackString);
echo $blackObject->asLAB()->viewColor("Converted from a non-websafe color format");

echo $RGBAObject . PHP_EOL . PHP_EOL;

$tetradicColors = $HexObject2->asHSL()->tetradicColors();
echo $HexObject2->viewColor("Tetradic Colors of");
foreach ($tetradicColors as $tetradicColor) {
    echo $tetradicColor->viewColor();
}
echo PHP_EOL;
$tetradicColors2 = $HexObject2->asLAB()->tetradicColors();
echo $HexObject2->viewColor("Tetradic Color in LAB (LAB converts to RGB since it isn't websafe)");
foreach ($tetradicColors2 as $tetradicColor2) {
    echo $tetradicColor2->viewColor();
}
echo PHP_EOL;
$hsla = $HexObject2->asHSLA();
echo $HexObject2->viewColor("For Reference");
echo $hsla->viewColor("The color");

$alphaRGBA = new RGBA(234, 21, 148, 0.5);
echo $alphaRGBA->viewColor("Transparency Here");
echo $alphaRGBA->asHSLA()->viewColor("As HSLA");
echo $alphaRGBA->asHEX()->viewColor("As HEX");
