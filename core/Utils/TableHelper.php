<?php

class TableHelper
{
    public static function sortLink(
        string $label,
        string $column,
        string $currentSort,
        string $currentDir
    ): string {
        $dir = ($currentSort === $column && $currentDir === 'asc')
            ? 'desc'
            : 'asc';

        $icon = '';
        if ($currentSort === $column) {
            $icon = $currentDir === 'asc' ? ' <span class="sort-icon-asc">^</span>' : ' <span class="sort-icon-desc">^</span>';
        }

        $url = ListStateHelper::url('', [
            'sort' => $column,
            'dir'  => $dir,
            'page' => 1
        ]);

        return "<a href=\"{$url}\" class=\"inline-flex items-center gap-1 hover:underline font-medium\">
                  {$label}{$icon}
                </a>";
    }
}
?>