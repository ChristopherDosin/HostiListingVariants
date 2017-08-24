<?php

namespace ShopwarePlugins\HostiListingVariants\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_ActionEventArgs;
use ShopwarePlugins\HostiListingVariants\Components\Article;

class Listing implements SubscriberInterface {

    protected $bootstrap;

    /**
     * @var Context Service
     */
    protected $contextService;

    /**
     * @var Context Service
     */
    protected $categoryService;

    /**
     * Listing constructor.
     * @param \Shopware_Plugins_Frontend_HostiListingVariants_Bootstrap $bootstrap
     */
    public function __construct(\Shopware_Plugins_Frontend_HostiListingVariants_Bootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
        $this->contextService = Shopware()->Container()->get('shopware_storefront.context_service');
        $this->categoryService = Shopware()->Container()->get('shopware_storefront.category_service');
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return array(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => array('onPostDispatchFrontendListing', 0),
            'Enlight_Controller_Action_PostDispatchSecure_Widgets_Listing' => array('onPostDispatchWidgetsListing', 0),
            'Shopware_Modules_Articles_sGetArticlesByCategory_FilterLoopEnd' => array('onGetArticlesByCategoryFilterLoopEnd', 0)
        );
    }

    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchFrontendListing(Enlight_Controller_ActionEventArgs $args) {
        $controller = $args->getSubject();
        $View = $controller->View();

        $View->addTemplateDir($this->bootstrap->Path() . "Views/");

        $this->assignSecondImageConfig($controller);

        $this->extendCategoryAttributes($controller);
    }

    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchWidgetsListing(Enlight_Controller_ActionEventArgs $args) {
        $controller = $args->getSubject();
        $View = $controller->View();

        $View->addTemplateDir($this->bootstrap->Path() . "Views/");

        $this->assignSecondImageConfig($controller);
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onGetArticlesByCategoryFilterLoopEnd(\Enlight_Event_EventArgs $args) {
        $Article = $args->getReturn();
        $ArticlesModule = $args->getSubject();
        $image = $Article['image'];
        $Article = $ArticlesModule->sGetArticleById($Article['articleID']);
        $Article['image'] = $image;
        $args->setReturn($Article);
    }

    private function assignSecondImageConfig($controller) {
        $View = $controller->View();
        $Config = $this->bootstrap->Config();
        $listingSecondImage = $Config->listingSecondImage;

        $View->assign("listingSecondImage", $listingSecondImage);
    }

    private function extendCategoryAttributes($controller) {
        $View = $controller->View();
        $Request = $controller->Request();
        $sCategoryID = $Request->getParam('sCategory');
        $sCategoryContent = $View->getAssign('sCategoryContent');

        $sCategoriesIds = Shopware()->Modules()->Categories()->sGetCategoryPath($sCategoryID);
        if (count($sCategoriesIds) > 1) {
            $sCategoryContent['attribute']['disable_variants_view'] = $this->getCategoryAttribute($sCategoriesIds, 'disable_variants_view');
        }

        $View->assign("sCategoryContent", $sCategoryContent);
    }

    private function getCategoryAttribute($sCategoriesIds, $attrName) {
        $context = $this->contextService->getShopContext();
        $sCategories = $this->categoryService->getList($sCategoriesIds, $context);
        if (empty($sCategories)) {
            return;
        }


        foreach ($sCategories as $sCategory) {
            $attributes = $sCategory->getAttribute('core')->toArray();
            if ($attributes[$attrName]) {
                return $attributes[$attrName];
            }
        }
    }

}
