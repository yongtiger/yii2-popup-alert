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

namespace yongtiger\popupalert\widgets;

use yii\bootstrap\Widget;
use mdscomp\BootstrapDialog\assets\BootstrapDialogAssets;

/**
 * Alert widget renders a message from session flash with BootstrapDialog and fontawesome icons. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', '<b>Alert!</b> Danger alert preview. This alert is dismissable.');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @see https://bitbucket.org/mzdani/yii2-bootstrap-dialog/wiki/Usage
 * @package yongtiger\popupalert\widgets
 */
class Alert extends \yii\bootstrap\Widget
{
    /**
     * @var [type]
     */
    public $popupTypes = [
        'default'   => 'BootstrapDialog.TYPE_DEFAULT',
        'info'  => 'BootstrapDialog.TYPE_INFO',
        'primary' => 'BootstrapDialog.TYPE_PRIMARY',
        'success'    => 'BootstrapDialog.TYPE_SUCCESS',
        'warning' => 'BootstrapDialog.TYPE_WARNING',
        'danger' => 'BootstrapDialog.TYPE_DANGER',
        'error' => 'BootstrapDialog.TYPE_DANGER'
    ];

    /**
     * @var [type]
     */
    public $sizeTypes=[
        'nomal'=>'BootstrapDialog.SIZE_NORMAL',
        'small'=>'BootstrapDialog.SIZE_SMALL',
        'wide'=>'BootstrapDialog.SIZE_WIDE',
        'large'=>'BootstrapDialog.SIZE_LARGE'

    ];

    /**
     * @var [type]
     */
    public $title;

    /**
     * @var [type]
     */
    public $size;

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();

        $this->registerClientScript();

        if ($this->size === null || !isset($this->sizeTypes[$this->size])){
            $this->size = 'small';
        }

        $session = \Yii::$app->session;
        $flashes = $session->getAllFlashes();

        $view = $this->getView();

        foreach ($flashes as $type => $data) {
            if (isset($this->popupTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $message) {
                    $view->registerJs("
                        var dialogShow = BootstrapDialog.show({
                            type:" . $this->popupTypes[$type] . ",
                            title:'" . $this->title . "',
                            message:'" . $message . "',
                            size:" . $this->sizeTypes[$this->size] . ",
                            buttons:[
                                {
                                    label: 'Close',
                                    action: function(dialogItself){
                                        dialogItself.close();
                                    }
                                }
                            ]
                        });
                    ");

                    // If `$type` is `success`, automatically closed after 3s.
                   if($type == 'success'){
                        $view->registerJs("
                            setTimeout(function(){ dialogShow.close() }, 3000);
                        ");
                   }
                }

                $session->removeFlash($type);
            }
        }
    }

    /**
     * Registers necessary JavaScript.
     *
     * @return yii\web\AssetBundle the registered asset bundle instance
     */
    public function registerClientScript()
    {
        BootstrapDialogAssets::register($this->view);
    }
}