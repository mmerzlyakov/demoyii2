echo Nav::widget([
                'options' => ['id' => 'topnav','class' => 'navbar-default center-block text-center'],
                'items' => [
                    ['label' => '������������', 'url' => ['/site/activities'],
			          'options' => ['id' => 'down_menu'],			
			          'items' => [
            		          ['label' => '���� ������', 'url' => ['/site/history'],'options' => ['id' => 'down_history']],
		                      ['label' => '���� ��������', 'url' => ['/site/event'],'options' => ['id' => 'wn_history']],
					          ['label' => '������������', 'url' => ['/activities/reabilitation'],'options' => ['id' => 'n_history']],
		                      ['label' => '��������� � ��������', 'url' => ['/activities/sponsor']],]],
			                  ['label' => '�����������', 'url' => ['/site/event']],
			                  ['label' => '��� � ���', 'url' => ['/site/smi']],
			       ['label' => '������������', 'url' => ['/site/activities'],
			          'options' => ['id' => 'down_menu'],			
			          'items' => [
            		          ['label' => '���� ������', 'url' => ['/site/history'],'options' => ['id' => 'down_history']],
		                      ['label' => '���� ��������', 'url' => ['/site/event'],'options' => ['id' => 'wn_history']],
					          ['label' => '������������', 'url' => ['/activities/reabilitation'],'options' => ['id' => 'n_history']],
		                      ['label' => '��������� � ��������', 'url' => ['/activities/sponsor']],]],
			                  ['label' => '�����������', 'url' => ['/site/event']],
			                  ['label' => '��� � ���', 'url' => ['/site/smi']],
                ],
            ]);
