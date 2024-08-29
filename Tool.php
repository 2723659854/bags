<?php

/**
 * @purpose 代码打包操作类
 */
class Tool
{
    /**
     * 根目录
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
    public function make()
    {
        /** 先打包业务代码 */
        $this->makePhar();
        echo date('Y-m-d H:i:s') . "\r\n";
        echo "开始打包二进制文件\r\n";
        if (PHP_OS_FAMILY === 'Windows') {
            /** 編譯windows版本可執行文件 */
            $sfxFile = $this->app_path() . '/micro.sfx';
            $binFile = $this->app_path() . '/build/songshu.exe';
        } else {
            /** 編譯linux版本可執行文件 */
            $sfxFile = $this->app_path() . '/php8.1.micro.sfx';
            $binFile = $this->app_path() . '/build/songshu.bin';
        }
        /** php打包文件 */
        $pharFile = $this->app_path() . '/build/songshu.phar';
        /** 生成二进制文件 */
        file_put_contents($binFile, file_get_contents($sfxFile));
        file_put_contents($binFile, file_get_contents($pharFile), FILE_APPEND);
        /** 添加执行权限 */
        chmod($binFile, 0755);
        echo date('Y-m-d H:i:s') . "\r\n";
        echo "打包二进制文件完成 ^-^\r\n";
        echo "文件位置：{$binFile}\r\n";
    }

    /**
     * 打包phar文件
     * @return void
     */
    public function makePhar()
    {
        echo date('Y-m-d H:i:s') . "\r\n";
        echo "开始打包phar文件\r\n";
        /** 创建编译文件目录 */
        is_dir($this->app_path() . '/build') || mkdir($this->app_path() . '/build', 0777, true);
        /** 创建打包文件 */
        $phar = new Phar($this->app_path() . '/build/songshu.phar', 0, 'songshu');
        /** 开始写入缓冲区 */
        $phar->startBuffering();
        /** 设置归档的签名算法为 SHA256。签名用于验证归档的完整性和真实性。使用 SHA256 算法可以确保归档在传输或存储过程中没有被篡改。 */
        $phar->setSignatureAlgorithm(Phar::SHA256);
        /** 需要排除的文件 */
        $modern = '#^(?!.*(composer.json|/.github/|/.idea/|/.git/|/.setting/|/runtime/|/vendor-bin/|/build/|/public/upload/|micro.sfx|php8.1.micro.sfx|README.md|Tool.php))(.*)$#';
        /** 将指定目录下的文件归档，排除不需要的文件，用于减小文件大小 */
        $phar->buildFromDirectory($this->app_path(), $modern);
        /** 设置引导文件 */
        /** define('IN_PHAR', true) 表示从此行开始都以phar协议运行 */
        /** Phar::mapPhar('songshu') 将当前目录归档为songshu ,协议类型为phar */
        /** phar://songshu 表示是当前的phar格式的songshu目录，第二个songshu是当前目录下的文件songshu，这个是项目的启动文件 ，使用require_once命令会自动运行这个文件 */
        /** __HALT_COMPILER() 表示退出协议 */
        $phar->setStub(
            "#!/usr/bin/env php
<?php
define('IN_PHAR', true);
Phar::mapPhar('songshu');
require_once 'phar://songshu/songshu';
__HALT_COMPILER();
"
        );
        /** 结束 */
        $phar->stopBuffering();
        unset($phar);
        echo date('Y-m-d H:i:s') . "\r\n";
        echo "打包phar文件完成\r\n";
    }
}