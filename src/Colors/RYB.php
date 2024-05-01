<?php

namespace Webzille\ColorUtility\Colors;

use Webzille\ColorUtility\Color;

class RYB extends Color {

    private string $r;

    private string $y;

    private string $b;

    protected array $colorWheel = [
    //  [r,   y,   b, angle]
        [255, 0,   0,   0  ],   // 0   Red
        [255, 255, 0,   30 ],   // 30  Red-Orange
        [128, 255, 0,   60 ],   // 60  Orange
        [69,  255, 0,   90 ],   // 90  Yellow-Orange
        [0,   255, 0,   120],   // 120 Yellow
        [0,   255, 69,  150],   // 150 Yellow-Green
        [0,   255, 255, 180],   // 180 Green
        [0,   128, 255, 210],   // 210 Blue-Green
        [0,   0,   255, 240],   // 240 Blue
        [128, 0,   255, 270],   // 270 Blue-Violet
        [255, 0,   255, 300],   // 300 Violet
        [255, 0,   128, 330],   // 330 Red-Violet
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
        list($r, $y, $b) = $color->asRYB()->asArray();

        $deltaR = $this->r - $r;
        $deltaY = $this->y - $y;
        $deltaB = $this->b - $b;

        return sqrt($deltaR ** 2 + $deltaY ** 2 + $deltaB ** 2);
    }

    public function visibleDifference(Color $color): float
    {
        return $this->asLAB()->visibleDifference($color);
    }

    public function currentAngle(): float
    {
        $bestDistance = PHP_FLOAT_MAX;
        $bestAngle = 0;

        foreach ($this->colorWheel as $index => $colorData) {
            $color1 = new RYB(...$colorData);
            $nextIndex = ($index + 1) % count($this->colorWheel);
            $color2 = new RYB(...$this->colorWheel[$nextIndex]);

            if ($this->normalizeColor($color1)->digitalDistance($this) < 1) {
                return $colorData[3];
            }

            for ($angle = $colorData[3]; $angle < $colorData[3] + 30; $angle += 1) {
                $weight = $this->getWeight($angle, $index);
                $blendedColor = $color1->blendColors($color2, $weight);
                $distance = $this->normalizeColor($blendedColor)->digitalDistance($this);

                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestAngle = $angle;
                }
            }
        }

        return $bestAngle;
    }

    public function findColorByAngle(float $angle): self
    {
        if ($this->isGrayScale()) {
            return clone $this;
        }

        $angle = fmod($this->currentAngle() + $angle, 360);
        $angle = $angle < 0 ? $angle + 360 : $angle;

        [$segmentIndex, $nextSegmentIndex] = $this->getSegments($angle);
        $weight = $this->getWeight($angle, $segmentIndex);

        $color1 = new RYB(...$this->colorWheel[$segmentIndex]);
        $color2 = new RYB(...$this->colorWheel[$nextSegmentIndex]);

        $newColor = $color1->blendColors($color2, $weight);
        return $this->normalizeColor($newColor);
    }

    private function getWeight(float $angle, int $segmentIndex): float
    {
        if ($angle === 0 || $angle === 360) {
            return 0;
        }
        
        return ($angle - $this->colorWheel[$segmentIndex][3]) / (360 / count($this->colorWheel));
    }

    private function getSegments(float $angle): array
    {
        $segmentIndex = floor($angle / (360 / count($this->colorWheel)));
        $nextSegmentIndex = ($segmentIndex + 1) % count($this->colorWheel);

        if ($angle === 0 || $angle === 360) {
            $segmentIndex = count($this->colorWheel) - 1;
        }

        return [$segmentIndex, $nextSegmentIndex];
    }

    public function normalizeColor(RYB $color, float $dampingFactor = 1): self
    {
        list($targetR, $targetY, $targetB) = $color->asArray();

        $maxCurrent = max($this->r, $this->y, $this->b);
        $minCurrent = min($this->r, $this->y, $this->b);
        $rangeCurrent = $maxCurrent - $minCurrent;

        $maxTarget = max($targetR, $targetY, $targetB);
        $minTarget = min($targetR, $targetY, $targetB);
        $rangeTarget = $maxTarget - $minTarget;

        $adjustmentRatio = 1 + ($dampingFactor * ($rangeCurrent - $rangeTarget) / 255);

        $newR = $minCurrent + ($targetR - $minTarget) * $adjustmentRatio;
        $newY = $minCurrent + ($targetY - $minTarget) * $adjustmentRatio;
        $newB = $minCurrent + ($targetB - $minTarget) * $adjustmentRatio;

        return new RYB($newR, $newY, $newB);
    }

    public function blendColors(RYB $color, float $weight): RYB
    {
        $weight = (1 - cos($weight * M_PI)) / 2;
        
        $red    = $this->r * (1 - $weight) + $color->getRed()    * $weight;
        $yellow = $this->y * (1 - $weight) + $color->getYellow() * $weight;
        $blue   = $this->b * (1 - $weight) + $color->getBlue()   * $weight;

        return new RYB($red, $yellow, $blue);
    }

    public function isGrayScale(): bool
    {
        return abs($this->r - $this->y) === 0 && abs($this->y - $this->b) === 0 && abs($this->b - $this->r) === 0;
    }

    public function findColorByDifference(float $difference): self
    {
        return $this->asLAB()->findColorByDifference($difference)->asRYB();
    }

    public function findColorByDistance(float $distance): self
    {
        return $this->asLAB()->findColorByDistance($distance)->asRYB();
    }

    public function adjustShade(int $shade): self
    {
        return $this->asLAB()->adjustShade($shade)->asRYB();
    }

    public function linearDeviance(float $percent): self
    {
        if ($percent === 0) {
            return clone $this;
        }

        $adjustedPercent = $percent / 100;
        $directionR = ($this->r > 127) ? -1 : 1;
        $directionY = ($this->y > 127) ? -1 : 1;
        $directionB = ($this->b > 127) ? -1 : 1;

        $newR = max(0, min(255, $this->r + $directionR * $adjustedPercent * (255 - $this->r)));
        $newY = max(0, min(255, $this->y + $directionY * $adjustedPercent * (255 - $this->y)));
        $newB = max(0, min(255, $this->b + $directionB * $adjustedPercent * (255 - $this->b)));

        return new RYB($newR, $newY, $newB);
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
        list($red, $yellow, $blue) = $this->asArray();

        $minPrimary = min($red, $yellow, $blue);
        $red -= $minPrimary;
        $yellow -= $minPrimary;
        $blue -= $minPrimary;

        $maxPrimary = max($red, $yellow, $blue);
        $greenComponent = min($yellow, $blue);
        $yellow -= $greenComponent;
        $blue -= $greenComponent;

        if ($blue && $greenComponent) {
            $blue *= 2.0;
            $greenComponent *= 2.0;
        }

        $red += $yellow;
        $green = $greenComponent + $yellow;

        $maxGreen = max($red, $green, $blue);
        if ($maxGreen) {
            $normalizationFactor = $maxPrimary / $maxGreen;
            $red *= $normalizationFactor;
            $green *= $normalizationFactor;
            $blue *= $normalizationFactor;
        }

        $red += $minPrimary;
        $green += $minPrimary;
        $blue += $minPrimary;

        return new RGB($red, $green, $blue);
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
