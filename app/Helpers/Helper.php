<?php

namespace App\Helpers;

class Helper
{
    /**
     * Create links for pagination
     *
     * @param string $url
     * @param int $countPages
     * @param int $currentPage
     * @param bool $extra
     * @return string
     */
    public static  function generateLinksForPagination(string $url, int $countPages, $currentPage = 1, $showArrows = false)
    {
        $j = 1 + ($currentPage < 2 || ($currentPage > $countPages - 3) ? 0 : $currentPage - 2 - ($currentPage == $countPages - 3));

        for ($i = 1, $l = $countPages > 7 ? 7 : $countPages; $i <= $l; $i++, $j++) {

            if ($i > 3 && $countPages > 7) {
                if ($i == 4) {
                    $result[] = '<li class="disabled"><span>...</span></li>';
                    continue;
                }
                $k = $countPages - ($l - $i);
            } else $k = $j;

            if ($currentPage == $k) {
                $result[] = '<li class="active"><a href="' . $url . '?page=' . $k . '">' . $k . '</a></li>';
            } else $result[] = '<li><a href="' . $url . '?page=' . $k . '">' . $k . '</a></li>';
        }

        if ($showArrows) {
            array_unshift($result, '<li><a' . ($currentPage != 1 ? ' href="' . $url . '?page=' . ($currentPage - 1) . '"' : '') . ' title="Предыдущая страница">«</a></li>');
            $result[] = '<li><a' . ($currentPage != $countPages ? ' href="' . $url . '?page=' . ($currentPage + 1) . '"' : '') . ' title="Следующая страница">»</a></li>';
        }

        return implode("\n", $result);
    }
}