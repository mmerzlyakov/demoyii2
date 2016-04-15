echo Nav::widget([
                'options' => ['id' => 'topnav','class' => 'navbar-default center-block text-center'],
                'items' => [
                    ['label' => 'Деятельность', 'url' => ['/site/activities'],
			          'options' => ['id' => 'down_menu'],			
			          'items' => [
            		          ['label' => 'Наши собаки', 'url' => ['/site/history'],'options' => ['id' => 'down_history']],
		                      ['label' => 'Наши волонтёры', 'url' => ['/site/event'],'options' => ['id' => 'wn_history']],
					          ['label' => 'Реабилитация', 'url' => ['/activities/reabilitation'],'options' => ['id' => 'n_history']],
		                      ['label' => 'Спонсорам и партнёрам', 'url' => ['/activities/sponsor']],]],
			                  ['label' => 'Мероприятия', 'url' => ['/site/event']],
			                  ['label' => 'СМИ о нас', 'url' => ['/site/smi']],
			       ['label' => 'Деятельность', 'url' => ['/site/activities'],
			          'options' => ['id' => 'down_menu'],			
			          'items' => [
            		          ['label' => 'Наши собаки', 'url' => ['/site/history'],'options' => ['id' => 'down_history']],
		                      ['label' => 'Наши волонтёры', 'url' => ['/site/event'],'options' => ['id' => 'wn_history']],
					          ['label' => 'Реабилитация', 'url' => ['/activities/reabilitation'],'options' => ['id' => 'n_history']],
		                      ['label' => 'Спонсорам и партнёрам', 'url' => ['/activities/sponsor']],]],
			                  ['label' => 'Мероприятия', 'url' => ['/site/event']],
			                  ['label' => 'СМИ о нас', 'url' => ['/site/smi']],
                ],
            ]);
