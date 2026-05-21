{extends file="layout.tpl"}

{block name="content"}
    <h1 class="page-title">Сидинг</h1>

    <div class="alert alert--success">
        <div>Категории: {$counts.categories}</div>
        <div>Статьи: {$counts.posts}</div>
    </div>

    <div class="stack">
        <a class="btn" href="{$baseUrl}/index.php?r=home">Открыть сайт</a>
        <a class="btn btn--danger" href="{$baseUrl}/index.php?r=seed&reset=1">Пересоздать демо-данные</a>
    </div>
{/block}
