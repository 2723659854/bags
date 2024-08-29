# 打包php为可执行文件

### 簡介
將動畫項目打包成可執行文件

###  用法

打开本项目的`./Demo.php`文件，里面有一个`run()`方法，这个是入口函数，你可以将你的业务代码放到这里面，下面是一个示例：
```php
<?php

/**
 * @purpose 业务代码
 */
class Demo
{
    /**
     * 入口函数
     * @return void
     * @note 添加一场流星雨
     */
    public function run()
    {
        $client = new \Xiaosongshu\Animation\Client(0, 0, 1);
        $config3 = ['maxStars' => 10, 'numStars' => 10, 'isWaterLine' => true, 'distanceX' => 0, 'distanceY' => 0, 'distanceXStep' => 2, 'distanceYStep' => 1, 'directionX' => 0, 'directionY' => 0,];
        $client->addStarRain($config3);
        $client->run();
    }
}

```

### 打包命令
```bash 
php -d phar.readonly=0 ./index.php
```
### 运行可执行文件
打包完成后，在windows环境下会生成可执行文件`./build/songshu.exe`，双击即可执行，也可以命令行执行，直接在命令行键入`./build/songshu.exe`
即可运行。<br>
如果是linux环境，打包会生成`./build/songshu.bin`可执行文件，直接命令行键入`./build/songshu.bin`即可执行。<br>

### 关闭可执行文件
在命令行窗口键入`ctrl + c`即可关闭。

### php靜態文件下載
https://github.com/dixyes/lwmbs/actions

### 参考文献
https://www.workerman.net/a/1635
https://www.workerman.net/a/1637

### 实现原理
先将业务代码打包成phar文件，写入引导文件。然后将php静态文件写入到可执行文件头，将phar压缩文件追加到可执行文件后面，最后给可执行文件追加执行权限。
在实际业务场景中，业务可能需要很多不同的扩展，那么请下载对应的php静态文件，或者自己编译。另外，可能业务代码被编译进去后会出现bug，那么需要你自己去
检验并修正。

### 其它
本项目，你只可以修改`./Demo.php`的代码，其它文件请不要修改，除非你真的明白项目的运行原理。



