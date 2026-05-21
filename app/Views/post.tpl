{extends file="layout.tpl"}

{block name="content"}
    <div class="breadcrumbs">
        <a href="{$baseUrl}/index.php?r=home">Главная</a>
        <span>/</span>
        <span>Статья</span>
    </div>

    <article class="post">
        {if $post.image_url}
            <div class="post__image">
                <img src="{$post.image_url}" alt="{$post.title}">
            </div>
        {/if}

        <h1 class="page-title">{$post.title}</h1>

        <div class="meta meta--big">
            <span>{$post.created_at|date_format:"%d.%m.%Y %H:%M"}</span>
            <span>Просмотры: {$post.views}</span>
        </div>

        {if $post.categories|@count > 0}
            <div class="chips">
                {foreach $post.categories as $c}
                    <a class="chip" href="{$baseUrl}/index.php?r=category&id={$c.id}">{$c.name}</a>
                {/foreach}
            </div>
        {/if}

        {if $post.description}
            <div class="post__lead">{$post.description}</div>
        {/if}

        <div class="post__body">
            {foreach $post.paragraphs as $p}
                <p>{$p}</p>
            {/foreach}
        </div>
    </article>

    <section class="similar">
        <div class="section-title">Похожие статьи</div>
        {if $similar|@count === 0}
            <div class="muted">Пока нет похожих статей.</div>
        {else}
            <div class="grid grid--dense">
                {foreach $similar as $p}
                    <article class="card card--small">
                        <a class="card__image" href="{$baseUrl}/index.php?r=post&id={$p.id}">
                            <img src="{$p.image_url|default:'https://picsum.photos/800/500'}" alt="{$p.title}">
                        </a>
                        <div class="card__body">
                            <div class="meta">
                                <span>{$p.created_at|date_format:"%d.%m.%Y"}</span>
                                <span>Просмотры: {$p.views}</span>
                            </div>
                            <h3 class="card__title">
                                <a href="{$baseUrl}/index.php?r=post&id={$p.id}">{$p.title}</a>
                            </h3>
                        </div>
                    </article>
                {/foreach}
            </div>
        {/if}
    </section>
{/block}
