<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helpers\{
    Helper, Decode
};
use App\{
    Region, Station, Srok, UserCategory, Warep
};


class WarepController extends Controller
{
    /**
     * WarepController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        define("PER_PAGE", 18);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $helper = new Helper();
        $regions = new Region();
        $stations = new Station();

        $appearances = DB::table('WEATHER2')->select('CODE_WAREP', 'CWCW')->where('CWCW', '!=', '')->get();

        $appear = [];
        foreach ($appearances as $appearance) {
            $appear[] = $appearance->CODE_WAREP;
        }

        $uId = Auth::getUser()->getAuthIdentifier();

        if (DB::table('user_categories')->where('user_id', $uId)->where('page', 'warep')->exists()) {

            $selectedFilters = UserCategory::all()->where('user_id', '=', $uId)->where('page', 'warep')->first();

            parse_str($selectedFilters->categories_list, $selectedFilters);

            $selectedRegions = isset($selectedFilters['regionName']) ? $selectedFilters['regionName'] : null;
            $selectesStations = isset($selectedFilters['stationName']) ? $selectedFilters['stationName'] : null;

            $currentDate = Date('Y-m-d');
            $warep = new Warep([$currentDate, $currentDate]);

            $storm = ($selectedFilters['storm'] == 'All') ? [1, 2] : [(int)$selectedFilters['storm']];
            $appearance = ($selectedFilters['appearance'] == 'All') ? $appear : [(int)$selectedFilters['appearance']];

            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            /**
             * Data filtering
             */
            if (isset($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {
                $countStr = $warep->getCountStrRegion($selectedFilters['regionName'], $storm, $appearance);
                $dataForTable = $warep->getRegionData($selectedFilters['regionName'], $storm, $appearance, $currentPage);

            } else if (isset($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {
                $countStr = $warep->getCountStrRegionStation($selectedFilters['regionName'], $selectedFilters['stationName'], $storm, $appearance);
                $dataForTable = $warep->getRegionStationData($selectedFilters['regionName'], $selectedFilters['stationName'], $storm, $appearance, $currentPage);

            } else if (empty($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {
                $countStr = $warep->getCountStrStation($selectedFilters['stationName'], $storm, $appearance);
                $dataForTable = $warep->getStationData($selectedFilters['stationName'], $storm, $appearance, $currentPage);

            } else if (empty($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {
                $countStr = $warep->getCountStrBasic($storm, $appearance);
                $dataForTable = $warep->getBasicData($currentPage, $storm, $appearance);
            }

            $countPages = ceil($countStr / PER_PAGE);

            $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/warep'),
                $countPages, $currentPage, true) : "";

            foreach ($dataForTable as $item) {
                if ($item->STORM_AVIA === 1) {
                    $item->STORM_AVIA = 'STORM';
                } else $item->STORM_AVIA = 'AVIA';
                foreach ($appearances as $appearance) {
                    if ($item->CODPHENOTYP === $appearance->CODE_WAREP)
                        $item->HENOTYP_DECODE = $appearance->CWCW;
                }
            }
            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'stations' => $stations->getAllStation(),
                'appearances' => $appearances,
                'dataForTable' => $dataForTable,
                'paginationLinks' => $paginationLinks
            ];

        } else {
            //if first auth
            $currentDate = Date('Y-m-d');
            $warep = new Warep([$currentDate, $currentDate]);

            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $dataForTable = $warep->getBasicData($currentPage, [1, 2], $appear);

            foreach ($dataForTable as $item) {
                if ($item->STORM_AVIA === 1) {
                    $item->STORM_AVIA = 'STORM';
                } else $item->STORM_AVIA = 'AVIA';
                foreach ($appearances as $appearance) {
                    if ($item->CODPHENOTYP === $appearance->CODE_WAREP)
                        $item->HENOTYP_DECODE = $appearance->CWCW;
                }
            }

            /**
             * Create pagination links
             */
            $countPages = ceil($warep->getCountStrBasic([1, 2], $appear) / PER_PAGE);
            $paginationLinks = $helper->generateLinksForPagination(url('/warep'), $countPages, $currentPage, true);

            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'stations' => $stations->getAllStation(),
                'appearances' => $appearances,
                'dataForTable' => $dataForTable,
                'paginationLinks' => $paginationLinks
            ];
        }

        return view('site.warep.warep', $data);
    }

    public function getDataWarep()
    {
        $helper = new Helper();
        $appearances = DB::table('WEATHER2')->select('CODE_WAREP', 'CWCW')->where('CWCW', '!=', '')->get();

        $appear = [];
        foreach ($appearances as $appearance) {
            $appear[] = $appearance->CODE_WAREP;
        }

        parse_str($_POST['data'], $data);
        $ajaxIdentification = $data['requestName'];

        switch ($ajaxIdentification) {

            case "selectInfoForTable":
                {
                    $currentPage = isset($data['page']) ? $data['page'] : 1;
                    $warep = new Warep([$data['dateFrom'], $data['dateTo']]);

                    $storm = ($data['storm'] == 'All') ? [1, 2] : [(int)$data['storm']];
                    $appearance = ($data['appearance'] == 'All') ? $appear : [(int)$data['appearance']];

                    /**
                     * Save selected filters
                     */
                    $uId = Auth::getUser()->getAuthIdentifier();
                    if (DB::table('user_categories')->where('user_id', $uId)->where('page', 'warep')->exists()) {
                        DB::table('user_categories')->where('user_id', $uId)->where('page', 'warep')->update(
                            ['categories_list' => $_POST['data']]
                        );
                    } else {
                        DB::table('user_categories')->where('user_id', $uId)->insert(
                            ['user_id' => $uId, 'page' => 'warep', 'categories_list' => $_POST['data']]
                        );
                    }

                    /**
                     * Data filtering
                     */
                    if (isset($data['regionName']) && empty($data['stationName'])) {
                        $countStr = $warep->getCountStrRegion($data['regionName'], $storm, $appearance);
                        $dataForTable = $warep->getRegionData($data['regionName'], $storm, $appearance, $currentPage);

                    } else if (isset($data['regionName']) && isset($data['stationName'])) {
                        $countStr = $warep->getCountStrRegionStation($data['regionName'], $data['stationName'], $storm, $appearance);
                        $dataForTable = $warep->getRegionStationData($data['regionName'], $data['stationName'], $storm, $appearance, $currentPage);

                    } else if (empty($data['regionName']) && isset($data['stationName'])) {
                        $countStr = $warep->getCountStrStation($data['stationName'], $storm, $appearance);
                        $dataForTable = $warep->getStationData($data['stationName'], $storm, $appearance, $currentPage);

                    } else if (empty($data['regionName']) && empty($data['stationName'])) {
                        $countStr = $warep->getCountStrBasic($storm, $appearance);
                        $dataForTable = $warep->getBasicData($currentPage, $storm, $appearance);
                    }

                    foreach ($dataForTable as $item) {
                        if ($item->STORM_AVIA === 1) {
                            $item->STORM_AVIA = 'STORM';
                        } else $item->STORM_AVIA = 'AVIA';
                        foreach ($appearances as $appearance) {
                            if ($item->CODPHENOTYP === $appearance->CODE_WAREP)
                                $item->HENOTYP_DECODE = $appearance->CWCW;
                        }
                    }

                    $countPages = ceil($countStr / PER_PAGE);
                    $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/warep'), $countPages, $currentPage, true) : "";

                    $dataOut = [
                        'dataForTable' => $dataForTable,
                        'paginationLinks' => $paginationLinks,
                    ];

                    return view('site.warep.table', $dataOut);
                    break;
                }
        }

    }
}
