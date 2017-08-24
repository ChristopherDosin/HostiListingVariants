<?php

namespace ShopwarePlugins\HostiListingVariants\Bootstrap;

/**
 * Updates existing versions of the plugin
 *
 * Class Update
 * @package ShopwarePlugins\HostiListingVariants\Bootstrap
 */
class Update {

    private $bootstrap = null;
    private $version = null;

    /**
     * Constructor method of class
     */
    public function __construct() {
        $this->bootstrap = $this->Plugin();
    }

    /**
     * Runs plugin update
     */
    public function run($version) {
        $this->version = $version;
        $this->registerEvents();

        return array('success' => true, 'invalidateCache' => array('backend', 'frontend'));
    }

    /**
     * Registers Events
     * @return bool
     */
    private function registerEvents() {
        return true;
    }

    /**
     * @return \Shopware_Plugins_Frontend_HostiListingVariants_Bootstrap
     */
    private function Plugin() {
        return Shopware()->Plugins()->Frontend()->HostiListingVariants();
    }

}
