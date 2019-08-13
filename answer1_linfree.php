<?php
/**
 * Created by PhpStorm.
 * User: linfree
 * Date: 2019/8/13
 * Time: 13:44
 */


class Num130
{


    private $files_src = [];
    private $total = 0;
    private $out_put_filename;
    private $tmp_files = [];
    private $default_content = [];

    public function __construct($total, $filename = 'nums_137w.txt')
    {
        $this->total = $total;
        $this->out_put_filename = $filename;
        $this->files_src = $this->get_file_src(1000 * 10000);
        $this->default_content = $this->get_default_content();

    }


    public function main()
    {

        $content = $this->default_content;

        for ($i = 0; $i < $this->total; $i++) {
            $tmp = array_rand($this->files_src);
            $content[$tmp] .= "{$i}\n";
            /** 1kw 数写一次，以免内存炸了*/
            if ($i % (1000 * 10000) == 0) {
                $this->echo_memory();

                $this->write_file($content);
                unset($content); //* 清空变量
                $content = $this->default_content;

                $this->echo_memory();
            }
        }
        if ($content[1] != '') {
            $this->write_file($content);
            unset($content);
        }

        $this->split_files();
    }


    /**
     *
     * @param $step  多少数分一个文件
     * @return array 文件对象
     */
    private function get_file_src($step)
    {

        for ($i = 0; $i < $this->total; $i += $step) {
            $this->files_src[] = fopen("{$i}.txt", 'w');
            $this->tmp_files[] = "{$i}.txt";
        }
        return $this->files_src;
    }


    /**
     * 获取默认的内容数组
     */
    public function get_default_content()
    {
        foreach ($this->files_src as $k => $v) {
            $content[$k] = '';
        }
        $this->default_content = $content;
        return $content;

    }


    /**
     *
     * @param $contents  要写入的数字内容
     */
    function write_file($contents)
    {
        $files = $this->files_src;
        foreach ($files as $key => $value) {
            fwrite($value, $contents[$key]);
            unset($contents[$key]);
        }
    }


    /**
     * 关闭文件资源
     * @param $files
     */
    function close_files()
    {
        $files = $this->files_src;
        foreach ($files as $file) {
            fclose($file);
        }
    }


    /**
     * 删除临时文件
     */
    function unlink_files()
    {

        $files = $this->tmp_files;
        foreach ($files as $file) {
            unlink($file);
        }
    }


    /**
     * 合并成文件
     */
    function split_files()
    {
        $os = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'win' : 'linux';
        if ($os === 'win') {
            exec("copy *.txt " . $this->out_put_filename);
        } else {
            exec("cat *.txt > " . $this->out_put_filename);
        }
    }


    private function echo_memory()
    {
        echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL;
    }


    public function __destruct()
    {

        $this->close_files();

        $this->unlink_files();

    }


}

define('TOTAL', 13700 * 10000);
// 输出文件
$fileName = 'nums_137w.txt';


$start_time = microtime(true);


$num130 = new Num130(TOTAL, $fileName);
$num130->main();
unset($num130);

$end_time = microtime(true);
echo $end_time - $start_time . '\n';

