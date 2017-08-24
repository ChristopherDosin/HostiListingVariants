<?php

namespace ShopwarePlugins\HostiListingVariants\Bootstrap;

/**
 * The install class does the basic setup of the plugin. All operations should be implemented in a way
 * that they can also be run on update of the plugin
 *
 * Class Install
 * @package ShopwarePlugins\HostiListingVariants\Bootstrap
 */
class Install {

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
     * Runs plugin installation
     */
    public function run() {
        $this->createAttributes();
        $this->createMyForm();
        $this->registerEvents();

        return array('success' => true, 'invalidateCache' => array('backend', 'frontend'));
    }

    private function createMyForm() {
        $form = $this->bootstrap->Form();
        $form->setName('HostiListingVariants');

        $form->setElement('checkbox', 'listingSecondImage', array(
            'label' => 'Whether or not show second image in listing on hover',
            'value' => true
        ));

        $form->save();
    }

    /**
     * Creates new attribute
     */
    private function createAttributes() {
        try {
            $this->createArticleAttributes();
            $this->createCategoryAttributes();
        } catch (Exception $exc) {
            
        }
    }

    private function createArticleAttributes() {
        $this->crudService->update('s_articles_attributes', 'disable_variants_view', 'boolean', [
            'label' => 'Disable article variants view on listing.',
            'supportText' => 'Disable article variants view on listing.',
            'helpText' => 'Disable article variants view on listing.',
            'translatable' => true,
            'displayInBackend' => true,
            'position' => 0,
            'custom' => false
        ]);
    }

    private function createCategoryAttributes() {
        $this->crudService->update('s_categories_attributes', 'disable_variants_view', 'boolean', [
            'label' => 'Disable article variants view on listing',
            'supportText' => 'Disable article variants view on listing.',
            'helpText' => 'Disable article variants view on listing.',
            'translatable' => true,
            'displayInBackend' => true,
            'position' => 0,
            'custom' => false
        ]);
    }

    /**
     * Registers Events
     * @return bool
     */
    private function registerEvents() {
        $this->bootstrap->subscribeEvent('Enlight_Controller_Front_StartDispatch', 'registerSubscriber');
        $this->bootstrap->subscribeEvent('Shopware_Console_Add_Command', 'registerSubscriber');
        return true;
    }

    /**
     * @return \Shopware_Plugins_Frontend_HostiListingVariants_Bootstrap
     */
    private function Plugin() {
        return Shopware()->Plugins()->Frontend()->HostiListingVariants();
    }

}
