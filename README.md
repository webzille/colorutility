# Webzille Color Utility

The Webzille Color Utility is a comprehensive PHP package designed for advanced color manipulation across multiple color models, including CylindricalLAB, HEX, HSL, HSLA, HSV, LAB, RGB, RGBA and RYB. This powerful utility allows developers to perform various operations on colors, such as conversions, calculations, and transformations, using an intuitive and flexible API.

## Features

- **Multiple Color Models:** Work with a variety of color models to meet the needs of different graphic and application requirements.
- **Color Manipulation:** Easily manipulate color properties, including adjustments, blending, and transformations.
- **Color Comparisons:** Compare colors to find differences, distances, and similarities, both visually and through calculated metrics.
- **Advanced Calculations:** Calculate angles, distances, and deviance to understand color relationships better.
- **Color Harmonies:** Generate color schemes based on harmonic rules like complementary, analogous, triadic, and more.

## Installation

To use the Webzille Color Utility in your project, include it via Composer:

```bash
composer require webzille/colorutility
```

## Usage

### Basic Usage

After including the package, you can start by creating color objects in any supported color space.

```php
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RYB;
use Webzille\ColorUtility\Colors\LAB;

$rgbColor = new RGB(255, 0, 0);  // Red in RGB
$rybColor = new RYB(255, 0, 0);  // Red in RYB
$labColor = new LAB(53.23288178584245, 80.10930952982204, 67.22006831026425);  // Red in LAB
```

### Setting color from string factory

You may set a color object from string from any of the websafe color formats (RGBA, RGB, HSLA, HSL, HEX and named colors) using the `SetCollor::fromString(string $string)` factory.

```php
use Webzille\ColorUtility\SetColor;

$HexString = "#ffcc00";
$HexObject = SetColor::fromString($HexString);

$RGBString = "rgb(255, 0, 0);";
$RGBObject = SetColor::fromString($RGBString);

$RGBAString = "rgba(153, 255, 46, 0.4)";
$RGBAObject = SetColor::fromString($RGBAString);

$HSLAString = "hsla(0, 41%, 51%, 0.3)";
$HSLAObject = SetColor::fromString($HSLAString);

$HSLString = "hsl(146, 41%, 51%)";
$HSLObject = SetColor::fromString($HSLString);

$NamedString = "steelblue";
$NamedObject = SetColor::fromString($NamedString);
```

### Catching colors from string

You can catch color from strings for whatever parsing you need.

```php
$value = ".cssRuleset {
    font-size: 1.3em;
    color: #ffcc00;
    border: 1px solid rgb(124, 20, 0);
    background-color: transparent;
}";

$transparency = true;
preg_match_all(Colors::getMatchingPattern($transparency), $value, $matches);

print_r($matches[1]);

// Array
// (
//     [0] => #ffcc00
//     [1] => rgb(124, 20, 0)
//     [2] => transparent
// )
```

If you set `$transparency` to false, than it would ignore the 'transparent' colors.

### Viewing color

The color class provides a way to view a sample of the color that the object holds. The method accepts an optional string parameter that allows you to add a label to the color sample to help keep track of each color object.

```php
echo $NamedString->viewColor("The optional label");

echo $RGBAObject->viewColor();
```

The method detects if the object is in a websafe color format and converts to RGB in case the color object is not in a websafe color format (like LAB or RYB).

### Converting Colors

Convert colors between different models to fit the context of your application.

```php
$hexColor = $rgbColor->asHEX();
echo $hexColor; // Outputs HEX code for red
```

### Manipulating Colors

Adjust color properties, blend colors, or calculate color harmonies.

```php
$complementaryRGB = $rgbColor->complementary(); // Green
$blendedRYB = $rybColor->blendColors(new RYB(0, 0, 255), 0.5); // Blend red and blue in RYB
```

### Analyzing Colors

Calculate angles, distances, and perform advanced analysis like checking if the color is light or dark.

```php
$isLight = $rgbColor->isLight(); // Returns true if the color is considered light
$angleBetween = $rybColor->calculateAngle(new RYB(0, 255, 0)); // Calculate angle between red and yellow in RYB
```

### Generating Color Schemes

Generate color schemes based on predefined harmonies.

```php
$triadicScheme = $rgbColor->triadic(); // Returns an array of RGB objects in a triadic scheme
```

## Color Conversions

The Webzille Color Utility provides extensive support for converting between various color models, allowing seamless transitions across different color formats. This feature is crucial for applications that need to work with multiple color specifications or require specific color manipulations that are easier in certain models.

### Conversion Examples

Here’s how you can convert between different color formats using the Webzille Color Utility:

```php
// Assuming $rgbColor, $rybColor, and $labColor are already defined as instances of their respective color classes

// RGB to other formats
$hexFromRGB = $rgbColor->asHEX();
$hslFromRGB = $rgbColor->asHSL();
$labFromRGB = $rgbColor->asLAB();

// RYB to RGB (and then to other formats)
$rgbFromRYB = $rybColor->asRGB();
$hexFromRYB = $rybColor->asHEX();
$hslFromRYB = $rybColor->asHSL();

// LAB to RGB and to HEX
$rgbFromLAB = $labColor->asRGB();
$hexFromLAB = $labColor->asHEX();
```

Every color format has a method to convert to any of the other available formats.

## Contributing

Contributions to the Webzille Color Utility are welcome! Please ensure that you submit pull requests to the development branch.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
