<?php

namespace Dashifen\FireflyTheme\Templates\Framework;

use SplFileInfo;
use Timber\Timber;
use RegexIterator;
use Timber\Menu as TimberMenu;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Dashifen\FireflyTheme\Theme;
use Timber\MenuItem as TimberMenuItem;
use Dashifen\WPTemplates\AbstractTemplate;
use Dashifen\WPTemplates\TemplateException;
use Dashifen\Repository\RepositoryException;
use Dashifen\FireflyTheme\Repositories\MenuItem;

abstract class AbstractFireflyTemplate extends AbstractTemplate
{
  protected int $postId;
  protected array $twigs;
  
  /**
   * AbstractFireflyTemplate constructor.
   *
   * @throws RepositoryException
   * @throws FireflyTemplateException
   */
  public function __construct()
  {
    $this->postId = get_the_ID();
    $this->twigs = $this->findTwigs();
    
    // the context for our template is the merge of the default context for
    // the entire site and the context for a specific template.  the default
    // context is built within this object so that it's available to all
    // templates.  the getTemplateContext method is left abstract so the
    // extensions of this object must define it.
    
    $context = array_merge(
      $this->getDefaultContext(),
      $this->getTemplateContext()
    );
    
    try {
      parent::__construct($this->getTemplateTwig(), $context);
    } catch (TemplateException $e) {
      
      // to make try/catch blocks easier external to this scope, we'll convert
      // our vanilla TemplateException into a FireflyTemplateException here
      // and then simply re-throw it.
      
      throw new FireflyTemplateException($e->getMessage(), $e->getCode(), $e);
    }
  }
  
  private function findTwigs(): array
  {
    // first, we want to get all of the files within the assets/twigs folder.
    // we select them in such a way that the filenames will be the keys of our
    // iterator which is important below.
    
    $directory = new RecursiveDirectoryIterator(
      get_stylesheet_directory() . '/assets/twigs/',
      RecursiveDirectoryIterator::KEY_AS_FILENAME
      | RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
      | RecursiveDirectoryIterator::SKIP_DOTS
    );
    
    // now, it's very, very likely that the only files within the folder we
    // searched above are twig files.  but, just in case we drop a readme or
    // other file type in there, we'll look at the keys within our iterator
    // and only keep the ones that match the regular expression crammed below.
    
    $files = new RegexIterator(
      new RecursiveIteratorIterator($directory),
      '/.twig$/',
      RegexIterator::MATCH,
      RegexIterator::USE_KEY
    );
    
    // unfortunately, we can't cram a RegexIterator into array_map because,
    // while it implements Iterator, it doesn't implement ArrayIterator.  so,
    // instead, we'll just do a quick loop to "convert" the SplFileInfo objects
    // collected by our iterator into simple filenames.
    
    $twigs = [];
    foreach ($files as $file) {
      /** @var SplFileInfo $file */
      $twigs[] = $file->getFilename();
    }
    
    return $twigs;
  }
  
  /**
   * getDefaultContext
   *
   * Returns an array of data that's used throughout the site.
   *
   * @return array
   * @throws RepositoryException
   */
  private function getDefaultContext(): array
  {
    return [
      'year' => date('Y'),
      'site' => [
        'url'    => home_url(),
        'title'  => 'The Firefly House',
        'banner' => [
          'src' => get_stylesheet_directory_uri() . '/assets/images/elements.jpg',
          'alt' => 'a stylistic image depicting the four classical Western elements:  earth, air, fire, and water',
        ],
        'menus'  => [
          'main' => $this->getMainMenu(),
        ],
      ],
    ];
  }
  
  /**
   * getMainMenu
   *
   * Returns an array of
   *
   * @return array
   * @throws RepositoryException
   */
  private function getMainMenu(): array
  {
    // Timber can give us a set of menu items, but they contain a massive
    // amount of data that we don't actually need to build our site.  so, we
    // want to use its data to construct MenuItem repositories instead, and
    // that's something that array_map can do for us very easily.  typically,
    // there's no need to specify types for short closures, but this time we
    // wanted to be as clear as possible about what was going on here.
    
    return array_map(
      fn(TimberMenuItem $item) => new MenuItem($item),
      (new TimberMenu('main'))->get_items()
    );
  }
  
  /**
   * getTemplateContext
   *
   * Returns an array containing data for this template's context that is
   * merged with the default data to form the context for the entire request.
   *
   * @return array
   */
  abstract protected function getTemplateContext(): array;
  
  /**
   * getTemplateTwig
   *
   * Returns the name of this template's twig file.
   *
   * @return string
   */
  abstract protected function getTemplateTwig(): string;
  
  /**
   * render
   *
   * Renders either a previously set template file and context or can use
   * the optional parameters here to specify what a file and context at the
   * time of the call.
   *
   * @param bool        $debug
   * @param string|null $file
   * @param array|null  $context
   *
   * @return void
   * @throws FireflyTemplateException
   */
  public function render(bool $debug = false, ?string $file = null, ?array $context = null): void
  {
    if (empty($file ??= $this->file)) {
      throw new FireflyTemplateException(
        'Cannot compile without a twig file.',
        FireflyTemplateException::UNKNOWN_TWIG
      );
    }
    
    if (empty($context ??= $this->context)) {
      throw new FireflyTemplateException(
        'Cannot compile without a twig context.',
        FireflyTemplateException::UNKNOWN_CONTEXT
      );
    }
    
    parent::render($debug, $file, $context);
  }
  
  
  /**
   * compile
   *
   * Compiles either a previously set template file and context or can use
   * the optional parameters here to specify the file and context at the time
   * of the call and returns it to the calling scope.     *
   *
   * @param bool        $debug
   * @param string|null $file
   * @param array|null  $context
   *
   * @return string
   * @throws FireflyTemplateException
   */
  public function compile(bool $debug = false, ?string $file = null, ?array $context = null): string
  {
    if (empty($file ??= $this->file)) {
      throw new FireflyTemplateException(
        'Cannot compile without a twig file.',
        FireflyTemplateException::UNKNOWN_TWIG
      );
    }
    
    if (empty($context ??= $this->context)) {
      throw new FireflyTemplateException(
        'Cannot compile without a twig context.',
        FireflyTemplateException::UNKNOWN_CONTEXT
      );
    }
    
    $compilation = Timber::fetch($file, $context);
    
    if ($debug || Theme::isDebug()) {
      $context['twig'] = $file;
      $compilation .= '<!--' . PHP_EOL;
      $compilation .= print_r($context, true);
      $compilation .= PHP_EOL . '-->';
    }
    
    return $compilation;
  }
  
  
}
