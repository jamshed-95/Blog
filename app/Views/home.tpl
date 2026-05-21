{extends file="layout.tpl"}

{block name="content"}
    <h1 class="page-title">Блог</h1>

    {if $categories|@count === 0}
        <div class="alert">Пока нет категорий со статьями. Запустите сидинг: <a href="{$baseUrl}/index.php?r=seed">?r=seed</a></div>
    {else}
        {foreach $categories as $category}
            <section class="category-block">
                <div class="category-block__header">
                    <div>
                        <h2 class="category-title">{$category.name}</h2>
                        {if $category.description}
                            <div class="category-description">{$category.description}</div>
                        {/if}
                    </div>
                    <a class="btn btn--ghost" href="{$baseUrl}/index.php?r=category&id={$category.id}">Все статьи</a>
                </div>

                <div class="grid">
                    {foreach $category.posts as $post}
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
            </section>
        {/foreach}
    {/if}
{/block}
