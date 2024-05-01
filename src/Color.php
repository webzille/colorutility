<?php

namespace Webzille\ColorUtility;

use Webzille\ColorUtility\Colors\CylindricalLAB;
use Webzille\ColorUtility\Colors\HEX;
use Webzille\ColorUtility\Colors\HSL;
use Webzille\ColorUtility\Colors\HSLA;
use Webzille\ColorUtility\Colors\LAB;
use Webzille\ColorUtility\Colors\RGB;
use Webzille\ColorUtility\Colors\RGBA;
use Webzille\ColorUtility\Colors\HSV;
use Webzille\ColorUtility\Colors\RYB;

abstract class Color {

    function __toString(): string
    {
        return $this->asString();
    }

    abstract function asArray();

    abstract function asString();

    abstract function isLight();

    public function isDark(): bool
    {
        return !$this->isLight();
    }

    abstract function white();

    abstract function black();

    public function asRGB(): RGB
    {
        return $this;
    }

    public function asRGBA(float $alpha): RGBA
    {
        return $this;
    }

    public function asLAB(): LAB
    {
        return $this;
    }

    public function asHEX(): HEX
    {
        return $this;
    }

    public function asCylindrical(): CylindricalLAB
    {
        return $this;
    }

    public function asHSL(): HSL
    {
        return $this;
    }

    public function asHSLA(float $alpha): HSLA
    {
        return $this;
    }

    public function asHSV(): HSV
    {
        return $this;
    }

    public function asRYB(): RYB
    {
        return $this;
    }

    public function viewColor(string $label = null): string
    {
        $isWebSafe = in_array(get_class($this), Colors::$websafe);

        $webSafeColor = $isWebSafe ? $this : $this->asRGB();

        $fontColor = $webSafeColor->isLight() ? $webSafeColor->black() : $webSafeColor->white();

        $label = trim("$label $webSafeColor");

        return "<span style=\"padding-inline: 3rem; background-color: $webSafeColor; color: $fontColor;\">$label</span>\n";
    }

    abstract function calculateAngle(Color $angle);

    abstract function currentAngle();

    abstract function digitalDistance(Color $color);

    abstract function findColorByDistance(float $distance);

    abstract function visibleDifference(Color $color);

    abstract function findColorByDifference(float $difference);

    abstract function findColorByAngle(float $angle);

    abstract function adjustShade(int $shade);

    abstract function linearDeviance(float $percent);

    public function angularDeviance(float $percent): self
    {
        $percent = ($percent > 200) ? ($percent - 200) / 100 : $percent / 100;

        return $this->findColorByAngle(180 * $percent);
    }

    public function complementary(): array
    {
        // Rotate the base color by 180 degrees (180°)
        return $this->findColorByAngle(180);
    }

    public function tetradic(): array
    {
        // Rotate the base color by 90 degrees (90°) for one color and 270 degrees (270°) for another
        return [
            $this->findColorByAngle(90),
            $this->findColorByAngle(180),
            $this->findColorByAngle(270)
        ];
    }

    public function splitComplementary(): array
    {
        // Rotate the base color by 150 degrees (150°) and 210 degrees (210°)
        return [
            $this->findColorByAngle(150),
            $this->findColorByAngle(210)
        ];
    }

    public function triadic(): array
    {
        // Rotate the base color by 120 degrees (120°) and 240 degrees (240°)
        return [
            $this->findColorByAngle(120),
            $this->findColorByAngle(240)
        ];
    }

    public function analogous(): array
    {
        // Rotate the base color by 30 degrees (30°) in both directions
        return [
            $this->findColorByAngle(30),
            $this->findColorByAngle(-30)
        ];
    }

    public function monochromaticTones(): array
    {
        // Generate a few different tones of the same color
        $monochromaticTones = [];

        for($tone = -25; $tone <= 25; $tone += 5) {
            $monochromaticTones[$tone] = $this->findColorByAngle($tone);
        }

        return $monochromaticTones;
    }

    public function monochromaticShades(): array
    {
        // Generate shades of the base color by adjusting the lightness
        $monochromaticShades = [];

        for($shade = 0; $shade <= 100; $shade += 5) {
            $monochromaticShades[$shade] = $this->adjustShade($shade);
        }

        return $monochromaticShades;
    }
}
