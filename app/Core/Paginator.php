<?php

namespace App\Core;

final class Paginator
{
    public int $page;
    public int $pageSize;
    public int $total;

    public function __construct(int $page, int $pageSize, int $total)
    {
        $this->page = max(1, $page);
        $this->pageSize = max(1, $pageSize);
        $this->total = max(0, $total);
    }

    public function pages()
    {
        return max(1, (int)ceil($this->total / $this->pageSize));
    }

    public function offset()
    {
        return ($this->page - 1) * $this->pageSize;
    }

    public function hasPrev()
    {
        return $this->page > 1;
    }

    public function hasNext()
    {
        return $this->page < $this->pages();
    }

    public function prevPage()
    {
        return max(1, $this->page - 1);
    }

    public function nextPage()
    {
        return min($this->pages(), $this->page + 1);
    }
}
