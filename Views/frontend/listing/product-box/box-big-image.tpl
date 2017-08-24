{extends file="parent:frontend/listing/product-box/box-big-image.tpl"}
{*{namespace name='frontend/hosti_listing_variants/snippets'}*}
{namespace name="frontend/listing/box_article"}

{* Product image *}
{block name='frontend_listing_box_article_image_picture_element'}
    {if $sArticle.images[1] && $listingSecondImage && $sArticle.disable_variants_view!='1' && $sCategoryContent.attribute.disable_variants_view!='1'}
        {block name="frontend_listing_box_article_image_picture_main_img"}
            <input class="main--image--src" type="hidden" value="{$sArticle.image.thumbnails[1].sourceSet}" />
        {/block}
        {block name="frontend_listing_box_article_image_picture_second_element"}
            <span class="image--media--inner">
                {$smarty.block.parent}
                {include file="frontend/hosti_listing_variants/product-second-image.tpl" thumbnailIndex=1}
            </span>
        {/block}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}

{* Product image *}
{block name='frontend_listing_box_article_picture'}
    {$smarty.block.parent}    
    {if $sCategoryContent.attribute.disable_variants_view!='1'}
        <div class="product--variants--image--slider">
            {if $sArticle.disable_variants_view!='1'}
                {include file="frontend/hosti_listing_variants/product-configurator-variants.tpl" isMedia=true}
            {/if}
        </div>
    {/if}
{/block}