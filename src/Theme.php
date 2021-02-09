<?php

namespace Dashifen\FireflyTheme;

use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Dashifen\WPHandler\Handlers\HandlerException;
use Dashifen\WPHandler\Handlers\Themes\AbstractThemeHandler;

class Theme extends AbstractThemeHandler
{
  public const SLUG = 'firefly-theme';
  
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
      $this->addAction('after_setup_theme', 'addThemeFeatures');
      $this->addFilter('timber/locations', 'addTwigLocation');
      $this->addFilter('timber/loader/loader', 'addTimberNamespaces');
      $this->addFilter('dashifen-card-heading-classes', 'addCardHeadingClasses');
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
    $scriptFont = $this->enqueue('//fonts.googleapis.com/css2?family=Kaushan+Script&display=swap');
    $sanSerifFont = $this->enqueue('//fonts.googleapis.com/css2?family=Poppins&display=swap');
    $this->enqueue('assets/firefly.min.css', [$scriptFont, $sanSerifFont]);
    $this->enqueue('assets/firefly.min.js');
  }
  
  /**
   * addThemeFeatures
   *
   * Adds theme features like featured images, menus, sidebars, etc.
   *
   * @return void
   */
  protected function addThemeFeatures(): void
  {
    register_nav_menus(
      [
        'main'   => 'Main Menu',
        'footer' => 'Footer Menu',
      ]
    );
    
    // for twitter and facebook sharing, we need some extra image sizes.  of
    // course, they can't agree on one size for both platforms so that means we
    // need two.  the true flags mean we crop to these exact dimensions.
    
    add_image_size("twImage", 1200, 675, true);
    add_image_size("fbImage", 1200, 630, true);
  }
  
  /**
   * addTwigLocation
   *
   * Adds the location of our theme's twigs to the Timber loader.
   *
   * @param array $locations
   *
   * @return array
   */
  protected function addTwigLocation(array $locations): array
  {
    $locations[] = $this->getStylesheetDir() . '/assets/twigs/';
    return $locations;
  }
  
  /**
   * addTimberNamespaces
   *
   * Adds the namespaces we use within our twig files to the Timber loader
   * so that it can correctly compile our templates.
   *
   * @param FilesystemLoader $loader
   *
   * @return FilesystemLoader
   * @throws LoaderError
   */
  protected function addTimberNamespaces(FilesystemLoader $loader): FilesystemLoader
  {
    // honestly, this is a little extra, but if we reorganize the twig folder,
    // having the includes namespaced like this will help avoid the need to
    // re-write the actual contents of those folders.
    
    $loader->addPath($this->getStylesheetDir() . '/assets/twigs/layout/', 'layout');
    $loader->addPath($this->getStylesheetDir() . '/assets/twigs/layout/partials/', 'partials');
    return $loader;
  }
  
  /**
   * addCardHeadingClasses
   *
   * Adds the 'h1' class to the list and returns it.  Allows the theme to
   * homogenize the #page-title and the card headings for font and size.
   *
   * @param array $classes
   *
   * @return array
   */
  protected function addCardHeadingClasses(array $classes): array
  {
    $classes[] = 'h1';
    return $classes;
  }
}
