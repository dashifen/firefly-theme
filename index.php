<!doctype html>
<html class="no-js" <?php language_attributes() ?>>
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class() ?>>
<div class="container">
  <a href="#content" class="screen-reader-text">Skip to the Content</a>
  <header class="banner" aria-labelledby="page-title">
    <a href="/">
      <h1 id="page-title">The Firefly House</h1>
    </a>

    <div class="toggler">
      <a id="toggler" class="screen-reader-text">Click or touch to toggle main menu availability</a>
      <div class="mid-line"></div>
    </div>

    <nav class="main-menu" aria-label="main menu">
      <ul>
        <li class="menu-item"><a class="menu-item-link" href="/">Home</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">About</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Events</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Tarot</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Policies</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Contact</a></li>
      </ul>
    </nav>
  </header>

  <div class="body">
    <main id="content" class="main"></main>
    <section class="main__sidebar"></section>
    <footer class="footer"></footer>
  </div>
</div>
<?php wp_footer() ?>
</body>
</html>
