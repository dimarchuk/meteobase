<?php

namespace App\Http\Controllers;

use App\Helpers\{
    Helper, KNDaily
};
use App\{
  Region, Station, UserCategory
};

use DB;
use Auth;

class KNDailyController extends Controller
{
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
    private $collumns = ['NAME_OBL', 'NAME_ST', 'srok.IND_ST', 'DATE_CH', 'SROK_CH', 'TTT', 'TMIN', 'TMAX', 'TDTDTD', 'TGTG', 'T2T2', 'RRR1', 'FF', 'N', 'PPP', 'P0P0P0P0', 'PPPP', 'HSNOW', 'SSS'];

    /**
     * Kode_knController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        define("PER_PAGE", 18);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {

        $helper = new Helper();
        $regions = new Region();
        $stations = new Station();

        $uId = Auth::getUser()->getAuthIdentifier();

        if (DB::table('user_categories')->where('user_id', $uId)->where('page', 'kodeKNdaily ')->exists()) {
            $currentDate = date('Y-m-d');
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            $selectedFilters = UserCategory::all()->where('user_id', '=', $uId)->where('page', 'kodeKNdaily ')->first();
            parse_str($selectedFilters->categories_list, $selectedFilters);

            $selectedRegions = isset($selectedFilters['regionName']) ? $selectedFilters['regionName'] : null;
            $selectesStations = isset($selectedFilters['stationName']) ? $selectedFilters['stationName'] : null;

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

            $countStr = count($dataForTable);
            $countPages = ceil($countStr / PER_PAGE);

            $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/kndaily'), $countPages, $currentPage, true) : "";

            /**
             * array with all data for view
             */ 
            $data = [
                'regions' => $regions->getAllRegions(),
                'stations' => $stations->getAllStation(),
                'categories' => $this->categories,
                'selectedCategories' => $categories,
                'dataForTable' => $dataForTable->forPage($currentPage, PER_PAGE),
                'selectedRegions' => $selectedRegions,
                'selectedStations' => $selectesStations,
                'paginationLinks' => $paginationLinks
            ];

        } else {
            //if first auth
            $currentDate = Date('Y-m-d');
            $dateFrom = date('Y-m-d', (strtotime($currentDate) - (60 * 60 * 24) * 2));
            $dateTo = date('Y-m-d', (strtotime($currentDate) - (60 * 60 * 24)));

            $selectedCategories = $this->categories;
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            /**
             * Get data
             */
            $dataForTable = DB::table('CAT_STATION')
                ->select($this->collumns)
                ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                ->orderBy('CAT_STATION.OBL_ID', 'asc')
                ->orderBy('CAT_STATION.IND_ST')
                ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                ->get();

            $kndaily = new KNDaily($dataForTable, ['dateFrom' => $dateTo, 'dateTo' => $dateTo], $this->categories);
            $tmp = $kndaily->calculate();
            $dataForTable = collect($tmp);

            /**
             * Create pagination links
             */
            $countPages = ceil(count($dataForTable) / PER_PAGE);
            $paginationLinks = $helper->generateLinksForPagination(url('/kndaily'), $countPages, $currentPage, true);

            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'stations' => $stations->getAllStation(),
                'categories' => $this->categories,
                'dataForTable' => $dataForTable->forPage($currentPage, PER_PAGE),
                'selectedCategories' => $selectedCategories,
                'paginationLinks' => $paginationLinks
            ];
        }

        return view('/site.kndaily.kode_kn_daily', $data);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function getDataKodeKN()
    {
        $helper = new Helper();
        $stations = new Station();

        parse_str($_POST['data'], $data);

        $ajaxIdentification = $data['requestName'];

        switch ($ajaxIdentification) {
            case "selectStation":
                {
                    if (empty($data['regionName'])) {
                        $stations = $stations->getAllStation();

                    } else {
                        $stations->regionName = $data['regionName'];
                        $stations = $stations->filterStation();
                    }

                    $data = [
                        'station' => $stations
                    ];

                    $response_data = json_encode($data);
                    return response($response_data, 200);
                    break;
                }

            case "selectInfoForTable":
                {
                    $currentDate = date('Y-m-d');
                    $currentPage = isset($data['page']) ? $data['page'] : 1;

                    //add default categiry
                    $defaultColl = ['NAME_OBL', 'NAME_ST', 'IND_ST', 'DATE_CH'];
                    if (isset($data['collumns'])) {
                        $collumns = $data['collumns'];
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

                    $dateFrom = date('Y-m-d', (strtotime($data['dateFrom']) - (60 * 60 * 24)));
                    $dateTo = $data['dateTo'];
                    if ($data['dateTo'] == $currentDate) {
                        if ($data['dateFrom'] == $data['dateTo']) {
                            $dateFrom = date('Y-m-d', (strtotime($data['dateFrom']) - ((60 * 60 * 24) * 2)));
                        }
                        $dateTo = date('Y-m-d', (strtotime($data['dateTo']) - (60 * 60 * 24)));
                    }

                    /**
                     * Save selected filters
                     */
                    $uId = Auth::getUser()->getAuthIdentifier();
                    if (DB::table('user_categories')->where('user_id', $uId)->where('page', 'kodeKNdaily')->exists()) {
                        DB::table('user_categories')->where('user_id', $uId)->where('page', 'kodeKNdaily')->update(
                            ['categories_list' => $_POST['data']]
                        );
                    } else {
                        DB::table('user_categories')->where('user_id', $uId)->insert(
                            ['user_id' => $uId, 'page' => 'kodeKNdaily ', 'categories_list' => $_POST['data']]
                        );
                    }


                    /**
                     * Data filtering
                     */
                    if (isset($data['regionName']) && empty($data['stationName'])) {
                        $dataForTable = DB::table('CAT_STATION')
                            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                            ->orderBy('CAT_STATION.OBL_ID', 'asc')
                            ->orderBy('CAT_STATION.IND_ST')
                            ->whereIn('CAT_STATION.OBL_ID', $data['regionName'])
                            ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                            ->get();

                    } else if (isset($data['regionName']) && isset($data['stationName'])) {
                        $dataForTable = DB::table('CAT_STATION')
                            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                            ->orderBy('CAT_STATION.OBL_ID', 'asc')
                            ->orderBy('CAT_STATION.IND_ST')
                            ->whereIn('CAT_STATION.OBL_ID', $data['regionName'])
                            ->whereIn('CAT_STATION.IND_ST', $data['stationName'])
                            ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                            ->get();

                    } else if (empty($data['regionName']) && isset($data['stationName'])) {
                        $dataForTable = DB::table('CAT_STATION')
                            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                            ->orderBy('CAT_STATION.OBL_ID', 'asc')
                            ->orderBy('CAT_STATION.IND_ST')
                            ->whereIn('CAT_STATION.IND_ST', $data['stationName'])
                            ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                            ->get();

                    } else if (empty($data['regionName']) && empty($data['stationName'])) {
                        $dataForTable = DB::table('CAT_STATION')
                            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                            ->orderBy('CAT_STATION.OBL_ID', 'asc')
                            ->orderBy('CAT_STATION.IND_ST')
                            ->whereBetween('DATE_CH', [$dateFrom, $dateTo])
                            ->get();
                    }

                    if ($data['dateFrom'] == $currentDate) {
                        $dateFrom = date('Y-m-d', (strtotime($data['dateFrom']) - (60 * 60 * 24)));
                    } else {
                        $dateFrom = $data['dateFrom'];
                    }
                    $kndaily = new KNDaily($dataForTable, ['dateFrom' => $dateFrom, 'dateTo' => $dateTo], $categories);
                    $tmp = $kndaily->calculate();

                    $dataForTable = collect($tmp);

                    $countStr = count($dataForTable);
                    $countPages = ceil($countStr / PER_PAGE);

                    $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/kndaily'), $countPages, $currentPage, true) : "";

                    $dataOut = [
                        'categories' => $this->categories,
                        'selectedCategories' => $categories,
                        'dataForTable' => $dataForTable->forPage($currentPage, PER_PAGE),
                        'countPages' => $countPages,
                        'currentPage' => $currentPage,
                        'paginationLinks' => $paginationLinks,
                    ];

                    return view('site.kndaily.table', $dataOut);
                    break;
                }
        }
    }
}
