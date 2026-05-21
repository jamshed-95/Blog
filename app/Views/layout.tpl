<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title|default:'Блог'}</title>
    <link rel="stylesheet" href="{$baseUrl}/assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container header__inner">
        <a class="logo" href="{$baseUrl}/index.php?r=home">Blog</a>
        <nav class="nav">
            <a class="nav__link" href="{$baseUrl}/index.php?r=home">Главная</a>
            <a class="nav__link" href="{$baseUrl}/index.php?r=seed">Сидинг</a>
        </nav>
    </div>
</header>

<main class="container">
    {block name="content"}{/block}
</main>

<footer class="footer">
    <div class="container footer__inner">Copyright © {$smarty.now|date_format:"%Y"}</div>
</footer>
</body>
</html>
