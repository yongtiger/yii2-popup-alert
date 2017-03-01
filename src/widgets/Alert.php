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
use rmrevin\yii\fontawesome\AssetBundle;    ///[yii2-popup-alert 1.1.0 (fontawesome)]

/**
 * Alert widget renders a message from session flash with `BootstrapDialog` and fontawesome icons. All flash messages are displayed
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
class Alert extends Widget
{
    ///[yii2-popup-alert 1.1.0 (fontawesome)]
    /**
     * @var array the fontawesome icon types configuration for the flash titles or messages.
     */
    public $iconTypes = [
        'default' => '<i class="icon fa fa-info"></i>',
        'info' => '<i class="icon fa fa-info"></i>',
        'primary' => '<i class="icon fa fa-info-circle"></i>',
        'success' => '<i class="icon fa fa-check"></i>',
        'warning' => '<i class="icon fa fa-warning"></i>',
        'danger' => '<i class="icon fa fa-exclamation"></i>',
        'error' => '<i class="icon fa fa-times-circle"></i>',
    ];

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
                            size:" . $this->sizeTypes[$this->size] . ",
                            cssClass: '',
                            title:'" . $this->iconTypes[$type] . " " . $this->title . "',
                            message:'" . $message . "',
                            nl2br: true,
                            closable: true,
                            closeByBackdrop: true,
                            closeByKeyboard: true,
                            closeIcon: '&#215;',
                            spinicon: BootstrapDialog.ICON_SPINNER,
                            autodestroy: true,
                            draggable: false,
                            animate: true,
                            description: '',
                            tabindex: -1,
                            btnsOrder: BootstrapDialog.BUTTONS_ORDER_CANCEL_OK,

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
        AssetBundle::register($this->view); ///[yii2-popup-alert 1.1.0 (fontawesome)]
    }
}