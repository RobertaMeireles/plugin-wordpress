<?php 

// FORMULÁRIO PARA APRESENTAR O METABOX NO TEMPLATE
    // pegar o id do post que está na tela utilizando o metodo wp get_post_meta
    // essa variavel post é global que vem de post-types / class.mv-slider-cpt.php / metodo add_inner_meta_boxes
    $meta = get_post_meta( $post->ID );
    // pegar o texto do botao do post que está na tela utilizando o metodo wp get_post_meta
    $link_text = get_post_meta( $post->ID, 'mv_slider_link_text', true );
    // pegar a url do botao do post que está na tela utilizando o metodo wp get_post_meta
    $link_url = get_post_meta( $post->ID, 'mv_slider_link_url', true );
    /*
    var_dump( $post ); RETORNO
    object(WP_Post)#8241 (24) { 
        ["ID"]=> int(10) 
        ["post_author"]=> string(1) "1" 
        ["post_date"]=> string(19) "2022-06-27 13:12:58" 
        ["post_date_gmt"]=> string(19) "2022-06-27 12:12:58" 
        ["post_content"]=> string(165) "Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos," 
        ["post_title"]=> string(8) "Slider 3" 
        ["post_excerpt"]=> string(0) "" 
        ["post_status"]=> string(7) "publish" 
        ["comment_status"]=> string(6) "closed" 
        ["ping_status"]=> string(6) "closed" 
        ["post_password"]=> string(0) "" 
        ["post_name"]=> string(8) "slider-3" 
        ["to_ping"]=> string(0) "" 
        ["pinged"]=> string(0) "" 
        ["post_modified"]=> string(19) "2022-06-27 13:12:58" 
        ["post_modified_gmt"]=> string(19) "2022-06-27 12:12:58" 
        ["post_content_filtered"]=> string(0) "" 
        ["post_parent"]=> int(0) ["guid"]=> string(49) "http://plugin.test/?post_type=mv-slider&p=10" 
        ["menu_order"]=> int(0) 
        ["post_type"]=> string(9) "mv-slider" 
        ["post_mime_type"]=> string(0) "" 
        ["comment_count"]=> string(1) "0" 
        ["filter"]=> string(3) "raw" }
    */
?>
<table class="form-table mv-slider-metabox"> 
<input type="hidden" name="mv_slider_nonce" value="<?php echo wp_create_nonce( "mv_slider_nonce" ); ?>">
    <tr>
        <th>
            <label for="mv_slider_link_text">Link Text</label>
        </th>
        <td>
            <input 
                type="text" 
                name="mv_slider_link_text" 
                id="mv_slider_link_text" 
                class="regular-text link-text"
                value="<?php echo ( isset( $link_text ) ) ? esc_html( $link_text ) : ''; ?>"
                required
            >
        </td>
    </tr>
    <tr>
        <th>
            <label for="mv_slider_link_url">Link URL</label>
        </th>
        <td>
            <input 
                type="url" 
                name="mv_slider_link_url" 
                id="mv_slider_link_url" 
                class="regular-text link-url"
                value="<?php echo ( isset( $link_url ) ) ? esc_url( $link_url ) : ''; ?>"
                required
            >
        </td>
    </tr>               
</table>