<?php

namespace Dashifen\FireflyTheme\Templates;

use Timber\Timber;
use Dashifen\FireflyTheme\Repositories\Event;
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
        'title'    => get_the_title(),
        'content'  => $this->getContent(),
        'calendar' => $this->getCalendar(),
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
    $events = '';
    $content = parent::getContent();
    if (sizeof($this->siteContext['events']) > 0) {
      $events = Timber::compile('@partials/calendar.twig', $this->getCalendar());
      $events .= Timber::compile('@partials/events.twig', $this->siteContext);
    }
    
    return $content . $events;
  }
  
  /**
   * generateCalendar
   *
   * Generates the data needed to display an on-screen calendar for the
   * current month.
   *
   * @return array
   */
  private function getCalendar(): array
  {
    /** @var Event $nextEvent */
    
    $thisMonth = date('n');
    $nextEvent = $this->siteContext['events'][0];
    $month = date('n', $nextEvent->datetime) !== $thisMonth
      
      // if we want to show next month's calendar, we add one to this month.
      // but if this month was december, now we have a month #13 which doesn't
      // exist.  so, when that happens, we reset the month to 1.  we could do
      // it with modulo arithmetic, but this is a little more clear, we think.
      
      ? (++$thisMonth === 13 ? $thisMonth = 1 : $thisMonth)
      : $thisMonth;
    
    $timestamp = mktime(0, 0, 0, $month, 1, date('Y'));
    
    return [
      'start'  => ($start = date('w', $timestamp)),
      'end'    => ($end = date('t', $timestamp)),
      'days'   => $this->getDays(),
      'month'  => $this->getAbbreviatedString(date('F', $timestamp)),
      'weeks'  => ceil(($start + $end) / 7),
      'image'  => $this->siteContext['site']['images'] . 'event.svg',
      'events' => $this->getEvents(),
    ];
  }
  
  /**
   * getDays
   *
   * Returns an array of day names ready for abbreviation on smaller
   * screens.
   *
   * @return array
   */
  private function getDays(): array
  {
    $month = date('n');
    $year = date('Y');
    for ($i = 0; $i < 7; $i++) {
      $days[] = $this->getAbbreviatedString(
        date('l', mktime(0, 0, 0, $month, $i, $year))
      );
    }
    
    return $days ?? [];
  }
  
  /**
   * getAbbreviatedString
   *
   * Returns the string parameter with its first three letters wrapped in a
   * <span> tag to abbreviate information on smaller screens.
   *
   * @param string $string
   *
   * @return string
   */
  private function getAbbreviatedString(string $string): string
  {
    return sprintf(
      '<span class="abbr">%s</span><span>%s</span>',
      substr($string, 0, 3),
      substr($string, 3)
    );
  }
  
  private function getEvents(): array
  {
    foreach ($this->siteContext['events'] as $event) {
      /** @var Event $event */
      
      $events[date('j', $event->datetime)] = $event->name;
    }
    
    return $events ?? [];
  }
}
