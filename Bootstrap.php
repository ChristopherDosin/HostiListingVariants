<?php

use ShopwarePlugins\HostiListingVariants\Bootstrap\Install,
    ShopwarePlugins\HostiListingVariants\Bootstrap\Update,
    ShopwarePlugins\HostiListingVariants\Bootstrap\Uninstall,
    ShopwarePlugins\HostiListingVariants\Subscriber;

/**
 * @link https://shopwareianer.com/
 * @copyright Copyright (c) 2017, Christopher Dosin
 * @author info@shopwareianer.com;
 * @package HostiListingVariants
 * @subpackage HostiListingVariants
 * @version 1.0.10 / 2017-02-22
 */
class Shopware_Plugins_Frontend_HostiListingVariants_Bootstrap extends Shopware_Components_Plugin_Bootstrap {

    /**
     * Get Capabilities of this plugin and return them as an array
     * @return array
     */
    public function getCapabilities() {
        return array('install' => true, 'update' => true, 'enable' => true);
    }

    /**
     * Returns Plugin Label string
     * @return string
     */
    public function getLabel() {
        return "Hosti Listing Variants";
    }

    /**
     * Returns Plugin Version string
     * @return string
     */
    public function getVersion() {
        return '1.0.10';
    }

    /**
     * Reads Plugins Meta Information
     * @return array
     */
    public function getInfo() {
        return array(
            'version' => $this->getVersion(),
            'autor' => '<a href="https://shopwareianer.com" target="_blank" title="Shopwareianer">Shopwareianer</a>',
            'copyright' => 'Copyright (c) 2017, Christopher Dosin',
            'label' => $this->getLabel(),
            'source' => '',
            'description' => '',
            'license' => '',
            'support' => 'info@shopwareianer.com',
            'link' => 'https://shopwareianer.com',
            'changes' => '',
            'revision' => '1'
        );
    }

    /**
     * Registers plugin namespace
     * @return void
     */
    public function afterInit() {
        Shopware()->Loader()->registerNamespace(
                'ShopwarePlugins\\HostiListingVariants', __DIR__ . '/'
        );
    }

    /**
     * Install the Plugin
     * @return bool
     */
    public function install() {
        $install = new Install();
        return $install->run();
    }

    /**
     * Update the Plugin
     * @return bool
     */
    public function update($version) {
        $update = new Update();
        return $update->run($version);
    }

    /**
     * Uninstall the Plugin
     * @return array
     */
    public function uninstall() {
        $uninstall = new Uninstall();
        return $uninstall->run();
    }

    /**
     * This callback function is triggered at the very beginning of the dispatch process and allows
     * us to register additional events on the fly. This way you won't ever need to reinstall you
     * plugin for new events - any event and hook can simply be registerend in the event subscribers
     */
    public function registerSubscriber(\Enlight_Event_EventArgs $args) {
        Shopware()->Template()->addTemplateDir(__DIR__ . '/Views');
        $subscribers = array(
            new Subscriber\Listing($this),
            new Subscriber\Less($this),
            new Subscriber\Javascript($this)
        );

        foreach ($subscribers as $subscriber) {
            $this->Application()->Events()->addSubscriber($subscriber);
        }
    }

}

?>
