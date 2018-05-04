<?php

namespace App\Helpers;

use DB;

class Decode
{

    /**
     * @param $dataFromSrok
     */
    public function decodeDirectionWind($dataFromSrok)
    {
        $code = DB::table('WEATHER2')
            ->select('DD', 'RUMB')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataDD = $dataFromSrok[$dataItem]->DD;

            for ($codeItem = 2; $codeItem <= 37; $codeItem++) {
                $codeDD = explode('-', $code[$codeItem]->DD);

                if ($dataDD >= $codeDD[0] && $dataDD <= $codeDD[1]) {
                    $dataFromSrok[$dataItem]->DD = $code[$codeItem]->RUMB;
                    break;
                } else if ($dataDD == 0 || $dataDD == 360) {
                    $dataFromSrok[$dataItem]->DD = 'Пн';
                }
            }
        }
    }

    /**
     * @param $dataFromSrok
     */
    public function decodeBaricTendency($dataFromSrok)
    {
        $code = DB::table('WEATHER')
            ->select('CODE_KN01', 'A')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataA = $dataFromSrok[$dataItem]->A;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeA = $code[$codeItem]->CODE_KN01;
                if ($dataA == $codeA) {
                    $dataFromSrok[$dataItem]->A = $code[$codeItem]->A;
                    break;
                }
            }
        }
    }

    /**
     * @param $dataFromSrok
     */
    public function decodeWeatherSrok($dataFromSrok)
    {
        $code = DB::table('WEATHER2')
            ->select('CODE_KN01', 'WW')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataWW = $dataFromSrok[$dataItem]->WW;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeWW = $code[$codeItem]->CODE_KN01;
                if ($dataWW == 508 || $dataWW == 509) {
                    $dataFromSrok[$dataItem]->WW = "";
                } else if ($dataWW == $codeWW) {
                    $dataFromSrok[$dataItem]->WW = $code[$codeItem]->WW;
                }
            }
        }
    }

    /**
     * @param $dataFromSrok
     */
    public function decodeWeatherSrok12($dataFromSrok)
    {
        $code = DB::table('WEATHER')
            ->select('CODE_KN01', 'W1W2')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataW1 = $dataFromSrok[$dataItem]->W1;
            $dataW2 = $dataFromSrok[$dataItem]->W2;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeW = $code[$codeItem]->CODE_KN01;

                if ($dataW1 == 10) {
                    $dataFromSrok[$dataItem]->W1 = "";
                } else if ($dataW1 == $codeW) {
                    $dataFromSrok[$dataItem]->W1 = $code[$codeItem]->W1W2;
                }

                if ($dataW2 == 10) {
                    $dataFromSrok[$dataItem]->W2 = "";
                } else if ($dataW2 == $codeW) {
                    $dataFromSrok[$dataItem]->W2 = $code[$codeItem]->W1W2;
                }
            }
        }
    }

    /**
     * @param $dataFromSrok
     */
    public function decodeClouds($dataFromSrok)
    {
        $code = DB::table('WEATHER')
            ->select('CODE_KN01', 'C')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataC = $dataFromSrok[$dataItem]->C;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeC = $code[$codeItem]->CODE_KN01;
                if ($dataC == $codeC) {
                    $dataFromSrok[$dataItem]->C = $code[$codeItem]->C;
                    break;
                }
            }
        }
    }

    public function decodeCloudsC($dataFromSrok)
    {
        $code = DB::table('WEATHER')
            ->select('CODE_KN01', 'CL', 'CM', 'CH')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataCL = $dataFromSrok[$dataItem]->CL;
            $dataCM = $dataFromSrok[$dataItem]->CM;
            $dataCH = $dataFromSrok[$dataItem]->CH;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeKN = $code[$codeItem]->CODE_KN01;
                if ($dataCL !== null || $dataCM !== null || $dataCH !== null) {

                    if (($dataCL - 30) == $codeKN) {
                        $dataFromSrok[$dataItem]->CL = $code[$codeItem]->CL;
                    }
                    if (($dataCM - 20) == $codeKN) {
                        $dataFromSrok[$dataItem]->CM = $code[$codeItem]->CM;
                    }
                    if (($dataCH - 10) == $codeKN) {
                        $dataFromSrok[$dataItem]->CH = $code[$codeItem]->CH;
                    }
                }
            }
        }
    }

    /**
     * @param $dataFromSrok
     */
    public function decodeSoilCondition($dataFromSrok)
    {
        $code = DB::table('WEATHER')
            ->select('CODE_KN01', 'E1', 'E')
            ->get();
        for ($dataItem = 0; $dataItem < count($dataFromSrok); $dataItem++) {
            $dataE = $dataFromSrok[$dataItem]->E;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeE = $code[$codeItem]->CODE_KN01;

                if ($dataE < 10) {
                    if ($dataE == $codeE) {
                        $dataFromSrok[$dataItem]->E = $code[$codeItem]->E;
                    }
                } else {
                    if (($dataE - 10) == $codeE) {
                        $dataFromSrok[$dataItem]->E = $code[$codeItem]->E1;
                    }
                }

            }
        }
    }

    /**
     * @param $dataFromGroup
     * @return mixed
     */
    public function decodeSPSP($dataFromGroup)
    {

        $code = DB::table('WEATHER2')
            ->select('CODE_KN01', 'SPSP')
            ->get();

        for ($dataItem = 0; $dataItem < count($dataFromGroup); $dataItem++) {
            $data = $dataFromGroup[$dataItem]->KOD_SPSP;

            for ($codeItem = 0; $codeItem < count($code); $codeItem++) {
                $codeSP = $code[$codeItem]->CODE_KN01;
                if ($data == $codeSP) {
                    $dataFromGroup[$dataItem]->KOD_SPSP = $code[$codeItem]->SPSP;
                }
            }
        }
        return $dataFromGroup;
    }


    /**
     *
     */
    public function decodeWarepParams(&$dataForTable)
    {
        $groups = [
            0 => [
                'PAR1' => null,
                'PAR2' => null,
                'PAR3' => [
                    1 => 'Наростання явища',
                    2 => 'Збереження явища'
                ]
            ],

            1 => [
                'PAR1' => 'RUMB',
                'PAR2' => null,
                'PAR3' => null
            ],

            2 => [
                'PAR1' => 'RUMB',
                'PAR2' => 'WW',
                'PAR3' => null
            ],

            3 => [
                'PAR1' => null,
                'PAR2' => null,
                'PAR3' => null
            ],

            7 => [
                'PAR1' => null,
                'PAR2' => 'WW',
                'PAR3' => null
            ],

            8 => [
                'PAR1' => 'N',
                'PAR2' => 'C',
                'PAR3' => 'HSHS'
            ],

            9 => [
                'PAR1' => null,
                'PAR2' => null,
                'PAR3' => null
            ]
        ];
        for ($i = 0; $i < count($dataForTable); $i++) {
            $group = $dataForTable[$i]->CODGROUP;

            if ($group == 7) {
                $dataForTable[$i]->PAR1 = $dataForTable[$i]->PAR1 / 10;
            }
            if ($group == 0) {
                isset($dataForTable[$i]->PAR3) ? $dataForTable[$i]->PAR3 = $groups[$group][$dataForTable[$i]->PAR3] : '';
            }
            foreach ($groups[$group] as $key => $param) {

                if (isset($param) && is_string($param)) {
                    if (\Schema::hasColumn('WEATHER2', $param)) {
                        $value = DB::table('WEATHER2')
                            ->select($param)
                            ->where('CODE_WAREP', '=', $dataForTable[$i]->$key)
                            ->get();

                        $rez = $value->first();

                        if ($dataForTable[$i]->$key !== null) {
                            $dataForTable[$i]->$key = $rez->$param;
                        }

                    } else if (\Schema::hasColumn('WEATHER', $param)) {
                        $value = DB::table('WEATHER')
                            ->select($param)
                            ->where('CODE_WAREP', '=', $dataForTable[$i]->$key)
                            ->get();

                        $rez = $value->first();

                        if ($dataForTable[$i]->$key !== null) {
                            $dataForTable[$i]->$key = $rez->$param;
                        }
                    }
                }
            }
        }
    }
}