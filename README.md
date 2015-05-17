# phramework

__phramework__ is a collection of PHP classes that I use frequently.

## Installation

Go to yout project directory and install the package.

```bash
# Install composer if is not installed
curl -sS https://getcomposer.org/installer | php

# Install phramework using composer
php composer.phar require piffall/phramework
```

Now, require Composer's autoloader in your aplication.

```php
require 'vendor/autoload.php';
```

## Curl

Curl is a curl wrapper. Here is an example.

```php
use PHK\Curl as Curl;

// New instance
$c = new Curl();

// GET
$c->setUrl('http://yourdomain.net');
$c->get();

// POST
$c->setUrl('http://yourdomain.net/action/');
$c->setDataFields(array('username'=>'user', 'password'=>'*****'));
$c->post();

// Result
$html = $c->getLastReturn();

// Reset Object
$c->reset();

// POST Multipart
$c->setUrl('http://yourdomain.net/action/');
$c->setDataFields(array('username'=>'user', 'password'=>'*****'), true);

// Errors
$err_no = $c->getErrorNumber();
$err_str = $c->getErrorString();
```
