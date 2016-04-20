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


	<!-- script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script -->

	<script type="text/javascript">


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

            'style'=>'width: 100%;'
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
        'options' => [
                'class' => 'navbar-nav navbar-right',
                //'style'=>'width: 100%;'
                ],
        'items' => $menuItems,
    ]);

    echo Nav::widget(['options' => ['id' => 'topnav','class' => 'navbar-nav navbar-topnav', 'style'=>'width: 100%;' ], 'items' => Yii::$app->controller->catalogMenu]);

    NavBar::end();

////////////////////////////////////////////////////////////////////////////////

    ?>


    <div class="container" style="width: 90%;">
        <?php
       /* NavBar::begin([
            'options' => [
                'class' => 'navbar navbar-default',
                'style'=>'width: 100%;'
            ],
        ]);*/
        /*$list = Menu::getStructure();

        echo "<pre>";
        print_r($list);
        echo "</pre>";
        die();
        */
       // echo Nav::widget(['options' => ['id' => 'topnav','class' => 'navbar-nav navbar-topnav', 'style'=>'width: 100%;' ], 'items' => Yii::$app->controller->catalogMenu]);

        //old widget type
        //echo Nav::widget(['options' => ['id' => 'topnav','class' => 'navbar-nav navbar-topnav'], 'items' => Yii::$app->controller->catalogMenu]);

    //    NavBar::end();
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
