<?php
interface SA_IPage {
	public function init();
	public function run();
	public function beforeDisplay();
	public function display();
	public function afterDisplay();
}