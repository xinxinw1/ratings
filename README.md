# PHP Ratings Plugin

This plugin allows visitors to rate things on your site.

A demo is available at http://musiclifephilosophy.com/codes/ratings-demo/

Note that 

See also: https://github.com/xinxinw1/ratings-demo

## Installation

You need a server that has php, mysql and git.

### Using submodules

1. `$ git submodule add https://github.com/xinxinw1/ratings.git lib/ratings`
2. `$ git submodule update --init --recursive`

### Without submodules

1. `$ git clone --recursive https://github.com/xinxinw1/ratings.git lib/ratings`

### Continue

1. Create a ratings table in MySQL based on `lib/ratings/ratings.sql`
2. Create a ratings user in MySQL with database-specific SELECT,INSERT,UPDATE,DELETE privileges on the ratings table.
5. Copy `authinfo.php.example` to `authinfo.php` and change the info to fit your system.
6. Add the following to your php file:
```php
<iframe width="170" height="65" style="border: 0; display: block;" src="lib/ratings/?id=<?php echo rawurlencode($id) ?>"></iframe>
```
7. Change `$id` from the previous step to whatever variable holds the id for the current ratings object. (Note that id can have letters and special characters as well)
8. Run your php file.

## License

This program is dedicated to the public domain using the [Creative Commons CC0](http://creativecommons.org/publicdomain/zero/1.0/). See `LICENSE.txt` for details.
