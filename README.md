# Color Utility Packager
A color utility package for whatever color practices you need.

You may set a color from string using the `SetColor` factory:

```PHP
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

echo $RGBAObject;
```

Any named color is converted to their RGB variant. Each object has a `__toString()` implementation which would print a valid string for CSS implementation. `__toString()` uses the `asString()` method as the string version of the object.

You could also find tetradic colors, monochromatic tones/shades, complementary, triadic, analogous and split complementary colors of a the current color object.

```PHP
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
```
You may easily convert between the available color spaces: (Going off of the original examples)
```PHP
$hsla = $HexObject2->asHSLA();
echo $HexObject2->viewColor("For Reference");
echo $hsla->viewColor("The color");
```

While `angularDeviance()` essentially does the same thing as `findColorByAngle()`, it uses a percent as the arguement instead of an angle. 100% is 180 degrees and 200% is full 360 degrees.

Transparency converts between colors that support them.

```PHP
$alphaRGBA = new RGBA(234, 21, 148, 0.5);
echo $alphaRGBA->viewColor("Transparency Here");
echo $alphaRGBA->asHSLA()->viewColor("As HSLA");
echo $alphaRGBA->asHEX()->viewColor("As HEX");
```
