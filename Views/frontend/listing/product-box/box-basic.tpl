{extends file="parent:frontend/listing/product-box/box-basic.tpl"}
{*{namespace name='frontend/hosti_listing_variants/snippets'}*}
{namespace name="frontend/listing/box_article"}

{block name="frontend_listing_box_article_content"}
    {if $sArticle.sConfigurator && $sArticle.disable_variants_view!='1' && $sCategoryContent.attribute.disable_variants_view!='1'}
        <div class="box--content--wrapper">
            {$smarty.block.parent}    
        </div>
    {else}
        {$smarty.block.parent}    
    {/if}
{/block}

{block name='frontend_listing_box_article_info_container'}
    {$smarty.block.parent}    
    {block name='frontend_listing_box_article_content_confogurator'}
        {if $sArticle.disable_variants_view!='1' && $sCategoryContent.attribute.disable_variants_view!='1'}
            {include file="frontend/hosti_listing_variants/product-configurator-variants.tpl"}
        {/if}
    {/block}
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