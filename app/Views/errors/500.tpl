{extends file="../layout.tpl"}

{block name="content"}
    <h1 class="page-title">{$title|default:'Ошибка'}</h1>
    <div class="alert">
        {if $message}{$message}{else}Произошла ошибка приложения.{/if}
    </div>
    <div class="stack">
        <a class="btn" href="{$baseUrl}/index.php?r=home">На главную</a>
        <a class="btn btn--ghost" href="{$baseUrl}/index.php?r=seed">Сидинг</a>
    </div>
{/block}
