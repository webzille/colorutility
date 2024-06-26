<?php

require 'vendor/autoload.php';

use Webzille\ColorUtility\Colors\HSV;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RYB;
use Webzille\ColorUtility\SetColor;

echo "<pre>";

// The color stops for the RYB color wheel (traditional painter's color wheel)
$colorWheel = [
    'Red'           => [255, 0,   0  ],
    'Red-Orange'    => [255, 255, 0  ],
    'Orange'        => [128, 255, 0  ],
    'Yellow-Orange' => [69,  255, 0  ],
    'Yellow'        => [0,   255, 0  ],
    'Yellow-Green'  => [0,   255, 69 ],
    'Green'         => [0,   255, 255],
    'Blue-Green'    => [0,   128, 255],
    'Blue'          => [0,   0,   255],
    'Blue-Violet'   => [128, 0,   255],
    'Violet'        => [255, 0,   255],
    'Red-Violet'    => [255, 0,   128]
];

$ryb = [];
foreach ($colorWheel as $colorName => $color) {
    $object = new RYB(...$color);
    $rgb = $object->asRGB();
    echo $rgb->viewColor($colorName);
    echo $rgb->findColorByAngle(0)->viewColor("Angle: 0;") . PHP_EOL;

    $ryb[$colorName] = $object;
}

echo "\n\nThe Color wheel\n\n";

$object = $ryb['Red'];
// $object = SetColor::fromString("rgb(255, 0, 0)")->setSpace(LAB::class);
echo $object->viewColor("Original Color");
$colors = [];
for ($i=0; $i <= 360; $i++) {
    $newAngle = $object->findColorByAngle($i);
    echo $newAngle->viewColor("Angle: $i; Color:");

    if ($i % 30 === 0 && $i !== 360) {
        $colors[$i] = $newAngle;
    }
}

echo "\n\nThe color at every 30 angles in the color wheel\n\n";
foreach ($colors as $angle => $colorStop) {
    echo $colorStop->viewColor($angle);
}

echo "\n\nColor stops of the RYB color wheel\n\n";
foreach ($ryb as $colorName => $colorStop) {
    echo $colorStop->viewColor($colorName);
}

echo PHP_EOL;

echo "Triadic Color Scheme for red: \n";
echo $ryb['Red']->viewColor();
foreach ($ryb['Red']->triadic() as $key => $scheme) {
    echo $scheme->viewColor("$key: ");
}

echo PHP_EOL;

echo "Tetradic Color Scheme for red: \n";
echo $ryb['Red']->viewColor();
foreach ($ryb['Red']->tetradic() as $key => $scheme) {
    echo $scheme->viewColor("$key: ");
}

echo PHP_EOL;

echo "Split Complementary Color Scheme for red: \n";
echo $ryb['Red']->viewColor();
foreach ($ryb['Red']->splitComplementary() as $key => $scheme) {
    echo $scheme->viewColor("$key: ");
}

echo PHP_EOL;

echo "Monochromatic Shades for red: \n";
foreach ($ryb['Red']->monochromaticShades() as $key => $shade) {
    echo $shade->viewColor("$key: ");
}

echo PHP_EOL;

echo "Monochromatic Tones for red: \n";
foreach ($ryb['Red']->asRGB()->monochromaticTones() as $key => $tone) {
    echo $tone->viewColor("$key: ");
}

echo PHP_EOL;

echo "The angle between orange and green is: " . $ryb['Orange']->calculateAngle($ryb['Green']);

echo PHP_EOL;

echo "\nLinear Deviance in RYB\n";
for ($i = 0; $i <= 100; $i++) {
    $newColor = $ryb['Red']->linearDeviance($i);
    echo "$newColor " . $newColor->viewColor("Deviance at {$i}%:");
}

echo PHP_EOL;

echo "\nComplementary to Red in RYB color space\n";

$complementaryRYB = $ryb['Red']->findColorByAngle(180);
echo "$complementaryRYB " . $complementaryRYB->viewColor();

echo PHP_EOL;

$lab = $ryb['Red']->asLAB();

echo "\nLinear Deviance in LAB\n";
for ($i = 0; $i <= 100; $i++) {
    $newColor = $lab->linearDeviance($i);
    echo "$newColor " . $newColor->viewColor("Deviance at {$i}%:");
}

echo PHP_EOL;

echo "\nComplementary to Red in LAB color space\n";
$complementaryLab = $lab->findColorByAngle(180);
echo "$complementaryLab " . $complementaryLab->viewColor();
