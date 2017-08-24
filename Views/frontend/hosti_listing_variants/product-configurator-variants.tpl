{* Product configurator groups container separated by options type,whether they are media or not *}
{block name='frontend_listing_box_article_confogurator_groups_container'}
    {if $sArticle.sConfigurator}

        <div class="{if $isMedia}product--configurator--media{else}product--configurator{/if}">
            <div class="product--configurator--inner">
                {if $isMedia}
                    <div class="product-slider"
                         data-product-slider="true"
                         data-orientation="vertical">
                        <div class="product-slider--container">
                        {/if}
                        {foreach $sArticle.sConfigurator as $configuratorGroup}

                            {* Product configurator group container*}
                            {block name='frontend_listing_box_article_confogurator_group'}
                                {$optionsCount=0}

                                {* Product configurator group options container*}
                                {block name='frontend_listing_box_article_confogurator_group_options'}
                                    {capture name="configuratorGroupOptions"}
                                        {foreach $configuratorGroup.values as $option name="configGroup"}

                                            {* Product configurator group option*}
                                            {block name='frontend_listing_box_article_confogurator_group_option'}
                                                {if $option.selectable}
                                                    {$option.detailUrl = {url controller="detail" sArticle=$sArticle.articleID sCategory=$sArticle.categoryID}}
                                                    {if !!$option.media == $isMedia}
                                                        {$optionsCount=$optionsCount+1}

                                                        {* Product configurator group option inner*}
                                                        {block name='frontend_listing_box_article_confogurator_group_option_inner'}
                                                            <a href="{$option.detailUrl}{if $option.detailUrl|strpos:"?"}&{else}?{/if}group[{$option.groupID}]={$option.optionID}" 
                                                               class="product--variant--option {if $isMedia} product-slider--item{/if} is--main" 
                                                               title="{$option.optionname}">

                                                                {if $productBoxLayout == 'image'}
                                                                    {$thumbnailIndex=1}
                                                                {else}
                                                                    {$thumbnailIndex=0}
                                                                {/if}

                                                                {if $option.media}
                                                                    {* Product configurator group option media element*}
                                                                    {block name='frontend_listing_box_article_confogurator_group_option_media_element'}
                                                                        <img class="product--variant--option--media"
                                                                             src="{if isset($option.media.thumbnails)}{$option.media.thumbnails[{$thumbnailIndex}].source}{else}{link file='frontend/_public/src/img/no-picture.jpg'}{/if}" 
                                                                             srcset="{$option.media.thumbnails[{$thumbnailIndex}].sourceSet}"
                                                                             alt="{$option.optionname}" />
                                                                    {/block}
                                                                {else}
                                                                    {* Product configurator group option element*}
                                                                    {block name='frontend_listing_box_article_confogurator_group_option_element'}
                                                                        <span class="product--variant--option--name">
                                                                            {$option.optionname}
                                                                        </span>
                                                                    {/block}
                                                                {/if}
                                                            </a>
                                                        {/block}
                                                    {/if}
                                                {/if}
                                            {/block}

                                        {/foreach}
                                    {/capture}
                                {/block}

                                {* Product configurator group element*}
                                {block name='frontend_listing_box_article_confogurator_group_inner'}
                                    {if $optionsCount>0}
                                        {if $isMedia}
                                            {$smarty.capture.configuratorGroupOptions}
                                        {else}
                                            <div class="product--config--group group--{$configuratorGroup.groupname}">
                                                <h5 class="product--config--group--headline">
                                                    {$configuratorGroup.groupname}
                                                </h5>
                                                {$smarty.capture.configuratorGroupOptions}
                                            </div>
                                        {/if}
                                    {/if}
                                {/block}
                            {/block}

                        {/foreach}
                        {if $isMedia}
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    {/if}
{/block}