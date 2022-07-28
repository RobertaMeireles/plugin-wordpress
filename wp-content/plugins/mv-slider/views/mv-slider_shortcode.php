<!-- titulo que refere-se ao texto do shortcode, caso o usuário não passe será pego o titulo do slider que o usuário configurou:
Podemos referir o conteúedo com shortcode como content, conforme a configuração do shortcode em class.mv-slider-shortcode.php
no class.mv-slider-settings.php existe um atributo statico $options, onde como é statico pode ser utilizado em outros arquivos
não precisa ser instanciado para utiliza-lo. esse atributo é um array e contem todos os conteúdos guardados na setting.
pode chamar o atributo com o nome da classe MV_Slider_Settings::$options['mv_slider_title']
-->
<h3><?php echo ( ! empty ( $content ) ) ? esc_html( $content ) : esc_html( MV_Slider_Settings::$options['mv_slider_title'] ); ?></h3>
<!-- Apresentar o estilo do css conforme o indicado pelo o usuário no admin no momento de cadastrar o criar o shortcode, caso ainda não tenha 
indicado, ou seja, falta informação na base de dados, vai 
indicar o estilo 1 como padão: -->
<div class="mv-slider flexslider <?php echo ( isset( MV_Slider_Settings::$options['mv_slider_style'] ) ) ? esc_attr( MV_Slider_Settings::$options['mv_slider_style'] ) : 'style-1'; ?>">
    <ul class="slides">
        <?php 
        
        // criar o loop para apresentar o cpt mv-slider
        // opçoes para exibição do cpt:
        $args = array(
            'post_type' => 'mv-slider',
            'post_status'   => 'publish',
            'post__in'  => $id,
            'orderby' => $orderby
        );

        // 
        $my_query = new WP_Query( $args );


        if( $my_query->have_posts() ):
            while( $my_query->have_posts() ) : $my_query->the_post();
            
            // armazenar o link do botão e a url do cpt que estiver correndo o loop
            // pegando o id do cpt, passando o name configurado como o array em mv-slider_metabox
            $button_text = get_post_meta( get_the_ID(), 'mv_slider_link_text', true );
            $button_url = get_post_meta( get_the_ID(), 'mv_slider_link_url', true );
    
        ?>
            <li>
            <?php       
            // se não tiver _thumbnail apresenta a imagem padrão em assets
                if( has_post_thumbnail() ){
                    the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) );
                }else{
                    echo "<img src='" . MV_SLIDER_URL . "assets/images/default.jpg' class='img-fluid wp-post-image' />";
                } ?>
                <div class="mvs-container">
                    <div class="slider-details-container">
                        <div class="wrapper">
                            <div class="slider-title">
                                <h2><?php the_title(); ?></h2>
                            </div>
                            <div class="slider-description">
                                <!-- preenchimento na tela do browser o conteúdo vindo da base de dados -->
                                <div class="subtitle"><?php the_content(); ?></div>
                                <a class="link" href="<?php echo esc_attr( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a>
                            </div>
                        </div>
                    </div>              
                </div>
            </li>
            <?php
            endwhile; 
            // chamar essa função para finalizar o loop pq é uma consulta personalizada e isso garante que não afete outras consultas
            wp_reset_postdata();
        endif; 
        ?>
        </ul>
    </div>