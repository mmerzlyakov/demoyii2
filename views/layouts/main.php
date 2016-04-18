<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use yii\widgets\Pjax;
use yii\widgets\Menu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>

    <meta charset="<?= Yii::$app->charset ?>">


	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>

	<script type="text/javascript">

$(document).ready(function () {

    $('#login').click(function(event){ // нажатие на кнопку - выпадает модальное окно
        event.preventDefault();
         
        var url = 'login';
        var clickedbtn = $(this);
        //var UserID = clickedbtn.data("userid");
         
        var modalContainer = $('#my-modal');
        var modalBody = modalContainer.find('.modal-body');
        modalContainer.modal({show:true});

        $.ajax({
            url: url,
            type: "GET",
            data: {/*'userid':UserID*/},
            success: function (data) {
                $('.modal-body').html(data);
                modalContainer.modal({show:true});
            }
        });
    });

    $(document).on("submit", '.login-form', function (e) {

//		$("#loginform-phone").mask("(999) 999");
//		console.log($("#loginform-phone").val());		

        e.preventDefault();
        var form = $(this);
        var result;
        $.ajax({
            url: "submitlogin",
            type: "POST",
			scriptCharset: "utf-8",
            data: form.serialize(),
            success: function (data) {

//				console.log(data);
                    var modalContainer = $('#my-modal');
    		        var modalBody = modalContainer.find('.modal-body');
					var insidemodalBody = modalContainer.find('.gb-user-form');
				try
				{
	                result = jQuery.parseJSON(data);
    				console.log(result);
				//	alert(result.username);
	                       
					if (result.flag == true) {
						insidemodalBody.html(result).hide(); 
						$('#my-modal').modal('hide');
						$('#userlabel').text(result.username + " / Выход");
	
						$('#login').css("display", "none");
						$('#signup').css("display", "none");

						return true;
//						alert(result);
//						return window.location.reload();
					}
                }
				catch(e){
				//	alert(decodeURI(result[2]));
                    modalBody.html(data).hide().fadeIn();
					return true;
	            }
            },
        });
    });



    $('#signup').click(function(event){ // нажатие на кнопку - выпадает модальное окно
        event.preventDefault();
         
        var url = 'signup';
        var clickedbtn = $(this);
        //var UserID = clickedbtn.data("userid");
         
        var modalContainer = $('#signup-modal');
        var modalBody = modalContainer.find('.modal-body');
        modalContainer.modal({show:true});
        $.ajax({
            url: url,
            type: "GET",
            data: {/*'userid':UserID*/},
            success: function (data) {
                $('.modal-body').html(data);
                modalContainer.modal({show:true});
            }
        });
    });


    $(document).on("submit", '.signup-form', function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: "submitsignup",
            type: "POST",
			scriptCharset: "utf-8",
            data: form.serialize(),
            success: function (data) {

//				console.log(data);
                    var modalContainer = $('#signup-modal');
    		        var modalBody = modalContainer.find('.modal-body');
					var insidemodalBody = modalContainer.find('.gb-user-form');
				try
				{
	                result = jQuery.parseJSON(data);
    				console.log(result);
				//	alert(result.username);
	                       
					if (result.flag == true) {
						insidemodalBody.html(result).hide(); 
						$('#signup-modal').modal('hide');
						$('#userlabel').text(result.username + " / Выход");

						$('#login').css("display", "none");
						$('#signup').css("display", "none");

						return true;
//						alert(result);
//						return window.location.reload();
					}
                }
				catch(e){
				//	alert(decodeURI(result[2]));
                    modalBody.html(data).hide().fadeIn();
					return true;
	            }
            },
        });
    });


});
	</script>


</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

<?php


/*

$list['options']['id'] = 'topnav';
$list['options']['class'] = 'navbar-default center-block text-center';
$list['options']['label'] = 'menu';
$list['items']['label'] = 'Деятельность';
$list['items']['url'] = '/site/activities';
$list['items']['options']['id'] = 'down_menu';
$list['items']['items']['label'] = '1';
$list['items']['items']['label'] = '2';
$list['items']['items']['label'] = '3';
$list['items']['items']['label'] = '4';
$list['items']['items']['label'] = '5';
$list['items']['items']['label'] = '6';
*/



?>
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
        //['label' => 'User management', 'url' => ['/user']],

    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'options'=> ['id'=>'login']];
        $menuItems[] = ['label' => 'Регистрация', 'options' => ['id'=>'signup']];
//        $menuItems[] = ['label' => 'Вход', 'url' => '/site/login'];
//        $menuItems[] = ['label' => 'Регистрация', 'url' => '/site/signup'];
//		$menuItems[] = "<b>".Html::a('', '/site/logout', ['id'=>'userlabel'])."</b>";
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton('',['class' => 'btn btn-link' , 'id' => 'userlabel'])
            . Html::endForm()
            . '</li>';  
    }
   /*
    else {
//		$menuItems[] = "<b>".Html::a(Yii::$app->user->identity->username.' / Выход', '/site/logout', ['id'=>'userlabel'])."</b>";
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
//                '' . Yii::$app->user->identity->username . ' / Выход',
                '' . Yii::$app->user->identity->name . ' / Выход',
                ['class' => 'btn btn-link' ,'id' => 'userlabel']
            )
            . Html::endForm()
            . '</li>';  
    }*/




    if(Yii::$app->user->can('GodMode'))
    {
        $menuItems[] = '<li>'
        .    ButtonDropdown::widget([
            'label' => Yii::$app->user->identity->name." / ".Yii::$app->user->identity->phone,
            'options' => [
                'class' => 'btn-lg btn-default',
                //'class' => 'btn btn-mini',
                'style' => 'margin:5px',
                'id' => 'userlabel',
            ],
            'dropdown' => [
               // 'class' => 'btn btn-mini dropdown-toggle',
                'items' => [
                    [
                        'label' => 'Мои адреса',
                        'url' => '#'
                    ],
                    [
                        'label' => 'Операции с балансом',
                        'url' => '#'
                    ],

                    [
                        'label' => 'Управление пользователями',
                        'url' => '/user'
                    ],
                    [
                        'label' => 'Управление магазином',
                        'url' => '#'
                    ],

                    [
                        'label' => 'История заказов',
                        'url' => '#'
                    ],
                    [
                        'label' => 'Промо код',
                        'url' => '#'
                    ],
                    [
                        'label' => 'Избранные товары',
                        'url' => '#'
                    ],
                    [
                        'label' => 'Обратная связь',
                        'url' => '#'
                    ],
                    [
                        'label' => '',
                        'options' => [
                            'role' => 'presentation',
                            'class' => 'divider'
                        ]
                    ],
                    [
                        'label' => 'Выход из системы',
                        'url' => '/site/logout',
                        'linkOptions' => ['data-method' => 'post'],
                    ]
                ]
            ]
        ])
        .'</li>';

    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);

    NavBar::end();

////////////////////////////////////////////////////////////////////////////////








//$list = getStructure();

?>
    <div class="container">

<?php

    NavBar::begin([
//        'brandLabel' => 'My Company',
//        'brandUrl' => Yii::$app->homeUrl,
		'options' => ['class' => 'navbar navbar-default'],
    ]);
 
$list = //Yii::$app->controller->s  getStructure();

//var_dump($list);die();

echo Nav::widget(['options' => ['id' => 'topnav','class' => 'navbar-nav navbar-topnav'], 'items' => $list]);

    NavBar::end();

////////////////////////////////////////////////////////////////////////////////
    ?>

    </div>

    <div class="container">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
	
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>


<!-- Modal "Записаться на занятия" -->
<div class="modal fade" id="my-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-sm">
     <div class="modal-content">
       <div class="modal-body">

       </div>
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal "Записаться на занятия" -->
<div class="modal fade" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-sm">
     <div class="modal-content">
       <div class="modal-body">

       </div>
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
