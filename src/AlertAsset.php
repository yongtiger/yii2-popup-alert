<?php ///[yii2-popup-alert]

/**
 * Yii2 popup alert widget
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-popup-alert
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\popupalert;

use yii\web\AssetBundle;

/**
 * Alert AssetBundle
 */
class AlertAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $css = [
        'css/AdminLTE.min.css',
    ];
    public $js = [
        'js/app.min.js',
        'js/demo.js',   ///[yii2-adminlte-asset]demo
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
