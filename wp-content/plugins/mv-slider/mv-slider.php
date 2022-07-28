<?php

/**
 * Plugin Name: MV Slider
 * Plugin URI: https://www.wordpress.org/mv-slider
 * Description: My plugin's description
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Roberta Meireles
 * Author URI: https://www.codigowp.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mv-slider
 * Domain Path: /languages
 */

 /*
MV Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
MV Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with MV Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


// SE NAO ESTIVER DEFINIDO 
if( ! defined( 'ABSPATH') ){
    exit;
}



if( ! class_exists( 'MV_Slider' ) ){
    class MV_Slider{
        function __construct(){
            $this->define_constants();

            // adicionar um submenu para a configuração do plugin
            add_action( 'admin_menu', array( $this, 'add_menu' ) );

            // Objeto criado para classe custom post type
            require_once( MV_SLIDER_PATH . 'post-types/class.mv-slider-cpt.php' );
            $MV_Slider_Post_Type = new MV_Slider_Post_Type();

            // objeto criado para classe do settings e options api
            require_once( MV_SLIDER_PATH . 'class.mv-slider-settings.php' );
            $MV_Slider_Settings = new MV_Slider_Settings();


            // objeto criado para classe do shortcode do plugin
            require_once( MV_SLIDER_PATH . 'shortcodes/class.mv-slider-shortcode.php' );
            $MV_Slider_Shortcode = new MV_Slider_Shortcode();

            // inserir css e js no plugin
            add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 999 );
        }

        public function define_constants(){
            define( 'MV_SLIDER_PATH', plugin_dir_path( __FILE__ ) );
            define( 'MV_SLIDER_URL', plugin_dir_url( __FILE__ ) );
            define( 'MV_SLIDER_VERSION', '1.0.0' );
        }

        public static function activate(){
            update_option( 'rewrite_rules', '' );
        }

        public static function deactivate(){
            flush_rewrite_rules();
            // Desinstalar o cpt
            unregister_post_type( 'mv-slider' );
        }

        public static function uninstall(){

        }

        // css e js para o pluguin
        public function register_scripts(){
            // descrição para chamar no class.mv-slider-shortcode.php / url do local do js ou css, array que precisa de pre requesito / versao / verdadeiro vir abaixo do body o js
            wp_register_script( 'mv-slider-main-jq', MV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js', array( 'jquery' ), MV_SLIDER_VERSION, true );
            wp_register_script( 'mv-slider-options-js', MV_SLIDER_URL . 'vendor/flexslider/flexslider.js', array( 'jquery' ), MV_SLIDER_VERSION, true );
            // all para todas as mídias
            wp_register_style( 'mv-slider-main-css', MV_SLIDER_URL . 'vendor/flexslider/flexslider.css', array(), MV_SLIDER_VERSION, 'all' );
            wp_register_style( 'mv-slider-style-css', MV_SLIDER_URL . 'assets/css/frontend.css', array(), MV_SLIDER_VERSION, 'all' );
        }

        // css e js para o admin. Utiliza a global para só carregar no admin
        public function register_admin_scripts(){
            global $typenow;
            if( $typenow == 'mv-slider'){
                wp_enqueue_style( 'mv-slider-admin', MV_SLIDER_URL . 'assets/css/admin.css' );
            }
        }


        // metodo para add o menu para o plugin no admin 
        public function add_menu(){
            // para add um menu de nivel mais alto pode add_menu_page pode receber 7 parametros, mas nenhum oobrigatório
            add_menu_page(
                //titulo da página do menu
                'MV Slider Options',
                // titulo do menu
                'MV Slider',
                // capacidade que o usuário precisa ter para acessar
                'manage_options',
                // o slug para a sua página no admin
                'mv_slider_admin',
                // função call back que irá apresentar o conteúdo do plugin
                array( $this, 'mv_slider_settings_page' ),
                // icone para o menu
                'dashicons-images-alt2'
            );


            // adicionar submenus para o menu, pode receber 7 parametros nao obrigatórios
            // adicionar submenus para o menu, pode receber 7 parametros nao obrigatórios
            add_submenu_page(
                //o slug do menu criado acima
                'mv_slider_admin',
                // titulo da página
                'Manage Slides',
                // Titulo do menu
                'Manage Slides',
                // capacidade que o usuário precisa ter para acessar
                'manage_options',
                // indicar o slug desse submenu, copiando o trecho da url da página que deseja inserir vide aula, item 26 em caso de duvida.
                'edit.php?post_type=mv-slider',
                // callback para indicar o conteúdo do submenu, como já existe em add_menu_page em array( $this, 'mv_slider_settings_page' ),  indicar null
                null,
                // valor da posição
                null
            );

            add_submenu_page(
                'mv_slider_admin',
                'Add New Slide',
                'Add New Slide',
                'manage_options',
                'post-new.php?post_type=mv-slider',
                null,
                null
            );

        }

        // metodo para apresentar o html da página de configuraçao do plugin com as seçoes e o formulario em html
        public function mv_slider_settings_page(){
            if ( !current_user_can('manage_options')) {
                return;
            }
            require( MV_SLIDER_PATH . 'views/settings-page.php' );
        }

    }
}

if( class_exists( 'MV_Slider' ) ){
    register_activation_hook( __FILE__, array( 'MV_Slider', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'MV_Slider', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'MV_Slider', 'uninstall' ) );

    $mv_slider = new MV_Slider();
} 
