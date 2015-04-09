<?php
/**
 * Контроллер действий модуля услуг (кнопки справа)
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
class CashActController extends MPaidController
{
	public function accessRules()
	{
		return [
			[
				'allow', //разрешить только авториз. юзерам.
				'controllers'=>['paid/cashAct'],
				'users'=>['@'],
			],
			[
				'deny', //запрет всем остальным и перенаправление.
				'deniedCallback'=>[$this, 'redirectToDenied'],
				'controllers'=>['paid/cashAct'],
			],
		];
	}
	/**
	 * Отключаем уже подключенные скрипты
	 */
	public static function disableScripts()
	{
		Yii::app()->clientScript->scriptMap['jquery-1.11.2.min.js']=false; //уже подключен.
//		Yii::app()->clientScript->scriptMap['jquery.yiiactiveform.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.min.js']=false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.js']=false;
	}
	
	/**
	 * Выбор услуг (CGridView) в модальном окне
	 * Грузится из экшна /paid/cash/actionpatient() ajax-запросом.
	 * Результат грузится в модальное окно.
	 */
	public function actionSelectServices()
	{
		self::disableScripts();
		$modelPaid_Service=new Paid_Services('paid.cash.select');
		$modelPaid_Service->attributes=Yii::app()->request->getPost('Paid_Services');
		
		$modelDoctors=new Doctors();
		if(!Yii::app()->request->getParam('gridSelectServices'))
		{
			$modelPaid_Service->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$this->renderPartial('gridSelectServices', ['modelPaid_Service'=>$modelPaid_Service, 'modelDoctors'=>$modelDoctors], false, true);
	}
	
	/**
	 * Используется ответом на ajax-запрос при выборе услуги (двойном
	 * нажатии по записи в таблице).
	 * Смотри classSelectServices() в paid.js
	 * @param $code код услуги из хранилища, по которой был произведен двойной клик.
	 */
	public function actionChooseDoctor($code)
	{
		self::disableScripts();
		$recordPaid_Service=Paid_Services::model()->find('code=:code', [':code'=>$code]);
		//взяли id группы
		
		$criteria=new CDbCriteria;
		$criteria->select='t.last_name, t.first_name, t.middle_name';
		$criteria->with=['groups'=>['joinType'=>'INNER JOIN', 'select'=>'']];
		$criteria->together=true;
		$criteria->condition='groups.paid_service_group_id=:group_id';
		$criteria->params=[':group_id'=>$recordPaid_Service->paid_service_group_id];
		$criteria->group='t.id';
		$modelDoctors=new Doctors('paid.cashAct.search');
		$modelDoctors->attributes=Yii::app()->request->getParam('Doctors');
		
		if(!Yii::app()->request->getParam('gridSelectDoctor'))
		{ //первый заход в этот экшн
			$modelDoctors->hash=substr(md5(uniqid("", true)), 0, 4); //id CGridView
		}
		
		$dataProvider=new CActiveDataProvider($modelDoctors, ['criteria'=>$criteria]);
		$this->renderPartial('gridChooseDoctor', ['modelDoctors'=>$modelDoctors, 'dataProvider'=>$dataProvider], false, true);
	}
	
	/**
	 * Пробивка чека (выставление счета и оплата данного счёта).
	 * См. classPunchCheck() в paid.js
	 */
	public function actionPunch()
	{
		if(Yii::app()->request->getPost('punchButton'))
		{
			
		}
	}
}