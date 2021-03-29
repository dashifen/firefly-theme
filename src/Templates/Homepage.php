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
   * @param array $siteContext
   *
   * @return array
   */
  protected function getPageContext(array $siteContext): array
  {
    return [
      'post' => [
        'title'   => 'Home',
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
    
    // we want to surround the main content of our post in a div so that our
    // grid works the way we want it to.  we'll find the <h2> that starts that
    // post content, and add a div before it.  then, we add the closing tag
    // at the end to fully enclose it all.
    
    return str_replace('<h2>', '<div class="post-body"><h2>', $content) . '</div>';
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
