<?php
/**
 * Created by PhpStorm.
 * User: linfree
 * Date: 2019/8/13
 * Time: 13:44
 */

define('TOTAL', 13700 * 10000);
// 输出文件
$fileName = 'nums_137w.txt';


$start_time = microtime(true);

/**
 * @param $total 数字总数
 * @param $step  多少数分一个文件
 * @return array 文件对象
 */
function get_file_src($total, $step)
{
    $files = [];
    for ($i = 0; $i < $total; $i += $step) {
        $files[] = fopen("{$i}.txt", 'w');
    }
    return $files;
}

/**
 * @param $files 文件对象
 * @param $contents  要写入的数字内容
 */
function write_file($files, $contents)
{
    foreach ($files as $key => $value) {
        fwrite($value, $contents[$key]);
        unset($contents[$key]);
    }

}


/**
 * 关闭文件资源
 * @param $files
 */
function close_files($files)
{
    foreach ($files as $file) {
        fclose($file);
    }
}


/**
 * 删除临时文件
 */
function unlink_files($files)
{
    foreach ($files as $file) {
        unlink($file);
    }
}

/**
 * 合并成文件
 * @param $file
 */
function split_files($file)
{
    $os = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'win' : 'linux';
    if ($os === 'win') {
        exec("copy *.txt " . $file);
    } else {
        exec("cat *.txt > " . $file);
    }
}


$files = get_file_src(TOTAL, 1000 * 10000);

foreach ($files as $k => $v) {
    $content[$k] = '';
}
$c_tmp = $content;

echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;

for ($i = 0; $i < TOTAL; $i++) {

    $tmp = array_rand($files);
    $content[$tmp] .= "{$i}\n";
    /**
     * 1kw 数写一次，以免内存炸了
     */
    if ($i % (1000 * 10000) == 0) {
        write_file($files, $content);
        echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
        /**
         * 清空变量
         */
        unset($content);
        $content = $c_tmp;

        echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
    }

}
if ($content[1] != '') {
    echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
    write_file($files, $content);
    unset($content);
}


close_files($files);
split_files($fileName);
unlink_files($files);

$end_time = microtime(true);
echo $end_time - $start_time . '\n';

