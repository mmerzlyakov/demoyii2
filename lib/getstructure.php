/*


function getStructure()
{

$result = (new \yii\db\ActiveRecord())
->find()
->from('category')
->where('')
->orderby('sort ASC')
->asarray()
->all();

//var_dump($result);
//  $result = Category::find()->asArray()->all();

if (!$result) {     return NULL;  }

$arr_cat = array();


for ($i = 0; $i < count($result);$i++) {
$row = $result[$i];
if ($row['parent_id'] == NULL)
$row['parent_id'] = 0;

if (empty($arr_cat[$row['parent_id']]))
$arr_cat[$row['parent_id']] = array();

$arr_cat[$row['parent_id']][] = $row;
}

$view_cat =

function ($data, $parent_id = 0) use ( & $view_cat){

$result = NULL;
if (empty($data[$parent_id])) {
return;
}

$result = array();


for ($i = 0; $i < count($data[$parent_id]);$i++) {
$result[] = ['label' => $data[$parent_id][$i]['title'],
'url' => 'assortiment/'.$data[$parent_id][$i]['id'],
'active' => $data[$parent_id][$i]['id'] == 8,
'options' => ['class' => 'dropdown' ],
'items' => $view_cat($data,$data[$parent_id][$i]['id'])];

}
return $result;
};

$result = $view_cat($arr_cat);
return $result;
}

*/

//$list = Site::getStructure();



//    var_dump($list);die();