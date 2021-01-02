<!doctype html>
<html class="no-js" <?php language_attributes() ?>>
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class() ?>>
<div id="vue-root">
  <a href="#content" class="screen-reader-text">Skip to the Content</a>
  <header class="banner" aria-labelledby="page-title">
    <h1 id="page-title">The Firefly House</h1>
    <ul class="main-menu" aria-label="main menu">
      <li><a href="/">Home</a></li>
      <li><a href="/">About</a></li>
      <li><a href="/">Events</a></li>
      <li><a href="/">Tarot</a></li>
      <li><a href="/">Policies</a></li>
      <li><a href="/">Contact</a></li>
    </ul>
  </header>
      
  <main id="content" class="main"></main>
  <section class="main__sidebar"></section>
  <footer class="footer"></footer>
</div>
<?php wp_footer() ?>
</body>
</html>
