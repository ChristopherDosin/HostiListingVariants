$(function () {
    HostiListingVariants.init();
});

var HostiListingVariants = {
    /**
     * Supports the browser the history api
     * @boolean
     */
    hasHistorySupport: Modernizr.history,
    defaults: {
        /**
         * Selector for the product box.
         * 
         * @type {String}
         */
        productBoxSelector: ".box--content--wrapper",
        /**
         * Selector for the product main image element.
         * 
         * @type {String}
         */
        productMainImageSelector: ".product--image",
        /**
         * Selector for the product second image element.
         * 
         * @type {String}
         */
        productSecondImageSelector: ".is--second",
        /**
         * Selector for the product main image source element.
         * 
         * @type {String}
         */
        productMainImageSrcSelector: ".main--image--src",
        /**
         * Selector for the product configurator with media options.
         * 
         * @type {String}
         */
        productConfigMediaSelector: ".product--configurator--media",
        /**
         * Selector for the product variant options.
         * 
         * @type {String}
         */
        productOptionSelector: ".product--variant--option",
        /**
         * Selector for the product variant options thumbnail link.
         * 
         * @type {String}
         */
        thumbnailLinkSelector: ".thumbnail--link",
        /**
         * Selector for the product variant options media.
         * 
         * @type {String}
         */
        productOptionMediaSelector: ".product--variant--option--media"
    },
    /**
     * Initialize the plugin
     * 
     * @method init
     * @return void
     */
    init: function () {
        var self = this;
        self.opt = this.defaults;

        self.manageDetailUrl();

        self.registerEventListeners();
        self.registerShopwareEventListeners();
        self.addShopwarePluginsForListingProducts();

        $.publish('plugin/HostiListingVariants/onInit', [self]);
    },
    /**
     * Registers all necessary event listeners for the plugin to proper operate.
     *
     * @public
     * @method registerEventListeners
     */
    registerEventListeners: function () {
        var self = this;

        self.registerMediaVariantChangeEvent();

        $.publish('plugin/HostiListingVariants/onRegisterEventListeners', [self]);
    },
    registerMediaVariantChangeEvent: function () {
        var self = this,
                $productBox = $(self.opt.productBoxSelector),
                $productMediaOption = $productBox.find(self.opt.productConfigMediaSelector).find(self.opt.productOptionSelector);

        $productMediaOption.off("mouseover").on("mouseover", $.proxy(self.onProductMediaOptionMouseover, self));
        $productMediaOption.off("mouseleave").on("mouseleave", $.proxy(self.onProductMediaOptionMouseleave, self));

        $.publish('plugin/HostiListingVariants/onRegisterMediaVariantChangeEvent', [
            self,
            $productBox,
            $productMediaOption
        ]);
    },
    onProductMediaOptionMouseover: function (event) {
        var self = this,
                $productMediaOption = $(event.currentTarget),
                optionImage = $productMediaOption.find(self.opt.productOptionMediaSelector).attr("srcset"),
                $productBox = $productMediaOption.closest(self.opt.productBoxSelector),
                $productMainImage = $productBox.find(self.opt.productMainImageSelector).find("img:not(" + self.opt.productSecondImageSelector + ")"),
                $productMainImageSrc = $productBox.find(self.opt.productMainImageSrcSelector).val();


        $productMainImage.attr("srcset", optionImage);

        $.publish('plugin/HostiListingVariants/onProductMediaOptionMouseover', [
            self,
            event,
            $productMediaOption,
            $productBox,
            $productMainImage,
            $productMainImageSrc
        ]);
    },
    onProductMediaOptionMouseleave: function (event) {
        var self = this,
                $productMediaOption = $(event.currentTarget),
                $productBox = $productMediaOption.closest(self.opt.productBoxSelector),
                $productMainImage = $productBox.find(self.opt.productMainImageSelector).find("img:not(" + self.opt.productSecondImageSelector + ")"),
                $productMainImageSrc = $productBox.find(self.opt.productMainImageSrcSelector).val();

        $productMainImage.attr("srcset", $productMainImageSrc);

        $.publish('plugin/HostiListingVariants/onProductMediaOptionMouseleave', [
            self,
            event,
            $productMediaOption,
            $productBox,
            $productMainImage,
            $productMainImageSrc
        ]);
    },
    /**
     * Registers all necessary event listeners for the plugin to proper operate.
     *
     * @public
     * @method registerShopwareEventListeners
     */
    registerShopwareEventListeners: function () {
        var self = this;

        $.subscribe('plugin/swInfiniteScrolling/onFetchNewPageFinished', $.proxy(self.onSWInfiniteScrollingOnFetchNewPageFinished, self));
        $.subscribe('plugin/swInfiniteScrolling/onLoadPreviousFinished', $.proxy(self.onSWInfiniteScrollingOnLoadPreviousFinished, self));
    },
    onSWInfiniteScrollingOnFetchNewPageFinished: function (ev, me, template) {
        var self = this;

        self.registerMediaVariantChangeEvent();
        self.addShopwarePluginsForListingProducts();
    },
    onSWInfiniteScrollingOnLoadPreviousFinished: function (ev, me, event, data) {
        var self = this;

        self.registerMediaVariantChangeEvent();
        self.addShopwarePluginsForListingProducts();
    },
    addShopwarePluginsForListingProducts: function () {
        var self = this;

        window.StateManager
                .addPlugin('*[data-product-slider="true"]', 'swProductSlider');
    },
    manageDetailUrl: function () {
        var self = this, ie;

        // Detecting IE version using feature detection (IE7+, browsers prior to IE7 are detected as 7)
        ie = (function () {
            if (window.ActiveXObject === 'undefined')
                return null;
            if (!document.querySelector)
                return 7;
            if (!document.addEventListener)
                return 8;
            if (!window.atob)
                return 9;
            if (!document.__proto__)
                return 10;
            return 11;
        })();

        if (ie && ie <= 9) {
            self.hasHistorySupport = false;
        }

        self.$productDetails = $(".product--details");

        if (self.$productDetails.length > 0) {
            self.changeDetailUrl();
        }
    },
    changeDetailUrl: function () {
        var self = this,
                stateObj = self._createHistoryStateObject(),
                ordernumber;

        ordernumber = $.trim(self.$productDetails.find('.entry--sku .entry--content').text());

        if (self.hasHistorySupport) {
            var location = stateObj.location + '?number=' + ordernumber;

            if (stateObj.params.hasOwnProperty('c')) {
                location += '&c=' + stateObj.params.c;
            }

            window.history.pushState(stateObj.state, stateObj.title, location);
        }
    },
    /**
     * Provides a state object which can be used with the {@link Window.history} API.
     *
     * The ordernumber will be fetched every time 'cause we're replacing the upper part of the detail page and
     * therefore we have to get the ordernumber using the DOM.
     *
     * @returns {Object} state object including title and location
     * @private
     */
    _createHistoryStateObject: function () {
        var me = this,
                $form = me.$productDetails.find('.configurator--form'),
                urlParams = me._getUrlParams(),
                location = me._getUrl();

        return {
            state: {
                type: 'sw-ajax-variants',
                values: $form.serialize(),
                scrollPos: $(window).scrollTop()
            },
            title: document.title,
            location: location,
            params: urlParams
        };
    },
    /**
     * Helper method which returns all available url parameters.
     * @returns {Object}
     * @private
     */
    _getUrlParams: function () {
        var url = window.decodeURIComponent(window.location.search.substring(1)),
                urlParams = url.split('&'),
                params = {};

        $.each(urlParams, function (i, param) {
            param = param.split('=');

            if (param[0].length && param[1].length && !params.hasOwnProperty(param[0])) {
                params[param[0]] = param[1];
            }
        });

        return params;
    },
    /**
     * Helper method which returns the full URL of the shop
     * @returns {string}
     * @private
     */
    _getUrl: function () {
        return window.location.protocol + "//" + window.location.host + window.location.pathname;
    }
};


$.overridePlugin('swProductSlider', {
    init: function () {
        var me = this;

        if (window.StateManager.isCurrentState(['xs', 's', 'm']) && me.$el.closest('.box--content--wrapper').length > 0) {
            me.$el.attr('data-orientation', 'horizontal');
        }

        me.superclass.init.apply(me, arguments);
    },
    initInfiniteSlide: function () {
        var me = this;

        if (me.$el.closest('.box--content--wrapper').length > 0) {
            return;
        }

        me.superclass.initInfiniteSlide.apply(me, arguments);
    }
});