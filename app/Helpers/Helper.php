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
     * @param bool $showArrows
     * @return string
     */
    public static function generateLinksForPagination(string $url, int $countPages, $currentPage = 1, $showArrows = false)
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
            array_unshift($result, '<li><a' . ($currentPage != 1 ? ' href="' . $url . '?page=1"' : '') . ' title="Предыдущая страница">«</a></li>');
            $result[] = '<li><a' . ($currentPage != $countPages ? ' href="' . $url . '?page=' . $countPages . '"' : '') . ' title="Следующая страница">»</a></li>';
        }

        return implode("\n", $result);
    }


    /**
     * Decode direction of the wind, column from DD to RUMB
     *
     * @param $dataFromSrok
     */
    public function decodeDirectionWind($dataFromSrok)
    {
        $code = \DB::table('WEATHER2')
            ->select('DD', 'RUMB')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataDD = $dataFromSrok[$dataItem]->DD;

            for ($codeItem = 2; $codeItem <= 37; $codeItem++) {
                $codeDD = explode('-', $code[$codeItem]->DD);

                if ($dataDD >= $codeDD[0] && $dataDD <= $codeDD[1]) {
                    $dataFromSrok[$dataItem]->DD = $code[$codeItem]->RUMB;
                }
            }
        }
    }

    public function decodeBaricЕendency($dataFromSrok)
    {
        $code = \DB::table('WEATHER')
            ->select('CODE_KN01', 'A')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataA = $dataFromSrok[$dataItem]->A;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeA =  $code[$codeItem]->CODE_KN01;
                if ($dataA == $codeA) {
                    $dataFromSrok[$dataItem]->A = $code[$codeItem]->A;
                }
            }
        }
    }
}
