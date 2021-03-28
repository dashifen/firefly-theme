<?php

namespace Dashifen\FireflyTheme\Templates\Framework;

use WP_Post;
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
use Dashifen\FireflyTheme\Repositories\Event;
use Dashifen\Transformer\TransformerException;
use Dashifen\FireflyTheme\Repositories\MenuItem;
use Dashifen\WPHandler\Handlers\HandlerException;
use Dashifen\WPHandler\Traits\OptionsManagementTrait;

abstract class AbstractFireflyTemplate extends AbstractTemplate
{
  use OptionsManagementTrait;
  
  protected int $postId;
  protected array $twigs;
  
  /**
   * AbstractFireflyTemplate constructor.
   *
   * @throws FireflyTemplateException
   * @throws HandlerException
   * @throws RepositoryException
   * @throws TransformerException
   */
  public function __construct()
  {
    $this->postId = get_the_ID();
    
    try {
      parent::__construct(
        $this->getTwig(),
        $this->getContext()
      );
    } catch (TemplateException $e) {
      
      // to make try/catch blocks easier external to this scope, we'll convert
      // our vanilla TemplateException into a FireflyTemplateException here
      // and then simply re-throw it.
      
      throw new FireflyTemplateException(
        $e->getMessage(),
        $e->getCode(),
        $e
      );
    }
  }
  
  /**
   * getTwig
   *
   * Returns the name of the Twig file used to produce this response.
   *
   * @return string
   * @throws FireflyTemplateException
   * @throws HandlerException
   * @throws TransformerException
   */
  private function getTwig(): string
  {
    $twig = $this->getTemplateTwig();
    if (!isset($this->findTwigs()[$twig])) {
      throw new FireflyTemplateException(
        'Unknown template: ' . $twig,
        FireflyTemplateException::UNKNOWN_TWIG
      );
    }
    
    return $twig;
  }
  
  /**
   * getTemplateTwig
   *
   * Returns the name of this template's twig file.
   *
   * @return string
   */
  abstract protected function getTemplateTwig(): string;
  
  /**
   * findTwig
   *
   * Returns an array of Twig file names located in the assets/twigs folder.
   *
   * @return array
   * @throws HandlerException
   * @throws TransformerException
   */
  private function findTwigs(): array
  {
    // in a production environment, we only want to do this when we absolutely
    // have to.  so, first, we'll see if we're debugging - if so, then we're
    // not on prod and, therefore, we'll always spend the time to make sure
    // that we have the full set of twigs.  otherwise, we do it when the theme
    // is updated.
    
    if (!Theme::isDebug() && !$this->isNewThemeVersion()) {
      return $this->getOption('twigs', []);
    }
    
    // if we're still here, we'll use some SPL iterators to get a list twig
    // files in the assets/twigs folder.  that's likely all of them, but the
    // use of the RegexIterator makes sure to skip readme files or others
    // that we might place there for specific purposes.
    
    $directory = new RecursiveDirectoryIterator(        // get all files
      get_stylesheet_directory() . '/assets/twigs/',    // in this folder
      RecursiveDirectoryIterator::KEY_AS_FILENAME       // keyed by filenames
      | RecursiveDirectoryIterator::SKIP_DOTS           // but skip . and ..
    );
    
    $files = new RegexIterator(                         // limit results
      new RecursiveIteratorIterator($directory),        // within this iterator
      '/.twig$/',                                       // using this regex
      RegexIterator::MATCH,                             // keeping matches
      RegexIterator::USE_KEY                            // based on keys
    );
    
    // at this point, our RegexIterator indexes the files within our folder
    // that end in .twig.  they're indexed using the filenames, and those are
    // the data we want.  so, we convert it to an array.  then we get its keys.
    // next, we flip it so we can confirm the existence of a twig file using
    // isset instead of in_array to save a little time.  finally, we save that
    // in the database in the hopes that we don't have to do this too regularly
    // and then return it.
    
    $twigs = array_flip(array_keys(iterator_to_array($files)));
    $this->updateOption('twigs', $twigs);
    return $twigs;
  }
  
  /**
   * isNewThemeVersion
   *
   * Returns true if the current version of our theme has not been seen
   * before on this server.
   *
   * @return bool
   * @throws HandlerException
   * @throws TransformerException
   */
  private function isNewThemeVersion(): bool
  {
    $knownVersion = $this->getOption('version');
    $currentVersion = wp_get_theme()->get('Version');
    if ($knownVersion !== $currentVersion) {
      
      // if our known version is not the same as our current one, then we
      // this is a new version.  before we return true, we're going to quickly
      // update our version option so that that this server always knows what
      // it is.
      
      $this->updateOption('version', $currentVersion);
      return true;
    }
    
    return false;
  }
  
  /**
   * getContext
   *
   * Returns an array representing the template context for this response.
   *
   * @return array
   * @throws HandlerException
   * @throws RepositoryException
   * @throws TransformerException
   */
  private function getContext(): array
  {
    // the context for our template is the merge of the default context for
    // the entire site and the context for a specific template.  the default
    // context is built within this object so that it's available to all
    // templates.  the getTemplateContext method is left abstract so the
    // extensions of this object must define it.
    
    $siteContext = $this->getSiteContext();
    $templateContext = $this->getPageContext($siteContext);
    return array_merge($siteContext, $templateContext);
  }
  
  /**
   * getSiteContext
   *
   * Returns an array of data that's used throughout the site.
   *
   * @return array
   * @throws HandlerException
   * @throws RepositoryException
   * @throws TransformerException
   */
  private function getSiteContext(): array
  {
    return [
      'year'    => date('Y'),
      'site'    => [
        'url'    => home_url(),
        'title'  => 'The Firefly House',
        'images' => get_stylesheet_directory_uri() . '/assets/images/',
        'logo'   => [
          'alt' => 'a firefly drawn as a single, unending line with a glowing, seven-pointed star on it\'s abdomen',
          'src' => 'firefly-glowing.jpg',
        ],
        'banner' => [
          'alt' => 'a stylistic image depicting the four classical Western elements:  earth, air, fire, and water',
          'src' => 'elements.jpg',
        ],
        'menus'  => [
          'main'   => $this->getMainMenu(),
          'footer' => $this->getFooterMenu(),
        ],
      ],
      'discord' => [
        'logo'   => get_stylesheet_directory_uri() . '/assets/images/discord.svg',
        'online' => $this->getDiscordMembersOnline(),
        'invite' => 'prRN88MWt2',
      ],
      'events'  => $this->getMeetupEvents(),
    ];
  }
  
  /**
   * getMainMenu
   *
   * Returns the MenuItems for the main menu.
   *
   * @return MenuItem[]
   * @throws RepositoryException
   */
  private function getMainMenu(): array
  {
    return $this->getMenu('main');
  }
  
  /**
   * getMenu
   *
   * Returns an array of MenuItems for the items in the menu at the
   * specified location.
   *
   * @param string $menuLocation
   *
   * @return MenuItem[]
   * @throws RepositoryException
   */
  private function getMenu(string $menuLocation): array
  {
    // Timber can give us a set of menu items, but they contain a massive
    // amount of data that we don't actually need to build our site.  so, we
    // want to use its data to construct MenuItem repositories instead, and
    // that's something that array_map can do for us very easily.  typically,
    // there's no need to specify types for short closures, but this time we
    // wanted to be as clear as possible about what was going on here.
    
    return sizeof(($items = (new TimberMenu($menuLocation))->get_items())) > 0
      ? array_map(fn(TimberMenuItem $item) => new MenuItem($item), $items)
      : [];
  }
  
  /**
   * getFooterMenu
   *
   * Returns the MenuItems for the footer menu.
   *
   * @return MenuItem[]
   * @throws RepositoryException
   */
  private function getFooterMenu(): array
  {
    $menu = $this->getMenu('footer');
    
    // now, to this menu, we want to add one or two more items.  if the
    // current visitor is logged out, we add a log in menu.  if they're logged
    // in, we'll add links to the dashboard and to log out.
    
    $additionalItems = !is_user_logged_in()
      ? $this->getLoggedOutLinks()
      : $this->getLoggedInLinks();
    
    foreach ($additionalItems as $item) {
      $menu[] = new MenuItem((object) $item);
    }
    
    return $menu;
  }
  
  /**
   * getLoggedOutLinks
   *
   * Returns an array of arrays that describe the menu items we add to the
   * footer menu when a person is logged out.
   *
   * @return array
   */
  private function getLoggedOutLinks(): array
  {
    return [
      [
        'label'   => 'Log In',
        'url'     => wp_login_url(),
        'classes' => ['log-in'],
        'current' => false,
      ],
    ];
  }
  
  /**
   * getLoggedInLinks
   *
   * Returns an array of arrays that describe the links we want to add to the
   * footer menu when the visitor is already logged in.
   *
   * @return array
   */
  private function getLoggedInLinks(): array
  {
    return [
      [
        'label'   => 'Dashboard',
        'url'     => admin_url(),
        'classes' => ['dashboard'],
        'current' => false,
      ],
      [
        'label'   => 'Log Out',
        'url'     => wp_logout_url(),
        'classes' => ['log-out'],
        'current' => false,
      ],
    ];
  }
  
  /**
   * getDiscordMembersOnline
   *
   * Hits the Discord widget API for the firefly house server to see how
   * many folks are online, though not who they are.
   *
   * @return int|null
   */
  private function getDiscordMembersOnline(): ?int
  {
    $response = wp_remote_get('https://discord.com/api/guilds/326771162548011021/widget.json');
    if (wp_remote_retrieve_response_code($response) === 200) {
      $response = json_decode(wp_remote_retrieve_body($response));
      
      // the -1 is for Carl, the bot.  if we add more bots, we'll want to
      // update this alteration to match that count.
      
      return (int) $response->presence_count - 1;
    }
    
    return null;
  }
  
  /**
   * getMeetupEvents
   *
   * Hits the Meetup API and checks for Firefly House events.
   *
   * @return Event[]
   * @throws HandlerException
   * @throws TransformerException
   * @throws RepositoryException
   */
  private function getMeetupEvents(): array
  {
    $events = get_transient('firefly-events');
    
    if (!is_array($events)) {
      $response = wp_remote_get('https://api.meetup.com/thefireflyhouse/events?page=20');
      if (wp_remote_retrieve_response_code($response) === 200) {
        
        // if we got a response from the Meetup API, then we'll extract the
        // JSON body from it and save it in the database.  this is to help
        // avoid a situation where we have no events to show because the API
        // was down or something.
        
        $eventJson = wp_remote_retrieve_body($response);
        $this->updateOption('events-json', $eventJson);
      } else {
        
        // otherwise, we'll get whatever we got last time or an empty array
        // as a JSON string.  it's important that we return the string and not
        // the array itself because the json_decode() call will have a problem
        // if it's not a string.
        
        $eventJson = $this->getOption('event-json', '[]');
      }
      
      foreach (json_decode($eventJson, true) as $event) {
        $events[] = new Event($event);
      }
      
      set_transient('firefly-events', $events, 3600);
    }
  
    return $events;
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
  abstract protected function getPageContext(array $siteContext): array;
  
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
    return ($post = get_post($this->postId)) instanceof WP_Post
      ? apply_filters('the_content', $post->post_content)
      : '';
  }
  
  /**
   * getOptionNames
   *
   * Inherited from the OptionsManagementTrait, this method returns an array
   * of option names (without their prefix) specifying the exact list of things
   * this object messes with in the database.  this is marked final because
   * extensions of this object (A) shouldn't be messing with these objects, and
   * (B) definitely shouldn't be changing this list.
   *
   * @return array
   */
  final protected function getOptionNames(): array
  {
    return ['version', 'twigs', 'events-json'];
  }
  
  /**
   * getOptionNamePrefix
   *
   * Inherited from the OptionsManagementTrait, this method returns a prefix
   * that is added to option names internal to the methods of the trait so that
   * we don't need to type it over and over again in this object.  marked final
   * so that extensions of this object are unable to change this prefix.
   *
   * @return string
   */
  final public function getOptionNamePrefix(): string
  {
    return Theme::SLUG . '-';
  }
}
