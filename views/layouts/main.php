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
use yii\bootstrap\Button;
//use yii\widgets\Menu;
use app\models\Menu;

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
        e.preventDefault();
        var form = $(this);
        var result;
        $.ajax({
            url: "submitlogin",
            type: "POST",
			scriptCharset: "utf-8",
            data: form.serialize(),
            success: function (data) {
                    var modalContainer = $('#my-modal');
    		        var modalBody = modalContainer.find('.modal-body');
					var insidemodalBody = modalContainer.find('.gb-user-form');
				try
				{
	                result = jQuery.parseJSON(data);
    				console.log(result);

					if (result.flag == true) {
						insidemodalBody.html(result).hide(); 
						$('#my-modal').modal('hide');
                        /*$('#userlabel').label(result.username + " / " + result.phone);
                        $('#userlabel').css("display","block");
	
						$('#login').css("display", "none");
						$('#signup').css("display", "none");*/
                        location.reload();
						return true;
					}
                }
				catch(e){
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
						/*$('#userlabel').text(result.username + " / "+result.phone);
                        $('#userlabel').css("display","block");

						$('#login').css("display", "none");
						$('#signup').css("display", "none");
                           */ location.reload();
						return true;
					}
                }
				catch(e){
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
    NavBar::begin([
        'brandLabel' => 'ExtremeShop',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        //['label' => 'Home', 'url' => ['/site/index']],
        //['label' => 'About', 'url' => ['/site/about']],
        //['label' => 'Contact', 'url' => ['/site/contact']],
        //['label' => 'User management', 'url' => ['/user']],

    ];
    /*if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'options'=> ['id'=>'login']];
        $menuItems[] = ['label' => 'Регистрация', 'options' => ['id'=>'signup']];
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton('',['class' => 'btn btn-link' , 'id' => 'userlabel'])
            . Html::endForm()
            . '</li>';  
    }*/

    $subItems = [];

    if(!Yii::$app->user->isGuest) {


        $subItems[] = [
            'label' => 'Мои адреса',
            'url'   => '#'
        ];
        $subItems[] = [
            'label' => 'Операции с балансом',
            'url'   => '#'
        ];

    }

    if(Yii::$app->user->can('GodMode')){

            $subItems[] =
                            [
                                'label' => 'Управление пользователями',
                                'url' => '/user'
                            ];


            $subItems[] =   [
                                'label' => 'Управление магазином',
                                'url' => '#'
                            ];
    }

    if(!Yii::$app->user->isGuest) {
    $subItems[] =   [
                        'label' => 'История заказов',
                         'url' => '#'
                            ];
    $subItems[] =   [
                         'label' => 'Промо код',
                                'url' => '#'
                            ];
    $subItems[] =   [
                                'label' => 'Избранные товары',
                                'url' => '#'
                            ];
    $subItems[] =   [
                                'label' => 'Обратная связь',
                                'url' => '#'
                            ];
    $subItems[] =   [
                                'label' => '',
                                'options' => [
                                    'role' => 'presentation',
                                    'class' => 'divider'
                                ]
                            ];

    $subItems[] = [
            'label'       => 'Выход из системы',
            'url'         => '/site/logout',
            'linkOptions' => ['data-method' => 'post'],
        ];
    }

   // var_dump($subItems);die();

    if(!Yii::$app->user->isGuest) {


        $label = Yii::$app->user->identity->name . " / ". Yii::$app->user->identity->phone;
        $style = 'margin:5px;';

       // $idDB = 'userlabel';
    }
    else {

        $label = '';
        $style = 'margin: 5px;';
     //   $idDB = 'signup';

        $menuItems[] = '<li>'
            . Button::widget(
                [
                    'label'   => 'Вход',
                    'options' => [
                        'class' => 'btn-lg btn-default',
                        'style' => 'margin:5px',
                        'id'    => 'login',
                    ],
                ]
            )
            . '</li>';

        $menuItems[] = '<li>'
            . Button::widget(
                [
                    'label'   => 'Регистрация',
                    'options' => [
                        'class' => 'btn-lg btn-default',
                        'style' => 'margin:5px',
                        'id'    => 'signup',
                    ],
                ]
            )
            . '</li>';
    }


    $menuItems[] = '<li>'
        .    ButtonDropdown::widget([
            'label' => $label,
            'options' => [
                'class' => 'btn-lg btn-default',
                'style' => 'margin: 5px;',
                'id' => 'userlabel',
            ],
            'dropdown' => [
                //'class' => $style,
                'items' => $subItems,
            ]
        ])
        .'</li>';


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);

    NavBar::end();

////////////////////////////////////////////////////////////////////////////////

    ?>
    <div class="container">
        <?php
        NavBar::begin([
            'options' => ['class' => 'navbar navbar-default'],
        ]);
        $list = Menu::getStructure();

        echo Nav::widget(['options' => ['id' => 'topnav','class' => 'navbar-nav navbar-topnav'], 'items' => Yii::$app->controller->catalogMenu]);

        NavBar::end();
        ?>
    </div>

    <div class="container">

        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Главная', 'url' => '/'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],   ]) ?>

        <?= $content ?>
	
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ExtremeShop <?= date('Y') ?></p>


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
