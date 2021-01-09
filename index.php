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
  <a href="/" class="no-js-menu-link screen-reader-text">Go to the Main Menu</a>
  <header id="banner" class="banner" aria-labelledby="page-title">
    <a href="/">
      <h1 id="page-title">The Firefly House</h1>
    </a>
    
    <menu-toggle></menu-toggle>
    <nav id="main-menu" class="main-menu" aria-label="main menu">
      <ul class="menu-item-container">
        <li class="menu-item"><a class="menu-item-link" href="/">Home</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">About</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Events</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Tarot</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Policies</a></li>
        <li class="menu-item"><a class="menu-item-link" href="/">Contact</a></li>
      </ul>
    </nav>
    
    <figure role="presentation">
      <img src="<?= get_stylesheet_directory_uri() . '/assets/images/elements.jpg' ?>" alt="a stylistic image depicting the four classical Western elements:  earth, air, fire, and water">
    </figure>
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
