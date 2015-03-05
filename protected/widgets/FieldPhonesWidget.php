<?php
/**
 * Виджет для динамики input
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class FieldPhonesWidget extends Widget
{
	public $model;
	public $form; //CActiveForm object
	public $scenario;
	
	public function run()
	{
		$this->render('fieldPhonesWidget', ['model'=>$this->model,
											'scenario'=>$this->scenario,
											'form'=>$this->form,
		]);
	}
}