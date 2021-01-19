<?php

namespace Dashifen\FireflyTheme\Repositories;

use Dashifen\Repository\Repository;
use Timber\MenuItem as TimberMenuItem;
use Dashifen\Repository\RepositoryException;

class MenuItem extends Repository
{
  protected array $classes = [];
  protected bool $current;
  protected string $label;
  protected string $url;
  
  public function __construct(TimberMenuItem $item)
  {
    parent::__construct(
      [
        'classes' => array_filter($item->classes),
        'current' => $item->current || $item->current_item_ancestor || $item->current_item_parent,
        'label'   => $item->name(),
        'url'     => $item->url,
      ]
    );
  }
  
  /**
   * setClasses
   *
   * Sets the classes property.
   *
   * @param array $classes
   *
   * @return void
   */
  protected function setClasses(array $classes): void
  {
    // if there have been previously set classes, we don't want to obliterate
    // them with a simple assignment here.  instead, we merge anything that was
    // already added to this item's classes with the new information that we've
    // received this time.
    
    $this->classes = array_merge($this->classes, $classes);
    sort($this->classes);
  }
  
  /**
   * setCurrent
   *
   * Sets the current property.
   *
   * @param bool $current
   *
   * @return void
   */
  protected function setCurrent(bool $current): void
  {
    $this->current = $current;
    
    // in addition to remembering our Boolean in case it's handy on the
    // server side, for the client side we want to add a class to each menu
    // item so that they can get different styles as needed.
    
    $currentClass = $current ? 'is-current' : 'is-not-current';
    $this->setClasses([$currentClass]);
  }
  
  /**
   * setUrl
   *
   * Sets the url property.
   *
   * @param string $url
   *
   * @return void
   */
  protected function setUrl(string $url): void
  {
    $this->url = $url;
  }
  
  /**
   * setLabel
   *
   * Sets the label property.
   *
   * @param string $label
   *
   * @return void
   */
  protected function setLabel(string $label): void
  {
    $this->label = $label;
  }
}
