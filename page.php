<?php

use Dashifen\FireflyTheme\Theme;
use Dashifen\FireflyTheme\Templates\Page as Template;
use Dashifen\FireflyTheme\Templates\Framework\FireflyTemplateException;

try {
  (new Template)->render(Theme::isDebug());
} catch (FireflyTemplateException $e) {
  Theme::catcher($e);
}
