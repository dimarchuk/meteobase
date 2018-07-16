<?php


namespace App\Exports;

use DB;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\UserCategory;
use App\Helpers\{
    Helper,
    KNDaily
};

/**
 * Class KndailyExport
 * @package App\Exports
 */
class KndailyExport implements FromView, ShouldAutoSize
{
    /**
     * @var array
     */
    private $categories = [
        [
            'col_name' => 'Назва області',
            'short_col_name' => 'Назва області',
            'code_col_name' => 'NAME_OBL'
        ],
        [
            'col_name' => 'Назва станції',
            'short_col_name' => 'Назва станції',
            'code_col_name' => 'NAME_ST'
        ],
        [
            'col_name' => 'Індекс',
            'short_col_name' => 'Індекс',
            'code_col_name' => 'IND_ST'
        ],
        [
            'col_name' => 'Дата',
            'short_col_name' => 'Дата',
            'code_col_name' => 'DATE_CH'
        ],
        [
            'col_name' => 'Температура повітря, середня',
            'short_col_name' => 'T сер, &deg;С',
            'code_col_name' => 'TTT'
        ],
        [
            'col_name' => 'Мінімальна температура',
            'short_col_name' => 'Tmin',
            'code_col_name' => 'TMIN'
        ],
        [
            'col_name' => 'Максимальна температура',
            'short_col_name' => 'Тmax',
            'code_col_name' => 'TMAX'
        ],
        [
            'col_name' => 'Температура точки роси, середня',
            'short_col_name' => 'Тсер. роси',
            'code_col_name' => 'TDTDTD'
        ],
        [
            "col_name" => "Tmin поверхні грунту",
            "short_col_name" => "Тmin грунту",
            "code_col_name" => "TGTG"
        ],
        [
            "col_name" => "Тmin грунту на висоті 2 см",
            "short_col_name" => "Тmin, 2см",
            "code_col_name" => "T2T2"
        ],
        [
            "col_name" => "Добова кількість опадів",
            "short_col_name" => "Добова сума опадів, мм",
            "code_col_name" => "RRR1"
        ],
        [
            "col_name" => "Середян швидкість вітру",
            "short_col_name" => "Шв. вітру сер., м/с",
            "code_col_name" => "FF_ser"
        ],
        [
            "col_name" => "Максимальна швидкість вітру",
            "short_col_name" => "Шв. вітру макс., м/с",
            "code_col_name" => "FF_max"
        ],
        [
            "col_name" => "Загальна кількість хмар",
            "short_col_name" => "Заг к-сть хмар",
            "code_col_name" => "N"
        ],
        [
            "col_name" => "Зачення баричної тенденції",
            "short_col_name" => "Знач. бар. тенд, гПа",
            "code_col_name" => "PPP"
        ],
        [
            "col_name" => "Тиск повітря, середній",
            "short_col_name" => "Тиск, гПа",
            "code_col_name" => "P0P0P0P0"
        ],
        [
            "col_name" => "Тиск повітря зведений до сер. рівня моря, середній",
            "short_col_name" => "Тиск прив, гПа",
            "code_col_name" => "PPPP"
        ],
        [
            "col_name" => "Висота снігу",
            "short_col_name" => "Вис. снігу, см",
            "code_col_name" => "HSNOW"
        ],
        [
            "col_name" => "Тривалість сонячного сяйва",
            "short_col_name" => "Трив. сон. сяйва, год",
            "code_col_name" => "SSS"
        ]
    ];

    /**
     * @var array
     */
    private $collumns = ['NAME_OBL', 'NAME_ST', 'srok.IND_ST', 'DATE_CH', 'SROK_CH', 'TTT', 'TMIN', 'TMAX', 'TDTDTD', 'TGTG', 'T2T2', 'RRR1', 'FF', 'N', 'PPP', 'P0P0P0P0', 'PPPP', 'HSNOW', 'SSS'];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $helper = new Helper();

        $uId = Auth::getUser()->getAuthIdentifier();

        if (DB::table('user_categories')->where('user_id', $uId)->where('page', 'kodeKNdaily ')->exists()) {
            $currentDate = date('Y-m-d');
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            $selectedFilters = UserCategory::all()->where('user_id', '=', $uId)->where('page', 'kodeKNdaily ')->first();
            parse_str($selectedFilters->categories_list, $selectedFilters);

            //add default categiry
            $defaultColl = ['NAME_OBL', 'NAME_ST', 'IND_ST', 'DATE_CH'];
            if (isset($selectedFilters['collumns'])) {
                $collumns = $selectedFilters['collumns'];
            } else $collumns = [];

            $helper->addItemsinArr($defaultColl, $collumns);

            //Выборка категорий по выбраным пользователем колонкам
            foreach ($this->categories as $category) {
                foreach ($collumns as $collumn) {
                    if ($category['code_col_name'] == $collumn) {
                        $categories[] = $category;
                    }
                }
            }

            $dateFrom = date('Y-m-d', (strtotime($selectedFilters['dateFrom']) - (60 * 60 * 24)));
            $dateTo = $selectedFilters['dateTo'];
            if ($selectedFilters['dateTo'] == $currentDate) {
                if ($selectedFilters['dateFrom'] == $selectedFilters['dateTo']) {
                    $dateFrom = date('Y-m-d', (strtotime($selectedFilters['dateFrom']) - ((60 * 60 * 24) * 2)));
                }
                $dateTo = date('Y-m-d', (strtotime($selectedFilters['dateTo']) - (60 * 60 * 24)));
            }

            /**
             * Data filtering
             */
            if (isset($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.OBL_ID', $selectedFilters['regionName'])
                    ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                    ->get();

            } else if (isset($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.OBL_ID', $selectedFilters['regionName'])
                    ->whereIn('CAT_STATION.IND_ST', $selectedFilters['stationName'])
                    ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                    ->get();

            } else if (empty($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.IND_ST', $selectedFilters['stationName'])
                    ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                    ->get();

            } else if (empty($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                    ->get();
            }

            if ($selectedFilters['dateFrom'] == $currentDate) {
                $dateFrom = date('Y-m-d', (strtotime($selectedFilters['dateFrom']) - (60 * 60 * 24)));
            } else {
                $dateFrom = $selectedFilters['dateFrom'];
            }
            $kndaily = new KNDaily($dataForTable, ['dateFrom' => $dateFrom, 'dateTo' => $dateTo], $categories);
            $tmp = $kndaily->calculate();

            $dataForTable = collect($tmp);

            /**
             * array with all data for view
             */
            $this->data = [
                'categories' => $this->categories,
                'dataForTable' => $dataForTable,
                'selectedCategories' => $categories,
            ];
        }

        if (count($dataForTable) >= 50000) {
            $this->data = [
                'errors' => 'Data limit is limited'
            ];
        }

        return view('site.kndaily.KNDailyExport', $this->data);
    }
}