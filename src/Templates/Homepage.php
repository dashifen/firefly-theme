<?php

namespace Dashifen\FireflyTheme\Templates;

use Dashifen\FireflyTheme\Templates\Framework\AbstractFireflyTemplate;

class Homepage extends AbstractFireflyTemplate
{
  /**
   * getTemplateContext
   *
   * Returns an array containing data for this template's context that is
   * merged with the default data to form the context for the entire request.
   *
   * @return array
   */
  protected function getTemplateContext(): array
  {
    return [
      'page' => [
        'title' => 'Home',
        'content' => 'Hello, World!',
      ],
    ];
  }
  
  /**
   * getTemplateTwig
   *
   * Returns the name of this template's twig file.
   *
   * @return string
   */
  protected function getTemplateTwig(): string
  {
    return 'homepage.twig';
  }
}
