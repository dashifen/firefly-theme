<?php

namespace Dashifen\FireflyTheme;

use Dashifen\WPHandler\Handlers\HandlerException;
use Dashifen\WPHandler\Handlers\Themes\AbstractThemeHandler;

class Theme extends AbstractThemeHandler
{
  /**
   * initialize
   *
   * Uses addAction and/or addFilter to hook protected methods of this object
   * to the WordPress ecosystem.
   *
   * @return void
   * @throws HandlerException
   */
  public function initialize(): void
  {
    if (!$this->isInitialized()) {
      $this->addAction('wp_enqueue_scripts', 'addAssets');
    }
  }
  
  /**
   * addAssets
   *
   * Adds the script and style assets for this theme into the WordPress
   * assets queue.
   *
   * @return void
   */
  protected function addAssets(): void
  {
    $this->enqueue('assets/firefly.js');
    $this->enqueue('assets/firefly.css');
  }
}
