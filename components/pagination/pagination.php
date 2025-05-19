<?php

class Pagination
{
    private static $cssIncluded = false;

    public static function includeCSS()
    {
        if (!self::$cssIncluded) {
            echo '<link rel="stylesheet" href="/components/pagination/css/pagination.css">';
            self::$cssIncluded = true;
        }
    }

    public static function render(int $currentPage, int $totalPages, string $pageParam = 'page', array $additionalParams = []): void
    {
        self::includeCSS();

        if ($totalPages <= 1) return;

        $queryParams = $additionalParams;
        $baseQueryString = '';

        if (!empty($queryParams)) {
            foreach ($queryParams as $key => $value) {
                $baseQueryString .= "&{$key}={$value}";
            }
        }

        echo '<div class="pagination">';

        if ($currentPage > 1) {
            echo '<a href="?' . $pageParam . '=' . ($currentPage - 1) . $baseQueryString . '" class="page-link">&laquo; Previous</a>';
        }

        $range = 5;
        $start = max(1, $currentPage - floor($range / 2));
        $end = min($totalPages, $start + $range - 1);

        if ($end == $totalPages) {
            $start = max(1, $totalPages - $range + 1);
        }

        if ($start > 1) {
            echo '<a href="?' . $pageParam . '=1' . $baseQueryString . '" class="page-link">1</a>';
            if ($start > 2) {
                echo '<span class="page-ellipsis">...</span>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $activeClass = $i === $currentPage ? 'active' : '';
            echo '<a href="?' . $pageParam . '=' . $i . $baseQueryString . '" class="page-link ' . $activeClass . '">' . $i . '</a>';
        }

        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                echo '<span class="page-ellipsis">...</span>';
            }
            echo '<a href="?' . $pageParam . '=' . $totalPages . $baseQueryString . '" class="page-link">' . $totalPages . '</a>';
        }

        if ($currentPage < $totalPages) {
            echo '<a href="?' . $pageParam . '=' . ($currentPage + 1) . $baseQueryString . '" class="page-link">Next &raquo;</a>';
        }

        echo '</div>';
    }
}
