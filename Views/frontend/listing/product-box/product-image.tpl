{extends file="parent:frontend/listing/product-box/product-image.tpl"}

{* Product image *}
{block name='frontend_listing_box_article_image_picture_element'}
    {if $sArticle.images[0] && $listingSecondImage && $sArticle.disable_variants_view!='1' && $sCategoryContent.attribute.disable_variants_view!='1'}
        {block name="frontend_listing_box_article_image_picture_main_img"}
            <input class="main--image--src" type="hidden" value="{$sArticle.image.thumbnails[0].sourceSet}" />
        {/block}
        {block name="frontend_listing_box_article_image_picture_second_element"}
            <span class="image--media--inner">
                {$smarty.block.parent}
                {include file="frontend/hosti_listing_variants/product-second-image.tpl" thumbnailIndex=0}
            </span>
        {/block}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}