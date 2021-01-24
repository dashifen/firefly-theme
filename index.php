<?php

use Dashifen\FireflyTheme\Theme;
use Dashifen\FireflyTheme\Templates\Homepage;
use Dashifen\FireflyTheme\Templates\Framework\FireflyTemplateException;

try {
  $homepage = new Homepage();
  $homepage->render(Theme::isDebug());
} catch (FireflyTemplateException $e) {
  Theme::catcher($e);
}
