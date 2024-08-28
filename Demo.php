<?php


class Demo{

    /**
     * 業務邏輯
     * @return void
     */
    public  function run()
    {
        $client = new \Xiaosongshu\Animation\Client(0, 0, 1);
        /** 雪花飘落背景 */
        $config4 = [
            /** 雪花密度 */
            'snowCount' => 100,
            /** 是否随机颜色 */
            'randomColor' => true,
        ];
        /** 添加雪花飘落背景 */
        $client->addSnow($config4);
        /** 运行脚本 */
        $client->run();
    }

    /**
     *
     * @return string
     */
    public function app_path()
    {
        return __DIR__;
    }

    /**
     * 打包成二進制
     * @return void
     */
    public function make(){

        $this->makePhar();
        echo date('Y-m-d H:i:s')."\r\n";
        echo "开始打包二进制\r\n";
        /** 編譯linux版本可執行文件 */
        //$sfxFile = $this->app_path().'/php8.1.micro.sfx';
        //$binFile = $this->app_path().'/build/songshu.bin';
        /** 編譯windows版本可執行文件 */
        $sfxFile = $this->app_path().'/micro.sfx';
        $binFile = $this->app_path().'/build/songshu.exe';
        /** php打包文件 */
        $pharFile = $this->app_path().'/build/songshu.phar';
        // 生成二进制文件
        file_put_contents($binFile, file_get_contents($sfxFile));
        file_put_contents($binFile, file_get_contents($pharFile), FILE_APPEND);
        // 添加执行权限
        chmod($binFile, 0755);
        echo date('Y-m-d H:i:s')."\r\n";
        echo "打包二进制文件完成 ^-^\r\n";
        echo "文件位置：{$binFile}\r\n";
    }

    /**
     * 打包phar文件
     * @return void
     */
    public function makePhar(){
        echo date('Y-m-d H:i:s')."\r\n";
        echo "开始打包phar文件...\r\n";
        is_dir($this->app_path().'/build')||mkdir($this->app_path().'/build',0777,true);
        $phar = new Phar($this->app_path().'/build/songshu.phar',0,'songshu');
        $phar->startBuffering();
        $phar->setSignatureAlgorithm(Phar::SHA256);
        $phar->buildFromDirectory($this->app_path(),'#^(?!.*(composer.json|/.github/|/.idea/|/.git/|/.setting/|/runtime/|/vendor-bin/|/build/|/public/upload/))(.*)$#');
        $phar->setStub("#!/usr/bin/env php
<?php
define('IN_PHAR', true);
Phar::mapPhar('songshu');
require_once 'phar://songshu/songshu';
__HALT_COMPILER();
");
        $phar->stopBuffering();
        unset($phar);
    }
}
