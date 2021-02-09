<?php

namespace Dashifen\FireflyTheme\Repositories;

use Dashifen\Repository\Repository;
use Dashifen\Repository\RepositoryException;
use Dashifen\WPHandler\Traits\FormattedDateTimeTrait;

/**
 * Class Event
 *
 * @property-read string $name
 * @property-read string $date
 * @property-read string $time
 * @property-read string $link
 * @property-read string $excerpt
 * @property-read string $description
 * @property-read int    $attending
 * @property-read int    $datetime
 *
 * @package Dashifen\FireflyTheme\Repositories
 */
class Event extends Repository
{
  use FormattedDateTimeTrait;
  
  protected string $name;
  protected string $date;
  protected string $time;
  protected string $link;
  protected string $excerpt;
  protected string $description;
  protected int $attending;
  
  // the FormattedDateTimeTrait has a private method named getTimestamp, so if
  // we more accurately named this property, our Repository code would think
  // that the trait's method is the getter for it.  instead, we'll name it
  // datetime, which isn't entirely inaccurate, and that'll dodge the name of
  // that private method.
  
  protected int $datetime;
  
  /**
   * Event constructor.
   *
   * Receives an array of data from the Meetup API and loads it all into the
   * properties of this object.
   *
   * @param array $event
   *
   * @throws RepositoryException
   */
  public function __construct(array $event)
  {
    parent::__construct(
      [
        'name'        => $event['name'],
        'link'        => $event['link'],
        'excerpt'     => $event['description'],
        'description' => $event['description'],
        'attending'   => $event['yes_rsvp_count'],
        'datetime'    => $event['time'] / 1000,    // meetup is in milliseconds
      ]
    );
    
    $this->date = $this->getFormattedDate($this->datetime);
    $this->time = $this->getFormattedTime($this->datetime);
  }
  
  /**
   * getRequiredProperties
   *
   * Returns an array of property names that must be non-empty after
   * construction.
   *
   * @return array
   */
  protected function getRequiredProperties(): array
  {
    return ['name', 'link', 'description', 'attending', 'datetime'];
  }
  
  /**
   * setName
   *
   * Sets the name property.
   *
   * @param string $name
   *
   * @return void
   */
  protected function setName(string $name): void
  {
    $this->name = $name;
  }
  
  /**
   * setLink
   *
   * Sets the link property.
   *
   * @param string $link
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setLink(string $link): void
  {
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
      throw new RepositoryException(
        'Invalid link: ' . $link,
        RepositoryException::INVALID_VALUE
      );
    }
    
    $this->link = $link;
  }
  
  /**
   * setExcerpt
   *
   * Using the full description, sets an excerpt for this event.
   *
   * @param string $description
   *
   * @return void
   */
  protected function setExcerpt(string $description): void
  {
    // for now, we want our excerpts to be the first paragraph from Meetup.
    // we'll instruct meetup authors that their first paragraph will show up
    // on the site's homepage (at least) and so they should think about how
    // they write those.
    
    $paraEnd = strpos($description, '</p>');
    $this->excerpt = substr($description, 0, $paraEnd + 4);
  }
  
  /**
   * setDescription
   *
   * Sets the description property.
   *
   * @param string $description
   *
   * @return void
   */
  protected function setDescription(string $description): void
  {
    $this->description = $description;
  }
  
  /**
   * setAttending
   *
   * Sets the attending property.
   *
   * @param string $attending
   *
   * @return void
   */
  protected function setAttending(string $attending): void
  {
    $this->attending = $attending;
  }
  
  /**
   * setDatetime
   *
   * Sets the datetime property.
   *
   * @param string $datetime
   *
   * @return void
   */
  protected function setDatetime(string $datetime): void
  {
    $this->datetime = $datetime;
  }
}
