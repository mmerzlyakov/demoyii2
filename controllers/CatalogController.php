<?php

namespace app\controllers;

use app\models\GoodsSearch;
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

    public function init() {
        $this->catalogMenu = Menu::getStructure();
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
        $children = [];
        $parent = [];
        $currentCategory = false;
        $breadcrumbsCatalog = [];
        if(!$alias){
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'breadcrumbsCatalog' => $breadcrumbsCatalog,
            ]);
        }else{
            $alias = explode('/',$alias);
            $breadcrumbsCatalog = Catalog::findBreadcrumbs($alias);
            $currentCategory = end($breadcrumbsCatalog);

            $currentCategories = Catalog::getChildsIds($currentCategory->id);
            $currentCategories[] = $currentCategory->id;
        }

        if($currentCategory){
            $children = Category::find()->where(['parent_id' => $currentCategory->id])->all();

            if(isset($currentCategory->parent_id) && $currentCategory->parent_id > 0){
                $parent = Category::find()->where(['id' => $currentCategory->parent_id])->all();
            }

            $model = $this->findModel($currentCategory->id);
            $searchModelProducts = new GoodsSearch();
            $dataProviderProducts = $model->findCategoryProducts($currentCategories,Yii::$app->request->queryParams);
            foreach($dataProviderProducts->getModels() as $product){
                $productsIds[] = $product->productId;
            }
            $variationsAllProductsList = !empty($productsIds)?$model->findVariations($productsIds):[];
            $imagesAllProductsList = !empty($productsIds)?Goods::findProductImages($productsIds):[];
            $stickersAllProductsList = !empty($productsIds)?Goods::findProductStickers($productsIds):[];

            return $this->render('view', [
                'model' => $model,
                'children' => $children,
                'parent' => $parent,
                'dataProviderProducts' => $dataProviderProducts,
                'searchModelProducts' => $searchModelProducts,
                'breadcrumbsCatalog' => $breadcrumbsCatalog,
                'variationsAllProductsList' => $variationsAllProductsList,
                'imagesAllProductsList' => $imagesAllProductsList,
                'stickersAllProductsList' => $stickersAllProductsList,
            ]);
        }else{
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'breadcrumbsCatalog' => $breadcrumbsCatalog,
            ]);
        }
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
