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

## Color Space / Wheel

This package includes the following color spaces / wheels to be used in scheme creation.

- **RYB Traditional Painter:** This color wheel is based on the traditional painter’s perspective, where the primary colors are red, yellow, and blue. It's used extensively in art and design education, emphasizing how painters mix colors to achieve a broad spectrum. This model is ideal for applications that require a naturalistic approach to color mixing and harmony, as it aligns with traditional artistic techniques.
- **HSV (Hue, Saturation, Value):** HSV represents colors in terms of their hue, saturation, and value (brightness). Hue is the color type, saturation represents the intensity of the color, and value indicates the brightness. This model is particularly useful for applications needing intuitive color adjustments since it separates color-making components in a way that aligns with human perception of color.
- **LAB:** LAB color space includes three components - L for lightness, A and B for color spectrums from green to red and blue to yellow, respectively. It is designed to approximate human vision and is not dependent on how colors are created with light or pigments. This model is useful for achieving precise color manipulation and ensuring color consistency across different devices and viewing conditions.

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

You may set a color object from string from any of the websafe color formats (RGBA, RGB, HSLA, HSL, HEX and named colors) using the `SetColor::fromString(string $string)` factory.

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

### Going between color spaces / wheels

By default the package uses the **RYB Traditional Painter** color wheel for creating color schemes or for any of the color manipulating methods available. It is easy though to change the color wheel from RYB to either **HSV** or to **LAB** by passing the class string name to `setSpace()` method.

```php
$rgbColor = new RGB(255, 128, 69);
echo $rgbColor->setSpace(LAB::class)->findColorByAngle(180)->viewColor();  // rgb(0, 189, 255)

$rgbColor = new RGB(255, 128, 69);
echo $rgbColor->setSpace(HSV::class)->findColorByAngle(180)->viewColor();  // rgb(69, 196, 255)

// The default color space / wheel
$rgbColor = new RGB(255, 128, 69);
echo $rgbColor->setSpace(RYB::class)->findColorByAngle(180)->viewColor();  // rgb(69, 255, 122)

$rgbColor = new RGB(255, 128, 69);
echo $rgbColor->findColorByAngle(180)->viewColor();  // rgb(69, 255, 122)
```

You could also simply convert the color object from RGB to one of the color spaces like `RYB::class`, `LAB::class` or `HSV::class` instead of specifying for a specific color space.

```php
$rgbColor = new RGB(255, 128, 69);
echo $rgbColor->as(LAB::class)->findColorByAngle(180)->viewColor();
```

Which is the same as

```php
$rgbColor = new RGB(255, 128, 69);
echo $rgbColor->asLAB()->findColorByAngle(180)->viewColor();
```

If the color model you are working with has it's own methods for `findColorByAngle()` and the like, it will use it's own such methods instead of what `$this->colorSpace` is set to. For instance:

```php
$labColor = new LAB(53, 80, 67);
echo $labColor->setSpace(RYB::class)->findColorByAngle(180);  // LAB(53, -80, -67) // Light-blue
```

In the above example, even though I set the color space to be used to be in `RYB::class`, since LAB color model has that method of it's own it will use it, resulting in it using it's own color space. To manipulate colors in LAB while utilizing RYB color space, you would need to explicitly convert between the methods

```php
$labColor = new LAB(53, 80, 67);
echo $labColor->asRYB()->findColorByAngle(180)->backTo($labColor);  // LAB(87, -86, 83) // Green
// or

echo $labColor->asRYB()->findColorByAngle(180)->asLAB();  // LAB(87, -86, 83) // Green
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

### Manipulating Colors

Adjust color properties, blend colors, or calculate color harmonies.

```php
$complementaryRGB = $rgbColor->complementary(); // Green

$weight = 0.5;
$blendedRYB = $rybColor->blendColors(new RYB(0, 0, 255), $weight); // Blend red and blue in RYB
```

Blending colors are done only within RYB color space at the moment. You may convert the color objects to RYB, blend them and then convert the resulting color back to your own color model you using. The weight is a number between 0 and 1 that determines how the colors blend. If weight is 0, the blend will show only the first color. If weight is 1, it will show only the second color. The closer the weight is to 1, the more the blend will favor the second color. This method smooths out the transition between colors, making the blend gradual and more natural-looking.

```php
$darkerColor = $rgbColor->adjustShade(50); // 50% of the current shade
$brighterColor = $rgbColor->adjustShade(150); // 50% brighter than it's current shade
$sameShade - $rgbColor->adjustShade(100); // No change; 100% of it's current shade
```

All colors can be brighter then they are, even fully saturated yellow (`rgb(255, 255, 0)`). You could adjust the shade of the color (lighten it or darken it) using a percentage relative to the current brightness/shade of the color.

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

### Converting Colors

Convert colors between different models to fit the context of your application.

```php
$hexColor = $rgbColor->asHEX();
echo $hexColor; // Outputs HEX code for red
```

If you ever need to use a different color space which would require you to convert a color from one color model to another and than need the color model converted back to what it was before you converted it, you could chain the `backTo()` method to the end of the method chains.

```php
// Default websafe color to view is RGB
$hslColor = new HSL(195.51382638999, 100, 50);
echo $hslColor->asLAB()->findColorByAngle(180)->viewColor();  // rgb(228, 163, 97)

// Making sure we view the color in HSL format after using the color in LAB color space
$hslColor = new HSL(195.51382638999, 100, 50);
echo $hslColor->asLAB()->findColorByAngle(180)->backTo($hslColor)->viewColor();  // hsl(30.533318540464, 70.332302060189%, 63.65760628305%)
```

That is good if the color object would be dynamically set and the result needs to be the same as what it was before any conversions took place. Otherwise you could simply chain the proper conversion method to get back to the original color model.

```php
// Making sure we view the color in HSL format after using the color in LAB color space
$hslColor = new HSL(195.51382638999, 100, 50);
echo $hslColor->asLAB()->findColorByAngle(180)->asHSL()->viewColor();  // hsl(30.533318540464, 70.332302060189%, 63.65760628305%)
```

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
