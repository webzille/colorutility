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

class Colors {

    public static string $hexPattern = '/^(#)?([0-9a-fA-F]{2,4}|[0-9a-fA-F]{6,8})$/';

    public static string $valuePattern = '/(?:\.\d+|\d+(?:\.\d+)?)/';

    public static array $settable = [
        'rgba'  => RGBA::class,
        'rgb'   => RGB::class,
        'hsla'  => HSLA::class,
        'hsl'   => HSL::class,
    ];

    public static array $websafe = [
        RGBA::class,
        RGB::class,
        HSLA::class,
        HSL::class,
        HEX::class,
    ];

    public static array $colors = [
        HSLA::class,
        HSL::class,
        RGBA::class,
        RGB::class,
        HEX::class,
        RYB::class,
        HSV::class,
        LAB::class,
        CylindricalLAB::class
    ];

    public static array $namedColors = [
        'aliceblue' => [
            'hex' => 'f0f8ff',
            'rgb' => '240,248,255'
        ],
        'antiquewhite' => [
            'hex' => 'faebd7',
            'rgb' => '250,235,215'
        ],
        'aqua' => [
            'hex' => '00ffff',
            'rgb' => '0,255,255'
        ],
        'aquamarine' => [
            'hex' => '7fffd4',
            'rgb' => '127,255,212'
        ],
        'azure' => [
            'hex' => 'f0ffff',
            'rgb' => '240,255,255'
        ],
        'beige' => [
            'hex' => 'f5f5dc',
            'rgb' => '245,245,220'
        ],
        'bisque' => [
            'hex' => 'ffe4c4',
            'rgb' => '255,228,196'
        ],
        'black' => [
            'hex' => '000000',
            'rgb' => '0,0,0'
        ],
        'blanchedalmond' => [
            'hex' => 'ffebcd',
            'rgb' => '255,235,205'
        ],
        'blue' => [
            'hex' => '0000ff',
            'rgb' => '0,0,255'
        ],
        'blueviolet' => [
            'hex' => '8a2be2',
            'rgb' => '138,43,226'
        ],
        'brown' => [
            'hex' => 'a52a2a',
            'rgb' => '165,42,42'
        ],
        'burlywood' => [
            'hex' => 'deb887',
            'rgb' => '222,184,135'
        ],
        'cadetblue' => [
            'hex' => '5f9ea0',
            'rgb' => '95,158,160'
        ],
        'chartreuse' => [
            'hex' => '7fff00',
            'rgb' => '127,255,0'
        ],
        'chocolate' => [
            'hex' => 'd2691e',
            'rgb' => '210,105,30'
        ],
        'coral' => [
            'hex' => 'ff7f50',
            'rgb' => '255,127,80'
        ],
        'cornflowerblue' => [
            'hex' => '6495ed',
            'rgb' => '100,149,237'
        ],
        'cornsilk' => [
            'hex' => 'fff8dc',
            'rgb' => '255,248,220'
        ],
        'crimson' => [
            'hex' => 'dc143c',
            'rgb' => '220,20,60'
        ],
        'cyan' => [
            'hex' => '00ffff',
            'rgb' => '0,255,255'
        ],
        'darkblue' => [
            'hex' => '00008b',
            'rgb' => '0,0,139'
        ],
        'darkcyan' => [
            'hex' => '008b8b',
            'rgb' => '0,139,139'
        ],
        'darkgoldenrod' => [
            'hex' => 'b8860b',
            'rgb' => '184,134,11'
        ],
        'darkgray' => [
            'hex' => 'a9a9a9',
            'rgb' => '169,169,169'
        ],
        'darkgreen' => [
            'hex' => '006400',
            'rgb' => '0,100,0'
        ],
        'darkgrey' => [
            'hex' => 'a9a9a9',
            'rgb' => '169,169,169'
        ],
        'darkkhaki' => [
            'hex' => 'bdb76b',
            'rgb' => '189,183,107'
        ],
        'darkmagenta' => [
            'hex' => '8b008b',
            'rgb' => '139,0,139'
        ],
        'darkolivegreen' => [
            'hex' => '556b2f',
            'rgb' => '85,107,47'
        ],
        'darkorange' => [
            'hex' => 'ff8c00',
            'rgb' => '255,140,0'
        ],
        'darkorchid' => [
            'hex' => '9932cc',
            'rgb' => '153,50,204'
        ],
        'darkred' => [
            'hex' => '8b0000',
            'rgb' => '139,0,0'
        ],
        'darksalmon' => [
            'hex' => 'e9967a',
            'rgb' => '233,150,122'
        ],
        'darkseagreen' => [
            'hex' => '8fbc8f',
            'rgb' => '143,188,143'
        ],
        'darkslateblue' => [
            'hex' => '483d8b',
            'rgb' => '72,61,139'
        ],
        'darkslategray' => [
            'hex' => '2f4f4f',
            'rgb' => '47,79,79'
        ],
        'darkslategrey' => [
            'hex' => '2f4f4f',
            'rgb' => '47,79,79'
        ],
        'darkturquoise' => [
            'hex' => '00ced1',
            'rgb' => '0,206,209'
        ],
        'darkviolet' => [
            'hex' => '9400d3',
            'rgb' => '148,0,211'
        ],
        'deeppink' => [
            'hex' => 'ff1493',
            'rgb' => '255,20,147'
        ],
        'deepskyblue' => [
            'hex' => '00bfff',
            'rgb' => '0,191,255'
        ],
        'dimgray' => [
            'hex' => '696969',
            'rgb' => '105,105,105'
        ],
        'dimgrey' => [
            'hex' => '696969',
            'rgb' => '105,105,105'
        ],
        'dodgerblue' => [
            'hex' => '1e90ff',
            'rgb' => '30,144,255'
        ],
        'firebrick' => [
            'hex' => 'b22222',
            'rgb' => '178,34,34'
        ],
        'floralwhite' => [
            'hex' => 'fffaf0',
            'rgb' => '255,250,240'
        ],
        'forestgreen' => [
            'hex' => '228b22',
            'rgb' => '34,139,34'
        ],
        'fuchsia' => [
            'hex' => 'ff00ff',
            'rgb' => '255,0,255'
        ],
        'gainsboro' => [
            'hex' => 'dcdcdc',
            'rgb' => '220,220,220'
        ],
        'ghostwhite' => [
            'hex' => 'f8f8ff',
            'rgb' => '248,248,255'
        ],
        'gold' => [
            'hex' => 'ffd700',
            'rgb' => '255,215,0'
        ],
        'goldenrod' => [
            'hex' => 'daa520',
            'rgb' => '218,165,32'
        ],
        'gray' => [
            'hex' => '808080',
            'rgb' => '128,128,128'
        ],
        'green' => [
            'hex' => '008000',
            'rgb' => '0,128,0'
        ],
        'greenyellow' => [
            'hex' => 'adff2f',
            'rgb' => '173,255,47'
        ],
        'grey' => [
            'hex' => '808080',
            'rgb' => '128,128,128'
        ],
        'honeydew' => [
            'hex' => 'f0fff0',
            'rgb' => '240,255,240'
        ],
        'hotpink' => [
            'hex' => 'ff69b4',
            'rgb' => '255,105,180'
        ],
        'indianred' => [
            'hex' => 'cd5c5c',
            'rgb' => '205,92,92'
        ],
        'indigo' => [
            'hex' => '4b0082',
            'rgb' => '75,0,130'
        ],
        'ivory' => [
            'hex' => 'fffff0',
            'rgb' => '255,255,240'
        ],
        'khaki' => [
            'hex' => 'f0e68c',
            'rgb' => '240,230,140'
        ],
        'lavender' => [
            'hex' => 'e6e6fa',
            'rgb' => '230,230,250'
        ],
        'lavenderblush' => [
            'hex' => 'fff0f5',
            'rgb' => '255,240,245'
        ],
        'lawngreen' => [
            'hex' => '7cfc00',
            'rgb' => '124,252,0'
        ],
        'lemonchiffon' => [
            'hex' => 'fffacd',
            'rgb' => '255,250,205'
        ],
        'lightblue' => [
            'hex' => 'add8e6',
            'rgb' => '173,216,230'
        ],
        'lightcoral' => [
            'hex' => 'f08080',
            'rgb' => '240,128,128'
        ],
        'lightcyan' => [
            'hex' => 'e0ffff',
            'rgb' => '224,255,255'
        ],
        'lightgoldenrodyellow' => [
            'hex' => 'fafad2',
            'rgb' => '250,250,210'
        ],
        'lightgray' => [
            'hex' => 'd3d3d3',
            'rgb' => '211,211,211'
        ],
        'lightgreen' => [
            'hex' => '90ee90',
            'rgb' => '144,238,144'
        ],
        'lightgrey' => [
            'hex' => 'd3d3d3',
            'rgb' => '211,211,211'
        ],
        'lightpink' => [
            'hex' => 'ffb6c1',
            'rgb' => '255,182,193'
        ],
        'lightsalmon' => [
            'hex' => 'ffa07a',
            'rgb' => '255,160,122'
        ],
        'lightseagreen' => [
            'hex' => '20b2aa',
            'rgb' => '32,178,170'
        ],
        'lightskyblue' => [
            'hex' => '87cefa',
            'rgb' => '135,206,250'
        ],
        'lightslategray' => [
            'hex' => '778899',
            'rgb' => '119,136,153'
        ],
        'lightslategrey' => [
            'hex' => '778899',
            'rgb' => '119,136,153'
        ],
        'lightsteelblue' => [
            'hex' => 'b0c4de',
            'rgb' => '176,196,222'
        ],
        'lightyellow' => [
            'hex' => 'ffffe0',
            'rgb' => '255,255,224'
        ],
        'lime' => [
            'hex' => '00ff00',
            'rgb' => '0,255,0'
        ],
        'limegreen' => [
            'hex' => '32cd32',
            'rgb' => '50,205,50'
        ],
        'linen' => [
            'hex' => 'faf0e6',
            'rgb' => '250,240,230'
        ],
        'magenta' => [
            'hex' => 'ff00ff',
            'rgb' => '255,0,255'
        ],
        'maroon' => [
            'hex' => '800000',
            'rgb' => '128,0,0'
        ],
        'mediumaquamarine' => [
            'hex' => '66cdaa',
            'rgb' => '102,205,170'
        ],
        'mediumblue' => [
            'hex' => '0000cd',
            'rgb' => '0,0,205'
        ],
        'mediumorchid' => [
            'hex' => 'ba55d3',
            'rgb' => '186,85,211'
        ],
        'mediumpurple' => [
            'hex' => '9370db',
            'rgb' => '147,112,219'
        ],
        'mediumseagreen' => [
            'hex' => '3cb371',
            'rgb' => '60,179,113'
        ],
        'mediumslateblue' => [
            'hex' => '7b68ee',
            'rgb' => '123,104,238'
        ],
        'mediumspringgreen' => [
            'hex' => '00fa9a',
            'rgb' => '0,250,154'
        ],
        'mediumturquoise' => [
            'hex' => '48d1cc',
            'rgb' => '72,209,204'
        ],
        'mediumvioletred' => [
            'hex' => 'c71585',
            'rgb' => '199,21,133'
        ],
        'midnightblue' => [
            'hex' => '191970',
            'rgb' => '25,25,112'
        ],
        'mintcream' => [
            'hex' => 'f5fffa',
            'rgb' => '245,255,250'
        ],
        'mistyrose' => [
            'hex' => 'ffe4e1',
            'rgb' => '255,228,225'
        ],
        'moccasin' => [
            'hex' => 'ffe4b5',
            'rgb' => '255,228,181'
        ],
        'navajowhite' => [
            'hex' => 'ffdead',
            'rgb' => '255,222,173'
        ],
        'navy' => [
            'hex' => '000080',
            'rgb' => '0,0,128'
        ],
        'oldlace' => [
            'hex' => 'fdf5e6',
            'rgb' => '253,245,230'
        ],
        'olive' => [
            'hex' => '808000',
            'rgb' => '128,128,0'
        ],
        'olivedrab' => [
            'hex' => '6b8e23',
            'rgb' => '107,142,35'
        ],
        'orange' => [
            'hex' => 'ffa500',
            'rgb' => '255,165,0'
        ],
        'orangered' => [
            'hex' => 'ff4500',
            'rgb' => '255,69,0'
        ],
        'orchid' => [
            'hex' => 'da70d6',
            'rgb' => '218,112,214'
        ],
        'palegoldenrod' => [
            'hex' => 'eee8aa',
            'rgb' => '238,232,170'
        ],
        'palegreen' => [
            'hex' => '98fb98',
            'rgb' => '152,251,152'
        ],
        'paleturquoise' => [
            'hex' => 'afeeee',
            'rgb' => '175,238,238'
        ],
        'palevioletred' => [
            'hex' => 'db7093',
            'rgb' => '219,112,147'
        ],
        'papayawhip' => [
            'hex' => 'ffefd5',
            'rgb' => '255,239,213'
        ],
        'peachpuff' => [
            'hex' => 'ffdab9',
            'rgb' => '255,218,185'
        ],
        'peru' => [
            'hex' => 'cd853f',
            'rgb' => '205,133,63'
        ],
        'pink' => [
            'hex' => 'ffc0cb',
            'rgb' => '255,192,203'
        ],
        'plum' => [
            'hex' => 'dda0dd',
            'rgb' => '221,160,221'
        ],
        'powderblue' => [
            'hex' => 'b0e0e6',
            'rgb' => '176,224,230'
        ],
        'purple' => [
            'hex' => '800080',
            'rgb' => '128,0,128'
        ],
        'rebeccapurple' => [
            'hex' => '663399',
            'rgb' => '102,51,153'
        ],
        'red' => [
            'hex' => 'ff0000',
            'rgb' => '255,0,0'
        ],
        'rosybrown' => [
            'hex' => 'bc8f8f',
            'rgb' => '188,143,143'
        ],
        'royalblue' => [
            'hex' => '4169e1',
            'rgb' => '65,105,225'
        ],
        'saddlebrown' => [
            'hex' => '8b4513',
            'rgb' => '139,69,19'
        ],
        'salmon' => [
            'hex' => 'fa8072',
            'rgb' => '250,128,114'
        ],
        'sandybrown' => [
            'hex' => 'f4a460',
            'rgb' => '244,164,96'
        ],
        'seagreen' => [
            'hex' => '2e8b57',
            'rgb' => '46,139,87'
        ],
        'seashell' => [
            'hex' => 'fff5ee',
            'rgb' => '255,245,238'
        ],
        'sienna' => [
            'hex' => 'a0522d',
            'rgb' => '160,82,45'
        ],
        'silver' => [
            'hex' => 'c0c0c0',
            'rgb' => '192,192,192'
        ],
        'skyblue' => [
            'hex' => '87ceeb',
            'rgb' => '135,206,235'
        ],
        'slateblue' => [
            'hex' => '6a5acd',
            'rgb' => '106,90,205'
        ],
        'slategray' => [
            'hex' => '708090',
            'rgb' => '112,128,144'
        ],
        'slategrey' => [
            'hex' => '708090',
            'rgb' => '112,128,144'
        ],
        'snow' => [
            'hex' => 'fffafa',
            'rgb' => '255,250,250'
        ],
        'springgreen' => [
            'hex' => '00ff7f',
            'rgb' => '0,255,127'
        ],
        'steelblue' => [
            'hex' => '4682b4',
            'rgb' => '70,130,180'
        ],
        'tan' => [
            'hex' => 'd2b48c',
            'rgb' => '210,180,140'
        ],
        'teal' => [
            'hex' => '008080',
            'rgb' => '0,128,128'
        ],
        'thistle' => [
            'hex' => 'd8bfd8',
            'rgb' => '216,191,216'
        ],
        'tomato' => [
            'hex' => 'ff6347',
            'rgb' => '255,99,71'
        ],
        'transparent' => [
            'hex' => '00000000',
            'rgba' => '0, 0, 0, 0'
        ],
        'turquoise' => [
            'hex' => '40e0d0',
            'rgb' => '64,224,208'
        ],
        'violet' => [
            'hex' => 'ee82ee',
            'rgb' => '238,130,238'
        ],
        'wheat' => [
            'hex' => 'f5deb3',
            'rgb' => '245,222,179'
        ],
        'white' => [
            'hex' => 'ffffff',
            'rgb' => '255,255,255'
        ],
        'whitesmoke' => [
            'hex' => 'f5f5f5',
            'rgb' => '245,245,245'
        ],
        'yellow' => [
            'hex' => 'ffff00',
            'rgb' => '255,255,0'
        ],
        'yellowgreen' => [
            'hex' => '9acd32',
            'rgb' => '154,205,50'
        ],
    ];

    public static function getMatchingPattern($getTransparency = false): string
    {
        $transparent = $getTransparency ? "|transparent" : "";
        return '/(#(?:[a-fA-F\d]{6}(?:[a-fA-F\d]{2})?|[a-fA-F\d]{3}(?:[a-fA-F\d]{1,2})?)|rgba?\(\d{1,3},\s*\d{1,3},\s*\d{1,3}(?:,\s*\d*\.?\d+)?\)|hsla?\(\d{1,3},\s*\d{1,3}%,\s*\d{1,3}%(?:,\s*\d*\.?\d+)?\)|\b(?:aliceblue|antiquewhite|aqua|aquamarine|azure|beige|bisque|black|blanchedalmond|blue|blueviolet|brown|burlywood|cadetblue|chartreuse|chocolate|coral|cornflowerblue|cornsilk|crimson|cyan|darkblue|darkcyan|darkgoldenrod|darkgray|darkgreen|darkkhaki|darkmagenta|darkolivegreen|darkorange|darkorchid|darkred|darksalmon|darkseagreen|darkslateblue|darkslategray|darkturquoise|darkviolet|deeppink|deepskyblue|dimgray|dimgrey|dodgerblue|firebrick|floralwhite|forestgreen|fuchsia|gainsboro|ghostwhite|gold|goldenrod|gray|green|greenyellow|grey|honeydew|hotpink|indianred|indigo|ivory|khaki|lavender|lavenderblush|lawngreen|lemonchiffon|lightblue|lightcoral|lightcyan|lightgoldenrodyellow|lightgray|lightgreen|lightgrey|lightpink|lightsalmon|lightseagreen|lightskyblue|lightslategray|lightslategrey|lightsteelblue|lightyellow|lime|limegreen|linen|magenta|maroon|mediumaquamarine|mediumblue|mediumorchid|mediumpurple|mediumseagreen|mediumslateblue|mediumspringgreen|mediumturquoise|mediumvioletred|midnightblue|mintcream|mistyrose|moccasin|navajowhite|navy|oldlace|olive|olivedrab|orange|orangered|orchid|palegoldenrod|palegreen|paleturquoise|palevioletred|papayawhip|peachpuff|peru|pink|plum|powderblue|purple|rebeccapurple|red|rosybrown|royalblue|saddlebrown|salmon|sandybrown|seagreen|seashell|sienna|silver|skyblue|slateblue|slategray|slategrey|snow|springgreen|steelblue|tan|teal|thistle|tomato'. $transparent .'|turquoise|violet|wheat|white|whitesmoke|yellow|yellowgreen)\b)/i';
    }
}