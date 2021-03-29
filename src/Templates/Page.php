<?php

namespace Dashifen\FireflyTheme\Templates;

use Dashifen\FireflyTheme\Templates\Framework\AbstractFireflyTemplate;

class Page extends AbstractFireflyTemplate
{
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
    return [
      'post' => [
        'title'   => get_the_title(),
        'content' => $this->getContent(),
      ],
    ];
  }
  
}
