<?php

namespace Dashifen\FireflyTheme\Templates;

use Timber\Timber;
use Dashifen\FireflyTheme\Templates\Framework\AbstractFireflyTemplate;

class Events extends AbstractFireflyTemplate
{
  private array $siteContext;
  
  /**
   * getTemplateTwig
   *
   * Returns the name of this template's twig file.
   *
   * @return string
   */
  protected function getTemplateTwig(): string
  {
    return 'page.twig';
  }
  
  /**
   * getTemplateContext
   *
   * Returns an array containing data for this template's context that is
   * merged with the default data to form the context for the entire request.
   *
   * @param array $siteContext
   *
   * @return array
   */
  protected function getPageContext(array $siteContext): array
  {
    // we can't change the signature of the get content method to pass it the
    // site context, but we can set it to a private property of this object and
    // then use it in the method below.
    
    $this->siteContext = $siteContext;
    
    return [
      'post' => [
        'title'   => get_the_title(),
        'content' => $this->getContent(),
      ],
    ];
  }
  
  /**
   * getContent
   *
   * Returns the filtered content for use on-screen as a convenience to this
   * object's extensions to avoid having to write and re-write this line of
   * code over and over again.
   *
   * @return string
   */
  protected function getContent(): string
  {
    $content = parent::getContent();
    $events = Timber::compile('@partials/events.twig', $this->siteContext);
    return $content . $events;
  }
  
  
}
