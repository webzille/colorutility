<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class RYB extends Color {

    private string $r;

    private string $y;

    private string $b;

    protected array $colorWheel = [
     // [r,   y,   b, angle]
        [255, 0,   0,   0  ],   // 0   Red
        [107, 25,  75,  30 ],   // 30  Red-Purple
        [135, 0,   175, 60 ],   // 60  Purple
        [61,  0,   165, 90 ],   // 90  Purple-Blue
        [0,   0,   255, 120],   // 120 Blue
        [3,   87,  206, 150],   // 150 Blue-Green
        [0,   255, 255, 180],   // 180 Green
        [43,  234, 69,  210],   // 210 Green-Yellow
        [0,   255, 0,   240],   // 240 Yellow
        [79,  250, 0,   270],   // 270 Yellow-Orange
        [167, 250, 0,   300],   // 300 Orange
        [250, 167, 0,   330],   // 330 Orange-Red
    ];

    function __construct($r, $y, $b)
    {
        $this->r = $r;

        $this->y = $y;

        $this->b = $b;
    }

    public function asArray(): array
    {
        return [$this->r, $this->y, $this->b];
    }

    public function asString(): string
    {
        return "RYB({$this->r}, {$this->y}, {$this->b})";
    }

    public function isLight(): bool
    {
        return $this->asRGB()->isLight();
    }

    public function white(): self
    {
        return new RYB(255, 255, 255);
    }

    public function black(): self
    {
        return new RYB(0, 0, 0);
    }

    public function calculateAngle(Color $color): float
    {
        $angle1 = $this->currentAngle();
        $angle2 = $color->asRYB()->currentAngle();

        return fmod(abs($angle1 - $angle2), 360);
    }

    public function digitalDistance(Color $color): float
    {
        return $this->asLAB()->digitalDistance($color);
    }

    public function visibleDifference(Color $color): float
    {
        return $this->asLAB()->visibleDifference($color);
    }

    public function currentAngle(): float
    {
        $bestDistance = PHP_FLOAT_MAX;
        $newColor = new RYB(255, 0, 0);
        $bestAngle = 0;

        foreach ($this->colorWheel as $colorData) {
            $colorObject = new RYB(...$colorData);
            $distance = $this->visibleDifference($this->normalizeColor($colorObject));

            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $bestAngle = $colorData[3];
            }
        }

        $segmentIndex = floor($bestAngle / (360 / count($this->colorWheel)));

        if ($bestAngle === 0 || $bestAngle === 360) {
            $segmentIndex = count($this->colorWheel) - 1;
            $weight = 0;
        }
        
        $color1 = new RYB(...$this->colorWheel[$segmentIndex]);
        $color2 = new RYB(...$this->colorWheel[($segmentIndex + 1) % count($this->colorWheel)]);
        $currentDistance = PHP_INT_MAX;
        
        $tolerance = 1;
        $step = $i = 0.05;
        while ($bestDistance > $tolerance && $i < 360) {
            $testAngle = ($bestAngle + $i);
            if ($testAngle > 360) {
                $testAngle -= 360;
            }

            $weight = ((int) $testAngle % (360 / count($this->colorWheel))) / (360 / count($this->colorWheel));
            $newColor = $this->blendColors($color1, $color2, $weight);
            $currentDistance = $this->visibleDifference($this->normalizeColor($newColor));
            
            if ($currentDistance < $bestDistance) {
                $bestDistance = $currentDistance;
                $bestAngle = $testAngle;
            }
            $i += $step;
        }

        return $bestAngle;
    }

    public function findColorByAngle(float $angle): RYB
    {
        if ($this->isGrayscale()) {
            return clone $this;
        }

        $angle = $this->currentAngle() + $angle;

        while ($angle >= 360) {
            $angle -= 360;
        }

        $segmentIndex = floor($angle / (360 / count($this->colorWheel)));
        $weight = fmod($angle, 360 / count($this->colorWheel)) / (360 / count($this->colorWheel));

        if ($angle === 0 || $angle === 360) {
            $segmentIndex = count($this->colorWheel) - 1;
            $weight = 0;
        }
        
        $color1 = new RYB(...$this->colorWheel[$segmentIndex]);
        $color2 = new RYB(...$this->colorWheel[($segmentIndex + 1) % count($this->colorWheel)]);
        
        $newColor = $this->blendColors($color1, $color2, $weight);
        return $this->normalizeColor($newColor);
    }

    public function normalizeColor(Color $color): RYB
    {
        $colorHSL = $color->asHSL();
        $currentHSL = $this->asHSL();

        $deltaS = abs($colorHSL->getSaturation() - $currentHSL->getSaturation());
        $saturation = $colorHSL->getSaturation() - ($deltaS / 2);

        $newHSL = new HSL($colorHSL->getHue(), $saturation, $currentHSL->getLightness());

        return $newHSL->asRYB();
    }

    public function blendColors(RYB $color1, RYB $color2, float $weight): RYB
    {
        $red = $color1->getRed() * (1 - $weight) + $color2->getRed() * $weight;
        $yellow = $color1->getYellow() * (1 - $weight) + $color2->getYellow() * $weight;
        $blue = $color1->getBlue() * (1 - $weight) + $color2->getBlue() * $weight;

        return new RYB($red, $yellow, $blue);
    }

    public function isGrayscale(): bool
    {
        return abs($this->r - $this->y) === 0 && abs($this->y - $this->b) === 0 && abs($this->b - $this->r) === 0;
    }

    public function findColorAtDifference(float $difference): self
    {
        return $this->asLAB()->findColorAtDifference($difference)->asRYB();
    }

    public function findColorAtDistance(float $distance): self
    {
        return $this->asLAB()->findColorAtDistance($distance)->asRYB();
    }

    public function findColorByShade(int $shade): self
    {
        return $this->asLAB()->findColorByShade($shade)->asRYB();
    }

    public function linearDeviance(float $percent): self
    {
        return $this->asLAB()->linearDeviance($percent)->asRYB();
    }

    public function angularDeviance(float $percent): self
    {
        return $this->asLAB()->angularDeviance($percent)->asRYB();
    }

    public function getRed(): string
    {
        return $this->r;
    }

    public function getYellow(): string
    {
        return $this->y;
    }

    public function getBlue(): string
    {
        return $this->b;
    }

    public function asRGB(): RGB
    {
        list($r, $y, $b) = $this->asArray();

        $w = min($r, $y, $b);
        $r -= $w;
        $y -= $w;
        $b -= $w;

        $my = max($r, $y, $b);

        $g = min($y, $b);
        $y -= $g;
        $b -= $g;

        if ($b && $g) {
            $b *= 2.0;
            $g *= 2.0;
        }

        $r += $y;
        $g += $y;

        $mg = max($r, $g, $b);
        if ($mg) {
            $n = $my / $mg;
            $r *= $n;
            $g *= $n;
            $b *= $n;
        }

        $r += $w;
        $g += $w;
        $b += $w;

        return new RGB($r, $g, $b);
    }

    public function asRGBA(float $alpha = 1): RGBA
    {
        return $this->asRGB()->asRGBA($alpha);
    }

    public function asLAB(): LAB
    {
        return $this->asRGB()->asLAB();
    }

    public function asCylindrical(): CylindricalLAB
    {
        return $this->asLAB()->asCylindrical();
    }

    public function asHSL(): HSL
    {
        return $this->asRGB()->asHSL();
    }

    public function asHSLA(float $alpha = 1): HSLA
    {
        return $this->asRGB()->asHSLA($alpha);
    }

    public function asHSV(): HSV
    {
        return $this->asRGB()->asHSV();
    }

    public function asHEX(): HEX
    {
        return $this->asRGB()->asHEX();
    }
}
