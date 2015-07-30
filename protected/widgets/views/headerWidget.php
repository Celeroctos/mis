<?php
/**
 * Шапка
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
?>
<!--<div class="container-fluid b-header">
	<div class="row">
		<div class="col-xs-12">
			МИС Notum
			<?php if(!Yii::app()->user->isGuest): ?>
				<?= CHtml::link('Выйти', Yii::app()->createUrl('service/auth/logout'), ['class'=>'btn btn-default btn-sm']); ?>
			<?php endif; ?>
		</div>
	</div>
</div>-->
<nav class="navbar navbar-default b-header" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="navbar-toggle">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand">
				<img src="/static_src/paid/img/logo-navbar.svg">
			</a>
		</div>
		<div class="collapse navbar-collapse">
<!--			<ul class="nav navbar-nav navbar-left">
			</ul>-->
			<ul class="nav navbar-nav navbar-right">
				<li>
					<span class="navbar-text">
						<span class="glyphicon glyphicon-calendar"></span>
						<?= Yii::app()->dateFormatter->format('d MMMM yyyy', time()); ?>
					</span>
					<span class="navbar-text">
						<span class="glyphicon glyphicon-time"></span>
						<?= Yii::app()->dateFormatter->format('hh:mm', time()); ?>
					</span>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-user"></span>
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a><?= Yii::app()->user->name; ?></a>
						</li>
						<li><?php if(!Yii::app()->user->isGuest): ?>
								<?= CHtml::link('Выйти', Yii::app()->createUrl('service/auth/logout'), ['class'=>'']); ?>
							<?php endif; ?>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>