# Phmiley [![Build Status](https://travis-ci.com/48design/phmiley.svg?branch=master)](https://travis-ci.com/48design/phmiley) [![Coverage Status](https://coveralls.io/repos/github/48design/phmiley/badge.svg?branch=master)](https://coveralls.io/github/48design/phmiley?branch=master)

Phmiley is a PHP library that replaces Unicode emojis in UTF8 strings with graphics.

**Why is this still needed with growing cross-device Emoji support?**

The creation of this library was triggered by the use case of server-side PDF creation that didn't support Emojis and displayed them as ugly rectangles. Also, Windows does not support Emoji flags natively, so you need to work around that if you want to use flags based on Unicode in your project.

## Installation

Use as a Composer package

`composer require 48design/phmiley`

or require the files from the src folder manually:

```php
use FortyeightDesign\Phmiley\Phmiley;

require '/path/to/phmiley/src/Phmiley.php';
require '/path/to/phmiley/src/Exception.php';
```

Phmiley requires at least PHP version 7.1.0 in order to run.


## Usage

The most simple usage of Phmiley is

```php
$Phmiley = new Phmiley();
$testString = "I would love to have some 🍕 right now! 🤤"
$Phmiley->parse($testString);
```

By default, this will replace all emojis with `<img>` tags with their href pointing to Twitter's Twemoji graphics in PNG format (72x72) on gitcdn.xyz. Some inline styles will make sure that the emojis have a feasible size compared to the text surrounding them.

You can also use some predefined presets to switch to SVG or use OpenMoji graphics instead, or use local graphics from your server. You can even provide your own tag generator callback. See the [Demo Code](https://48design.github.io/phmiley/demo/) for some examples and the Options section for all possible options.

On first run (per Unicode version), Phmiley will automatically get the character ranges for all emojis from Unicode's emoji-data.txt and cache them as a regex in the directory `/regexdata`. The package provides a cached regex file for version 13.0. Switching the version manually via `->setVersion()` hasn't been tested thoroughly and should not be relied on for now.

## Options

### Presets

```php
$Phmiley = new Phmiley([
    'preset' => 'openmoji_svg'
]);
// or
$Phmiley->setPreset('openmoji_svg');
```

Available presets:
| preset               | result                                                    |
|----------------------|-----------------------------------------------------------|
| twemoji_72 (default) | Twemoji PNG graphics (72x72px) served via gitcdn.xyz      |
| twemoji_svg          | Twemoji SVG graphics served via gitcdn.xyz                |
| openmoji_72          | OpenMoji PNG graphics (72x72px) served via jsdelivr.net   |
| openmoji_618         | OpenMoji PNG graphics (618x618px) served via jsdelivr.net |
| openmoji_svg         | OpenMoji SVG graphics (72x72px) served via jsdelivr.net   |


### Tag generator

If you want to get rid of the inline styles, move them to a class instead, add a wrapper around the icon, use a span with background image instead or any other usecase you can think of, you can provide your own tag generator callback. You can provide a callable in whatever form you please, that will receive a `$data` parameter, being an associative array with the following properties:
`emoji` - The real UTF-8 string of the matched emoji
`code` - A represantation of UTF-32 hex codes of all codepoints used in the emoji, joined by dashes; this will be used as the base file name for the default twemoji and openmoji integrations

See the [Demo Code](https://48design.github.io/phmiley/demo/) for example usage.

## Licence

Phmiley's code itself is available under the terms of the MIT license.

The code ranges are extracted from Unicode's emoji-data.txt and other data files, © 2019 Unicode®, Inc.
See their [Terms of Use](https://www.unicode.org/copyright.html) and [License](https://www.unicode.org/license.html) information.

When using the built-in presets that request Twemoji or OpenMoji graphics, you are required to attribute that usage accordingly:

* [Twemoji](https://github.com/twitter/twemoji) Copyright (c) 2018 Twitter, Inc and other contributors. License: [CC-BY 4.0](https://creativecommons.org/licenses/by/4.0/)
* [OpenMoji](https://openmoji.org/) – the open-source emoji and icon project. License: [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/#)
