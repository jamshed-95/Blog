{extends file="layout.tpl"}

{block name="content"}
    <div class="page-header">
        <div>
            <h1 class="page-title">{$category.name}</h1>
            {if $category.description}
                <div class="muted">{$category.description}</div>
            {/if}
        </div>
        <a class="btn btn--ghost" href="{$baseUrl}/index.php?r=home">На главную</a>
    </div>

    <div class="toolbar">
        <div class="toolbar__label">Сортировка:</div>
        <a class="chip {if $sort === 'date'}chip--active{/if}" href="{$baseUrl}/index.php?r=category&id={$category.id}&sort=date&page=1">По дате</a>
        <a class="chip {if $sort === 'views'}chip--active{/if}" href="{$baseUrl}/index.php?r=category&id={$category.id}&sort=views&page=1">По просмотрам</a>
    </div>

    {if $posts|@count === 0}
        <div class="alert">В этой категории пока нет статей.</div>
    {else}
        <div class="grid grid--dense">
            {foreach $posts as $post}
                <article class="card">
                    <a class="card__image" href="{$baseUrl}/index.php?r=post&id={$post.id}">
                        <img src="{$post.image_url|default:'https://picsum.photos/800/500'}" alt="{$post.title}">
                    </a>
                    <div class="card__body">
                        <div class="meta">
                            <span>{$post.created_at|date_format:"%d.%m.%Y"}</span>
                            <span>Просмотры: {$post.views}</span>
                        </div>
                        <h3 class="card__title">
                            <a href="{$baseUrl}/index.php?r=post&id={$post.id}">{$post.title}</a>
                        </h3>
                        {if $post.description}
                            <div class="card__desc">{$post.description}</div>
                        {/if}
                        <a class="link" href="{$baseUrl}/index.php?r=post&id={$post.id}">Читать</a>
                    </div>
                </article>
            {/foreach}
        </div>

        {if $pagination.pages > 1}
            <nav class="pagination">
                <a class="page-btn {if !$pagination.hasPrev}page-btn--disabled{/if}"
                   href="{$baseUrl}/index.php?r=category&id={$category.id}&sort={$sort}&page={$pagination.prevPage}">
                    Назад
                </a>

                {section name=i start=1 loop=$pagination.pages+1}
                    {assign var=p value=$smarty.section.i.index}
                    <a class="page-num {if $p == $pagination.page}page-num--active{/if}"
                       href="{$baseUrl}/index.php?r=category&id={$category.id}&sort={$sort}&page={$p}">
                        {$p}
                    </a>
                {/section}

                <a class="page-btn {if !$pagination.hasNext}page-btn--disabled{/if}"
                   href="{$baseUrl}/index.php?r=category&id={$category.id}&sort={$sort}&page={$pagination.nextPage}">
                    Вперёд
                </a>
            </nav>
        {/if}
    {/if}
{/block}
