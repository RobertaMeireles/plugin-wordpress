<!-- template html para apresentar as seçoes os campos do formulário criado em mv-slider-metabox.php -->

<!-- padrao sempre wrap -->
<div class="wrap">
    
    <!-- vai escrever o titulo do menu na tela, irá escrever  'MV Slider Options', que veio do add_menu_page -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <!-- incluir as tabs utilizando as classes do wp que fazem isso -->
    <?php 
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'main_options';
    ?>
    <h2 class="nav-tab-wrapper">
        <!-- incluir o link do href com o valor da tab -->
        <a href="?page=mv_slider_admin&tab=main_options" class="nav-tab <?php echo $active_tab == 'main_options' ? 'nav-tab-active' : ''; ?>">Main Options</a>
        <a href="?page=mv_slider_admin&tab=additional_options" class="nav-tab <?php echo $active_tab == 'additional_options' ? 'nav-tab-active' : ''; ?>">Additional Options</a>
    </h2>


    <!-- aproveite o options.php do wp. esse arquivo processa todos os forms do wp -->
    <form action="options.php" method="post">
        <!-- sera chamdo as seçoes (grupo de seçoes/controles), os campos do formulários e por fim o botão que envia os dados do formulário: -->
        <?php 

        // settings_field cria o nonon para nós 
        settings_fields( 'mv_slider_group' ); // o valor do grupo de opçoes registrados em class.mv-slider-settings.php em register_setting
        if( $active_tab == 'main_options' ){
            // mostra o conteúdo das seçoes e campos
            do_settings_sections( 'mv_slider_page1' ); // o valor da pagina que deseja apresentar a seçao esta em class.mv-slider-settings.php em add_settings_section
        }else{
            do_settings_sections( 'mv_slider_page2' ); // o valor da pagina que deseja apresentar a seçao esta em class.mv-slider-settings.php em add_settings_section
            // o botão
            submit_button( 'Save Settings' ); // botão com o texto que deseja
            // apresentar os error e o sucesso da validaçao dos campos 
            settings_errors('your_setting_key');
        }
    
    ?>
    </form>
</div>