<?php
/**
 * Используется для генерации инпута в форме
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class FieldDocumentsWidget extends Widget
{
	public $model;
	public $form; //CActiveForm object
	public $scenario;
	public $documentTypeListData;
	
	public function run()
	{
		$this->render('fieldDocumentsWidget', ['model'=>$this->model,
											   'scenario'=>$this->scenario,
											   'form'=>$this->form,
											   'documentTypeListData'=>$this->documentTypeListData,
		]);
	}
}