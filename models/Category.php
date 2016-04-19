<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

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
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'level', 'sort', 'active'], 'integer'],
            [['level'], 'required'],
            [['title', 'alias'], 'string', 'max' => 128],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'title' => 'Title',
            'alias' => 'Alias',
            'sort' => 'Sort',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    public function findCategoryProducts($idList,$params){
        $query = GoodsVariations::find()
            ->select([
                'goods.name AS name',

                'variants.price AS productPrice',
                'variants.comission as productCommission',
                'variants.id as variantId',

                'goods.id as productId',
                'goods.discount as productDiscount',
                'goods.count_pack as countPack',
                'shops.comission_id as commissionId',
            ])
            ->from([
                'variants' => GoodsVariations::find()
                    ->where(['status' => 1])
                    ->orderBy([
                        'price' => SORT_ASC,
                    ])
            ])
            ->leftJoin(Goods::tableName(),'variants.good_id = goods.id')
            ->leftJoin(CategoryLinks::tableName(),'category_links.product_id = goods.id')
            ->leftJoin(Shops::tableName(),'goods.shop_id = shops.id')
            ->where(['IN','category_links.category_id',$idList])
            ->andWhere([
                'shops.status' => 1,
                'goods.status' => 1,
                'goods.show' => 1,
                'goods.confirm' => 1,
                'variants.status' => 1,
            ])
            ->andWhere(['OR',
                ['>','good_count(`goods`.`id`, NULL)',0],
                ['IS','good_count(`goods`.`id`, NULL)',NULL]
            ])
            ->groupBy('goods.id');

        //print $query->createCommand()->getRawSql();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'attributes'=>[
                    'name',
                    'productPrice',
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            //'parent_id' => $this->parent_id,
            //'level' => $this->level,
            //'sort' => $this->sort,
            //'active' => $this->active,
        ]);
/*
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias]);
*/
        return $dataProvider;

    }

    public function findVariations($productsIds){
        $showTagsList = (new Query())->from('tags_groups')->where(['status' => 1,'show' => 1])->indexBy('id')->all();
        $showTagsListIds = [];
        foreach($showTagsList as $item){
            $showTagsListIds[] = $item['id'];
        }
        $variation = [];
        $productsIds = [];
        if(is_array($productsIds)){
            $query = (new Query())
                ->from('goods_variations')
                ->select([
                    'goods_variations.*',
                    'tags.id AS tagValueId',
                    'tags.value AS tagValue',
                    'tags_groups.id AS tagGroupId',
                    'tags_groups.name AS tagName',

                    'good_count(`good_id`, `goods_variations`.`id`) AS productCount',
                ])
                ->leftJoin('tags_links','tags_links.variation_id = goods_variations.id')
                ->leftJoin('tags','tags_links.tag_id = tags.id')
                ->leftJoin('tags_groups','tags.group_id = tags_groups.id')
                ->where(['IN','goods_variations.good_id',$productsIds])
                ->andWhere([
                    'goods_variations.status' => 1,
                    'tags_groups.status' => 1,
                    'tags.status' => 1,
                ])
                ->andWhere(['OR',
                    ['>','good_count(`good_id`, `goods_variations`.`id`)',0],
                    ['IS','good_count(`good_id`, `goods_variations`.`id`)',NULL]
                ])
            ->all();

            if(!$query){
                return false;
            }else{
                foreach($query as $variant){
                    $variation[$variant['good_id']][$variant['id']]['productCount'] = $variant['productCount'];
                    if(in_array($variant['tagGroupId'],$showTagsListIds)){
                        $variation[$variant['good_id']][$variant['id']]['props'][$variant['tagGroupId']][$variant['tagValueId']] = $variant['tagValue'];
                    }
                }
            }
        }
        return $variation;
    }
}
