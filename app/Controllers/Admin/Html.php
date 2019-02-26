<?php
/**
 * User: yongli
 * Date: 18/5/6
 * Time: 10:49
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

use YP\Core\YP_Controller;

/**
 * 前端框架
 *
 * Class Html
 *
 * @package App\Controllers
 */
class Html extends YP_Controller
{
    public function index()
    {
        $this->display();
    }


    public function update()
    {
        $driveid = '3427670,3427683,3427664,3427708,3427712,3427725,3427679,3427723,3427736,3427746,3427732,3427756,3427763,3427780,3427816,3427813,3427810,3427830,3427824,3427818,3427835,3427823,3427847,3427865,3427867,3427864,3427872,3427866,3427900,3427898,3427901,3427923,3427919,3427909,3427942,3427911,3427938,3427967,3427977,3427979,3427986,3427990,3427996,3428002,3427999,3427989,3428010,3428000,3427974,3428024,3428050,3428064,3428056,3428013,3428054,3428078,3428082,3428074,3428092,3428088,3428090,3428109,3428117,3428124,3428127,3428140,3428149,3428154,3428151,3428111,3428161,3428169,3428133,3428181,3428183,3428129,3428195,3428170,3428217,3428237,3427997,3428244,3428258,3428260,3428261,3428268,3428277,3428297,3428273,3428326,3428334,3428361,3428377,3428357,3428373,3428378,3428345,3428465,3428490,3428495,3428513,3428535,3428537,3428543,3428565,3428566,3428571,3428600,3428612,3428552,3428620,3428636,3428657,3428658,3428665,3428675,3428656,3428659,3428683,3428690,3428700,3428731,3428704,3428667,3428732,3428735,3428750,3428756,3428759,3428773,3428824,3428844,3428854,3428859,3428865,3428878,3428873,3428882,3428901,3428910,3428929,3428908,3428905,3428917,3428939,3428909,3428943,3428960,3428925,3428962,3428949,3428996,3429004,3429020,3429024,3429033,3429039,3429050,3429061,3429071,3429087,3429126,3429134,3429158,3429141,3429181,3429188,3429172,3429197,3428842,3429229,3429230,3429117,3429244,3429298,3429315,3429330,3429344,3429333,3429347,3429354,3429339,3429361,3429370,3429379,3429387,3429392,3429400,3429393,3429402,3429412,3429419,3429425,3429435,3429445,3429451,3429461,3429476,3429488,3429504,3429508,3429515,3429522,3429526,3429541,3429539,3429545,3429554,3429582,3429576,3429604,3429616,3429624,3429633,3429639,3429655,3429653,3429672,3429693,3429720,3429724,3429736,3429739,3429748,3429774,3429780,3429791,3429814,3429812,3429819,3429823,3429834,3429832,3429847,3429859,3429862,3429870,3429872,3429873,3429871,3429880,3429879,3429883,3429884,3429886,3429888,3429891,3429890,3429896,3429901,3429897,3429899,3429903,3429904,3429905,3429910,3429911,3429908,3429913,3429916,3429917,3429918,3429921,3429922,3429923,3429926,3429925,3429924,3429912,3429928,3429937,3429938,3429942,3429941,3429944,3429946,3429947,3429949,3429953,3429894,3429954,3429952,3429948,3429955,3429957,3429960,3429963,3429964,3429961,3429967,3429959,3429966,3429965,3429971,3429974,3429969,3429977,3429976,3429980,3429985,3429984,3429987,3429982,3429981,3429988,3429990,3429993,3429997,3429992,3429999,3430001,3430002,3430003,3430007,3430005,3430009,3430004,3430008,3430014,3430011,3430012,3430018,3430021,3430019,3430027,3430023,3430030,3430031,3430029,3430034,3430035,3430041,3430039,3430042,3430038,3430043,3430037,3430048,3430071,3427812,3430058,3430096,3430099,3430102,3430116,3430126,3430129,3430125,3430136,3430138,3430139,3430157,3430149,3430147,3430173,3430180,3430195,3430193,3430189,3430191,3430198,3430207,3430172,3430205,3430186,3430199,3430221,3430215,3430222,3430236,3430258,3430252,3430266,3430277,3430280,3430272,3430286,3430281,3430292,3428234,3430298,34334305080301,3430305,3430318,3430312,3430313,3430335,3430330,3430354,3430375,3430385,3430388,3430406,3430431,3430433,3429975,3430443,3430445,3430449,3430450';
        $driveid = explode(',', $driveid);

        $sql = '';
        foreach ($driveid as $key => $value) {
            $sql .= 'update  t_join_driver set receive_status=1 where id='.$value.'; ';
        }
        echo $sql;


    }
}

