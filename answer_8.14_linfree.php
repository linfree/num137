<?php

/**
 * Created by PhpStorm.
 * User: linfree
 * Date: 2019/8/14
 * Time: 10:03
 */


ini_set('memory_limit', '2048M');
function echo_memory()
{
    echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
}

echo_memory();

/**
 * 生成数据
 *
 **/

/*
$fp = fopen('137w.csv', 'a');
$str = '';
for ($i = 0; $i < 130 * 10000; $i++) {
    $str .= $i . ',' . rand(1, 130000) . "\n";

    if (($i % 100000) == 0) {
        fwrite($fp, $str);
        $str = '';
    }

}
if ($str) {
    fwrite($fp, $str);
    $str = '';
}
fclose($fp);*/


/**
 * 循环得到圈子数组
 */
$row = 1;
$group = [];
if (($handle = fopen('137w.csv', 'r')) !== FALSE) {
    $data_arr = []; //第一列的id作为键，圈子成员为值
    $data_arr2 = []; //第二列id作为键，圈子成员为值
    while (($data = fgetcsv($handle, 200, ",") and $row <= 1310000) !== FALSE) {
        //var_dump($data[0]);
        $data_arr[$data[0]][] = $data[1];

        if (isset($data_arr2[$data[0]])) {
            $data_arr[$data[0]] = array_merge($data_arr2[$data[0]], $data_arr[$data[0]]);
        }

        if (count($data_arr[$data[0]]) > 1) {
            if (!isset($group[$data[0]])) {
                $group[$data[0]] = [$data[0]];
            }
            $group[$data[0]] = array_unique(array_merge($group[$data[0]], $data_arr[$data[0]]));
        }

        $data_arr2[$data[1]][] = $data[0];
        if (isset($data_arr[$data[1]])) {
            $data_arr2[$data[1]] = array_merge($data_arr2[$data[1]], $data_arr[$data[1]]);
        }

        if (count($data_arr2[$data[1]]) > 1) {
            if (!isset($group[$data[1]])) {
                $group[$data[1]] = [$data[1]];
            }
            $group[$data[1]] = array_unique(array_merge($group[$data[1]], $data_arr2[$data[1]]));
        }
        $row++;
    }
    fclose($handle);
}

echo_memory();
var_dump(count($data_arr));
unset($data_arr2);
unset($data_arr);
var_dump('圈数:' . count($group));
$i = 1;

/**
 * 计算重复圈子
 */
$eq = 0;
foreach ($group as $key => $value) {
    foreach ($value as $v) {
        if (isset($group[$v]) && ($v != $key) && (array_diff($group[$v], $value) == [])) {

            echo "圈子重复:" . $key . '-' . $v;
            unset($group[$v]);
            var_dump($group[$key]);
            echo "-----------------";

            $eq += 1;
        }
    }

    /*    var_dump($key);
        var_dump(array_unique($value));

        $i++;
        if ($i > 10) {
            break;
        }*/
}

var_dump('圈数:' . count($group));

echo_memory();
$start_time = microtime(true);

$end_time = microtime(true);
echo $end_time - $start_time . '\n';


echo date("Y-m-d H:i:s", time()) . "\n";