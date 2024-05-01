<?php

require 'vendor/autoload.php';

use Webzille\ColorUtility\Colors\RYB;
use Webzille\ColorUtility\SetColor;

echo "<pre>";

// The color stops for the RYB color wheel (traditional painter's color wheel)
$colorWheel = [
    'Red'           => [255, 0,   0  ],
    'Red-Orange'    => [255, 128, 0  ],
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

$object = $ryb['Red']->asRGB();
// $object = SetColor::fromString("rgb(244, 82, 255");
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

echo "The angle between orange and green is: " . $ryb['Orange']->calculateAngle($ryb['Green']);
