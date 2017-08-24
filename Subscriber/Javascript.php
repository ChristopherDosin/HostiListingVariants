<?php

namespace ShopwarePlugins\HostiListingVariants\Subscriber;

use Enlight\Event\SubscriberInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Javascript implements SubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            'Theme_Compiler_Collect_Plugin_Javascript' => 'addJsFiles'
        );
    }

    /**
     * Provide the file collection for js files
     *
     * @param Enlight_Event_EventArgs $args
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function addJsFiles(\Enlight_Event_EventArgs $args) {
        $jsFiles = array(
            dirname(__DIR__) . '/Views/frontend/_public/src/js/hosti_listing_variants.js'
        );
        return new ArrayCollection($jsFiles);
    }

}
