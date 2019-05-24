# Localize

PHP Gettext translation generator from project files 

<p align="center">
<a href="https://packagist.org/packages/kosatyi/localize"><img src="https://poser.pugx.org/kosatyi/localize/version" /></a>
<a href="https://packagist.org/packages/kosatyi/localize"><img src="https://poser.pugx.org/kosatyi/localize/downloads"/></a>
<a href="https://packagist.org/packages/kosatyi/localize"><img src="https://poser.pugx.org/kosatyi/localize/license" /></a>
</p>

## Installation

### System Requirements

PHP 5.4 and later.

### Dependencies

Localize require the following extension in order to work properly:

- [`gettext`](http://php.net/manual/en/gettext.installation.php)


### Install with Composer

If you’re using [Composer](https://getcomposer.org/), you can run the following command:

```cmd
composer require kosatyi/localize
```

Or add dependency manually in `composer.json`

```json
{
  "require": {
    "kosatyi/localize":"^1.0"
  }
}
```

### Basic Usage

```php
require 'vendor/autoload.php';
use Kosatyi\Localize\Parser;
$localize = new Parser(array(
    'target'  => '.locales',
    'sources' => array('./src','./templates'),
    'locales' => array('en','ru')
));
$localize->initialize();
```



