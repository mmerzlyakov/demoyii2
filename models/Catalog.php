<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $level
 * @property string $title
 * @property string $alias
 * @property integer $sort
 * @property integer $active
 *
 * @property Category $parent
 * @property Category[] $categories
 */
class Catalog extends \yii\db\ActiveRecord
{
    public function getCategory(){

    }

    public function getCategoryProducts(){

    }

    public function findByAlias($alias)
    {
        return Category::findOne(['alias'=>$alias]);
    }

    public static function findBreadcrumbs($aliases,$data = false)
    {
        if(empty(end($aliases))){
            array_pop($aliases);
        }

        if(!$data){
            $categories = Category::find()->where(['alias' => $aliases])->indexBy('id')->orderBy('level')->asArray()->all();
        }else{
            foreach($data as $i => $item){
                if($item['alias'] != $aliases[0]){
                    unset($data[$i]);
                }
            }
            $categories = $data;
        }

        if(!$categories){
            return false;
        }

        $flag = 0;
        foreach($categories as $i => $category){
            if($flag > 0){
                if(!isset($categories[$category['parent_id']])){
                    unset($categories[$i]);
                }
            }
            $flag = 1;
        }

        return $categories;
    }

    public static function getChilds($id){
        return Category::find()->where(['parent_id' => $id])->all();
    }

    public static function getChildsIds($id){
        $childs = false;
        $childCategoryLevelOne = Category::find()->where(['parent_id' => $id])->select(['id','parent_id'])->all();
        if(!$childCategoryLevelOne){
            return false;
        }
        foreach($childCategoryLevelOne as $levelOne){
            $childs[] = $levelOne->id;
        }
        $childCategoryLevelOne = Category::find()->where(['IN','parent_id', $childs])->select(['id','parent_id'])->all();
        if(!$childCategoryLevelOne){
            return $childs;
        }
        foreach($childCategoryLevelOne as $levelOne){
            $childs[] = $levelOne->id;
        }
        return $childs;
    }

    public function getParent() {
        return (new Category)->hasOne(self::classname(),['id' => 'parent_id'])->from(Category::tableName() . ' AS parent');
    }
    public function getParentTitle() {
        return ($this->parent)?$this->parent->title:'';
    }

    public function findByAttributes($params){
        return Category::findOne($params);

    }

    public static function buildTree(array &$elements, array &$urls, $parentId = 0) {
        $branch = array();
        $urls[0] = '/' . Yii::$app->params['catalogPath'];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $urls[$element['id']] = (isset($urls[$parentId]))?$urls[$parentId] .'/'. $element['alias']:'/catalog/' . $element['alias'] . '/';
                $children = self::buildTree($elements, $urls, $element['id']);
                if ($children) {
                    $element['items'] = $children;
                }
                $branch[$element['id']] = $element;
                $branch[$element['id']]['label'] = $element['title'];
                $branch[$element['id']]['url'] = (isset($urls[$parentId]))?$urls[$parentId] .'/'. $element['alias'] . '/':'/catalog/' . $element['alias'];
                unset($elements[$element['id']]);
            }
        }
        return $branch;
    }
}
