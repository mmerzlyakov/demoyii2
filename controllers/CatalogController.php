<?php

namespace app\controllers;

use app\models\GoodsImagesLinks;
use app\models\GoodsSearch;
use app\models\GoodsImages;
use Yii;
use app\models\Catalog;
use app\models\Category;
use app\models\CategorySearch;
use app\models\Goods;
use app\models\Menu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CatalogController extends Controller
{
    public $catalogMenu;
    public $catalogHash;

    public function init() {
        $this->catalogHash = Category::find()->where(['active' => 1])->orderBy('level, sort')->indexBy('id')->asArray()->all();
        $urls = [];
        $this->catalogMenu = Catalog::buildTree($this->catalogHash,$urls);

        //$this->catalogMenu = Menu::getStructure();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = false, $alias = false)
    {
        $parent = $urlList = $breadcrumbsCatalog = false;
        if(!$alias){
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            $alias = explode('/',$alias);
            $view = Category::changeView($alias);
            $breadcrumbsCatalog = Catalog::findBreadcrumbs($alias,$this->catalogHash);

            $currentCategory = end($breadcrumbsCatalog);

            if($view == 'view'){
                $currentCategories = Catalog::getChildsIds($currentCategory['id']);
                $currentCategories[] = $currentCategory['id'];

                if($currentCategory) {
                    $children = Category::find()->where(['parent_id' => $currentCategory['id']])->all();

                    if (isset($currentCategory->parent_id) && $currentCategory['parent_id'] > 0) {
                        $parent = Category::find()->where(['id' => $currentCategory['parent_id']])->all();
                    }

                    $model = $this->findModel($currentCategory['id']);
                    $searchModelProducts = new GoodsSearch();
                    $dataProviderProducts = $model->findCategoryProducts($currentCategories, Yii::$app->request->queryParams);
                    foreach ($dataProviderProducts->getModels() as $product) {
                        $urlList[$product->productId] = Category::getCategoryPath($product->categoryId, $this->catalogHash) . $product->productId;
                        $productsIds[] = $product->productId;
                    }
                    $variationsAllProductsList = !empty($productsIds) ? $model->findVariations($productsIds) : [];
                    $imagesAllProductsList = !empty($productsIds) ? Goods::findProductImages($productsIds) : [];
                    $stickersAllProductsList = !empty($productsIds) ? Goods::findProductStickers($productsIds) : [];

                    return $this->render($view, [
                        'model' => $model,
                        'children' => $children,
                        'parent' => $parent,
                        'dataProviderProducts' => $dataProviderProducts,
                        'searchModelProducts' => $searchModelProducts,
                        'breadcrumbsCatalog' => $breadcrumbsCatalog,
                        'variationsAllProductsList' => $variationsAllProductsList,
                        'imagesAllProductsList' => $imagesAllProductsList,
                        'stickersAllProductsList' => $stickersAllProductsList,
                        'urlList' => $urlList,
                    ]);
                }
            }else{
                $productId = end($alias);
                $model = Goods::find()
                    ->where(['id' => $productId,'status' => 1,'show' => 1,'confirm' => 1])
                    ->one();
                $variations = Goods::getProductVariants($productId);
                $tagsHash = Goods::getProductVariantsTagHash($productId);

                $productImages = GoodsImagesLinks::find()->where(['good_id' => $productId])->all();
                if(!$productImages){
                    $productImages = GoodsImages::find()->where(['good_id' => $productId])->all();
                }

                if(!$tagsHash){
                    $tags = [];
                }else{
                    foreach($tagsHash as $tag){
                        $tags[$tag->variationId][$tag->group_id][$tag->id] = $tag->value;
                    }
                }
                return $this->render($view, [
                    'model' => $model,
                    'variations' => $variations,
                    'tags' => $tags,
                    'productImages' => $productImages,
                ]);
            }
        }

        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProduct($id){
        return $this->render('product', [
            'model' => Goods::find()->where(['id' => $id])->one(),
        ]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
