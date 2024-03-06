<?php
namespace addons\swiftfin\models\documents\mt;


interface MtOwnerInterface {
	public function getRecipient();
	public function getSender();
	public function getTerminalCode();
}