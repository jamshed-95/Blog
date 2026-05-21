{extends file="../layout.tpl"}

{block name="content"}
    <h1 class="page-title">{$title|default:'Страница не найдена'}</h1>
    <div class="alert">Такой страницы нет. <a href="{$baseUrl}/index.php?r=home">На главную</a></div>
{/block}
