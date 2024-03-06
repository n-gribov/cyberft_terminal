<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace addons\finzip\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class FinZipWidget extends Widget{
	    public $message;

	    public function init(){
	        parent::init();
	        if ($this->message === null){
	            $this->message = 'Welcome User';
	        } else {
	            $this->message= 'Welcome ' . $this->message;
	        }
	    }

	    public function run(){
	        return Html::encode($this->message);
	    }
	}
