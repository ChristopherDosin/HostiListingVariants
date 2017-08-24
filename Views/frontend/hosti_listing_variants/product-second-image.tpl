{block name="frontend_listing_box_article_image_picture_second_element_img"}
    <img class="is--second"
         srcset="{$sArticle.images[0].thumbnails[{$thumbnailIndex}].sourceSet}"
         alt="{$desc}"
         title="{$desc|truncate:25:""}" />
{/block}