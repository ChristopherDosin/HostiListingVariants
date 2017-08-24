<?php

namespace ShopwarePlugins\HostiListingVariants\Bootstrap;

/**
 * Uninstaller of the plugin.
 *
 * Class Uninstall
 * @package ShopwarePlugins\HostiListingVariants\Bootstrap
 */
class Uninstall {

    private $bootstrap = null;

    /**
     * @var Crud Service
     */
    private $crudService = null;

    /**
     * Constructor method of class
     */
    public function __construct() {
        $this->bootstrap = $this->Plugin();
        $this->crudService = $this->Plugin()->get('shopware_attribute.crud_service');
    }

    /**
     * Runs plugin uninstallation
     */
    public function run() {
        $this->deleteAttributes();
        return array('success' => true, 'invalidateCache' => array('backend', 'frontend'));
    }

    /**
     * Creates new attribute
     */
    private function deleteAttributes() {
        try {
            $this->deleteArticleAttributes();
            $this->deleteCategoryAttributes();
        } catch (Exception $exc) {
            
        }
    }

    private function deleteArticleAttributes() {
        $this->crudService->delete('s_articles_attributes', 'disable_variants_view');
    }

    private function deleteCategoryAttributes() {
        $this->crudService->delete('s_categories_attributes', 'disable_variants_view');
    }

    /**
     * @return \Shopware_Plugins_Frontend_HostiListingVariants_Bootstrap
     */
    private function Plugin() {
        return Shopware()->Plugins()->Frontend()->HostiListingVariants();
    }

}
