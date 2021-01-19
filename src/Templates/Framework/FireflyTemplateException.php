<?php

namespace Dashifen\FireflyTheme\Templates\Framework;

use Dashifen\WPTemplates\TemplateException;

class FireflyTemplateException extends TemplateException
{
  // to "dodge" whatever constants the TemplateException object defines, we'll
  // start our definitions at 100.  it's highly unlikely that we'll ever need
  // 100 constants in the parent class, and if we ever do, then a PHP error
  // will let us know to add an order of magnitude to these.
  
  public const UNKNOWN_TWIG = 100;
  public const UNKNOWN_CONTEXT = 101;
}
