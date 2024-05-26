<?php

require 'vendor/autoload.php';

use Webzille\ColorUtility\SetColor;

$seed = rand(1, 9999999999);

[$color, $seed] = SetColor::random($seed);

echo "<p>Seed: $seed</p>";
echo "<p>Base Color</p>";
echo $color->viewColor();

echo "<p>Complementary</p>";
$complementary = [$color, $color->complementary()];
foreach ($complementary as $colorVariant) {
    echo $colorVariant->viewColor();
}

echo "<p>Tetradic</p>";
$tetradic = $color->tetradic();
echo $color->viewColor();
foreach ($tetradic as $colorVariant) {
    echo $colorVariant->viewColor();
}

echo "<p>Split Complementary</p>";
$splitComplementary = $color->splitComplementary();
echo $color->viewColor();
foreach ($splitComplementary as $colorVariant) {
    echo $colorVariant->viewColor();
}

echo "<p>Triadic</p>";
$triadic = $color->triadic();
echo $color->viewColor();
foreach ($triadic as $colorVariant) {
    echo $colorVariant->viewColor();
}

echo "<p>Analogous</p>";
$analogous = $color->analogous();
echo $color->viewColor();
foreach ($analogous as $colorVariant) {
    echo $colorVariant->viewColor();
}

echo "<p>Monochromatic Tones</p>";
$tones = $color->monochromaticTones();
foreach ($tones as $colorVariant) {
    echo $colorVariant->viewColor() . '<br>';
}

echo "<p>Monochromatic Tones</p>";
$shades = $color->monochromaticShades();
foreach ($shades as $colorVariant) {
    echo $colorVariant->viewColor() . '<br>';
}