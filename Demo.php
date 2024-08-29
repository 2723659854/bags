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
