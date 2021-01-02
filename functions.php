<?php
/**
 * @noinspection PhpStatementHasEmptyBodyInspection
 * @noinspection PhpIncludeInspection
 */

use Dashifen\FireflyTheme\Theme;
use Dashifen\WPHandler\Handlers\HandlerException;

$autoloader = file_exists(dirname(ABSPATH) . '/deps/vendor/autoload.php')
  ? dirname(ABSPATH) . '/deps/vendor/autoload.php'    // production location
  : 'vendor/autoload.php';                            // development location

require_once($autoloader);

try {
  (function () {
    
    // by instantiating our theme within this anonymous function we avoid
    // putting it in the global scope.  this prevents access to it anywhere
    // but within this scope.
    
    $theme = new Theme();
    $theme->initialize();
  })();
} catch (HandlerException $e) {
  Theme::catcher($e);
}
