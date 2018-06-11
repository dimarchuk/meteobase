<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use App\Exceptions;

/**
 * Class KNDaily
 * @package App\Helpers
 */
class KNDaily
{
    /**
     * @var array
     */
    private $date = [];

    /**
     * @var false|string
     */
    private $dateBeforeFromDate;

    /**
     * @var Collection
     */
    private $allData;

    /**
     * @var array
     */
    private $allDataSort = [];

    /**
     * @var array
     */
    private $categories = [];

    /**
     * @var array
     */
    private $dataForTable = [];

    /**
     * KNDaily constructor.
     * @param Collection $allData
     * @param array $date
     * @param array $categories
     */
    public function __construct(Collection $allData, array $date, array $categories)
    {
        $this->date = $date;
        $this->dateBeforeFromDate = date('Y-m-d', (strtotime($date['dateFrom']) - (60 * 60 * 24)));
        $this->allData = $allData;
        array_splice($categories, 0, 4);
        $this->categories = $categories;
        $this->createDefArr();
    }

    /**
     * @return $this
     */
    private function createDefArr()
    {
        $dateFrom = strtotime($this->date['dateFrom']);
        $dateTo = strtotime($this->date['dateTo']);
        while ($dateFrom <= $dateTo) {
            foreach ($this->allData as $item) {
                if (strtotime($item->DATE_CH) == $dateFrom) {
                    $newData = [
                        'NAME_OBL' => $item->NAME_OBL,
                        'NAME_ST' => $item->NAME_ST,
                        'IND_ST' => $item->IND_ST,
                        'DATE_CH' => $item->DATE_CH,
                    ];
                    in_array($newData, $this->dataForTable) ?: $this->dataForTable[] = $newData;
                }
            }
            $dateFrom = $dateFrom + 60 * 60 * 24;
        }
        return $this;
    }

    /**
     * Call methods for calculating data
     */
    public function calculate()
    {
        //В запитах розібратись з датою, зробити щоб виводило на всі вказані дти
        foreach ($this->dataForTable as &$forTable) {
            $date = $forTable['DATE_CH'];
            $prevDate = date('Y-m-d',strtotime($forTable['DATE_CH']) - (60 * 60 * 24));
//
            $allDataSort = [];
            //Для @var $forTable беру колекцію відсортовану по індексу станції та даті
            $allDataSort[] = collect($this->allData)
                ->where('IND_ST', '=', $forTable['IND_ST'])
                ->where('SROK_CH', '=', 21)
                ->where('DATE_CH', '=', $prevDate)
                ->first();
            $tmp = collect($this->allData)
                ->where('IND_ST', '=', $forTable['IND_ST'])
                ->whereIn('SROK_CH', [0, 3, 6, 9, 12, 15, 18])
                ->where('DATE_CH', '=', $date)//$this->date['dateFrom']
                ->all();

            $this->allDataSort = array_merge($allDataSort, $tmp);

            //виклик функцій обрахунку за обраними користувачем параметрами
            foreach ($this->categories as $item) {
                try {
                    $newItems = call_user_func([__CLASS__, strtolower($item['code_col_name'])], $this->allDataSort);
                    $forTable = array_merge($forTable, $newItems);
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                }
            }
        }
        return $this->dataForTable;
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function ttt($srokDataForTableStr)
    {
        $countItems = count($srokDataForTableStr);
        $sum = 0;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'TTT')) {
                $sum += $item->TTT;
            }
        }
        $avg = round($sum / $countItems, 1);
        return [
            "TTT" => $avg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function tmin($srokDataForTableStr)
    {
        $tmin = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'SROK_CH') && property_exists($item, 'TMIN')) {
                if ($item->SROK_CH == 6) {
                    $tmin = $item->TMIN;
                }
            }
        }
        return [
            "TMIN" => $tmin
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function tmax($srokDataForTableStr)
    {
        $tmax = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'SROK_CH') && property_exists($item, 'TMAX')) {
                if ($item->SROK_CH == 18) {
                    $tmax = $item->TMAX;
                }
            }
        }
        return [
            "TMAX" => $tmax
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function tdtdtd($srokDataForTableStr)
    {
        $countItems = count($srokDataForTableStr);
        $sum = 0;
        $avg = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'TDTDTD')) {
                $sum += $item->TDTDTD;
            }
        }
        $avg = round($sum / $countItems, 1);
        return [
            "TDTDTD" => $avg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function tgtg($srokDataForTableStr)
    {
        $tg = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'SROK_CH') && property_exists($item, 'TGTG')) {
                if ($item->SROK_CH == 6 && $item->TGTG != null) {
                    $tg = $item->TGTG;
                }
            }
        }
        return [
            "TGTG" => $tg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function t2t2($srokDataForTableStr)
    {
        $t2t2 = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'SROK_CH') && property_exists($item, 'T2T2')) {
                if ($item->SROK_CH == 6 && $item->T2T2 != null) {
                    $t2t2 = $item->T2T2;
                }
            }
        }
        return [
            "T2T2" => $t2t2
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function rrr1($srokDataForTableStr)
    {
        $rrr1 = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'SROK_CH') && property_exists($item, 'RRR1')) {
                if ($item->SROK_CH == 6 && $item->RRR1 != null || $item->SROK_CH == 18 && $item->RRR1 != null) {
                    $rrr1 += $item->RRR1;
                }
            }
        }
        return [
            "RRR1" => $rrr1
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function ff_ser($srokDataForTableStr)
    {
        $countItems = count($srokDataForTableStr);
        $sum = 0;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'FF')) {
                $sum += $item->FF;
            }
        }
        $avg = round($sum / $countItems, 1);
        return [
            "FF_ser" => $avg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function ff_max($srokDataForTableStr)
    {
        $max = 0;
        $currentFF = 0;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'FF')) {
                $currentFF = $item->FF;
            }
            if ($max < $currentFF) $max = $currentFF;
        }
        return [
            "FF_max" => $max
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function n($srokDataForTableStr)
    {
        $countItems = count($srokDataForTableStr);
        $sum = 0;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'N')) {
                $sum += $item->N;
            }
        }
        $avg = ceil($sum / $countItems);
        return [
            "N" => $avg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function ppp($srokDataForTableStr)
    {
        $countItems = count($srokDataForTableStr);
        $sum = 0;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'PPP')) {
                $sum += $item->PPP;
            }
        }
        $avg = round($sum / $countItems, 1);
        return [
            "PPP" => $avg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function p0p0p0p0($srokDataForTableStr)
    {
        $countItems = count($srokDataForTableStr);
        $sum = 0;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'P0P0P0P0')) {
                $sum += $item->P0P0P0P0;
            }
        }
        $avg = round($sum / $countItems, 1);
        return [
            "P0P0P0P0" => $avg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function pppp($srokDataForTableStr)
    {
        $countItems = count($srokDataForTableStr);
        $sum = 0;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'PPPP')) {
                $sum += $item->PPPP;
            }
        }
        $avg = round($sum / $countItems, 1);
        return [
            "PPPP" => $avg
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function hsnow($srokDataForTableStr)
    {
        $hsnow = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'SROK_CH') && property_exists($item, 'HSNOW')) {
                if ($item->SROK_CH == 6 && $item->HSNOW != null) {
                    $hsnow = $item->HSNOW;
                }
            }
        }
        return [
            "HSNOW" => $hsnow
        ];
    }

    /**
     * @param $srokDataForTableStr
     * @return array
     */
    private function sss($srokDataForTableStr)
    {
        $sss = null;
        foreach ($srokDataForTableStr as $item) {
            $item = (object)$item;
            if (property_exists($item, 'SROK_CH') && property_exists($item, 'SSS')) {
                if ($item->SROK_CH == 3 && $item->SSS != null) {
                    $sss = $item->SSS;
                }
            }
        }
        return [
            "SSS" => $sss
        ];
    }
}