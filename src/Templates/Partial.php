<?php

namespace Dashifen\FireflyTheme\Templates;

use Dashifen\Repository\RepositoryException;
use Dashifen\Transformer\TransformerException;
use Dashifen\WPHandler\Handlers\HandlerException;
use Dashifen\FireflyTheme\Templates\Framework\AbstractFireflyTemplate;
use Dashifen\FireflyTheme\Templates\Framework\FireflyTemplateException;

class Partial extends AbstractFireflyTemplate
{
  private string $twig;
  
  /**
   * AbstractFireflyTemplate constructor.
   *
   * @param string $twig
   *
   * @throws FireflyTemplateException
   * @throws RepositoryException
   * @throws TransformerException
   * @throws HandlerException
   */
  public function __construct(string $twig)
  {
    // the purpose of our Partial template is to load something from within the
    // @partials namespace of our twig template loader.  we suspect that it'll
    // be either the header or the footer, but theoretically, any of them could
    // be displayed here.  regardless, we receive the partial twig filename
    // from the instantiating scope and store it here for later use.
    
    $this->twig = $twig;
    parent::__construct();
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
    return 'partial.twig';
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
      'post' => ['title' => get_the_title()],
      'twig' => $this->twig,
    ];
  }
}
