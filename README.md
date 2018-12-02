# PHP Command Line Interface (CLI) Library

- Version: v1.0.2
- Date: December 2nd 2018
- [Release notes](https://github.com/pointybeard/php-cli-lib/blob/master/CHANGELOG.md)
- [GitHub repository](https://github.com/pointybeard/php-cli-lib)

Collection of helpful classes to use when working on the command line (cli).

## Installation

This library is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/php-cli-lib` or add `"pointybeard/php-cli-lib": "~1.0"` to your `composer.json` file.

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Usage

This library is broken into 3 distinct components: Message, Prompt, and Argument. They can be used as needed, although they do rely on each other internally.

### Message

```php
use CLILib\Message;

## Display a message to STDOUT
(new Message)
    ->message("This is my message")
    ->prependDate(true)
    ->dateFormat('G:i:s > ')
    ->appendNewLine(true)
    ->foreground("light green")
    ->background("red")
    ->display(STDOUT)
;
```

### Prompt

```php
use CLILib\Prompt;
use CLILib\Message;

// Most basic usage
$name = Prompt::display(
    "Please enter your name"
);
## > Please enter your name:
## >

// Asking for a password
$password = Prompt::display(
    "Please enter your password", Prompt::FLAG_SILENT
);
## > Please enter your password:
## >

// Providing a Message object and default value
$message = (new Message)
    ->message("Enter database user name")
    ->prependDate(false)
    ->appendNewLine(false)
    ->foreground("light green")
    ->background("red")
;
$databaseUserName = Prompt::display(
    $message, null, "root",
);
## > Enter database user name [root]:
## >

// Using a validator to check the input
$user = Prompt::display(
    "Enter your user name", null, null, function($input){
        if(strlen(trim($input)) == 0) {
            (new Message
                ->message("Error: You must enter a user name!")
                ->prependDate(false)
                ->appendNewLine(true)
                ->foreground("red")
            ;
            return false;
        }
        return true;
    }
);
## > Enter your user name:
## >
## > Error: You must enter a user name!
## > Enter your user name:
## >
```

### Argument & Argument/Iterator

Include `CLILib\Argument` in your scripts then create an instance of `Argument\Iterator`. It will automatically look for arguments, or you can pass it your own argument string (see below).

#### Syntax Supported

This library supports the most common argument formats. Specifically `-x`,` --long`, `/x`. It also supports use of `=` or `:` as a delimiter. The following are examples of supported argument syntax:

    -x
    --aa
    --database=blah
    -d:blah
    --d blah
    --database-name=blah
    /d blah
    -u http://www.theproject.com
    -y something
    -p:\Users\pointybeard\Sites\shellargs\
    -p:"\Users\pointybeard\Sites"
    -h:local:host
    /host=local-host

#### Example

```php
use CLILib\Argument;

// Load up the arguments from $argv. By default
// it will ignore the first item, which is the
// script name
$args = new Argument\Iterator();

// Instead of using $argv, send in an array
// of arguments. e.g. emulates "... -i --database blah"
$args = new Argument\Iterator(false, [
    '-i', '--database', 'blah'
]);

// Arguments can an entire string too [Added 1.0.1]
$args = new Argument\Iterator(false, [
    '-i --database blah'
]);

// Iterate over all the arguments
foreach($args as $a){
    printf("%s => %s" . PHP_EOL, $a->name(), $a->value());
}

// Find a specific argument by name
$args->find('i');

// Find also accepts an array of values, returning the first one that is valid
$args->find(['h', 'help', 'usage']);
```

## Running the Test Suite

You can check that all code is passing by running the following command from the shell-args folder:

    ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/ArgumentsTest

If you want to run code coverage (e.g. `--coverage-html tests/reports/ ...`) you'll need an older version of xdebug (for PHP 5.6). To install this, use the following commands:

    pecl channel-update pecl.php.net
    pecl install xdebug-2.5.5

You'll need enable `xdebug.so`. Try adding the following to `/etc/php/5.6/mods-available`

    ; configuration for php xdebug module
    ; priority=20
    zend_extension=/usr/lib/php/20131226/xdebug.so

Then enable it with `phpenmod xdebug`. The above works on Ubuntu, however, paths might be different for other distros.

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/php-cli-lib/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/php-cli-lib/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"PHP Command Line Interface (CLI) Library" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
