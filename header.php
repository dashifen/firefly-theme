<?php

use Dashifen\FireflyTheme\Theme;
use Dashifen\FireflyTheme\Templates\Partial as Template;
use Dashifen\FireflyTheme\Templates\Framework\FireflyTemplateException;

try {
  $template = new Template('header.twig');
  $template->render(Theme::isDebug());
} catch (FireflyTemplateException $e) {
  Theme::catcher($e);
}
