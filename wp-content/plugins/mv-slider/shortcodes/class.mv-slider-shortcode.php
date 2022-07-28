<?php 

if( ! class_exists('MV_Slider_Shortcode')){
    class MV_Slider_Shortcode{
        public function __construct(){
            // adcionar o shortcode, a funçao add_shortcode aceita 2 paramentros, 1
            // 1 a tag do shortcode (mv_slider) e o método callback
            add_shortcode( 'mv_slider', array( $this, 'add_shortcode' ) );
        }

        // os parametros não sao obrigatorios.
        // o 1 seria um array de atributos que serão passados para o shortcode. isso usado quando é preciso passar paramentros para oshortcode
        // content é se caso o shortcode apresenta conteudo, isso ajuda a identificar o shortcode que apresenta tag de fechamento ou não.
        // aqueles que não tem tag de fechamento o conteúdo é sempre nulo.
        // tag corresponde a propria tag do shortcode
        public function add_shortcode( $atts = array(), $content = null, $tag = '' ){

            // regra para todos os atributos devem iniciar com letras minusculas
            $atts = array_change_key_case( (array) $atts, CASE_LOWER );

            // a função extract do php é tranformar cada item em uma variavel
            // passar alguns atributos padroes para o shortcode (id e oderby)
            // o id seria os id dos posts para escolher qual slider será passado no slider show e o orderby a escolha de ordenaçao que 
            // no exemplo é ordem da criação
            // segundo paramentro $atts é de onde sairia essa lista de atributos 
            // terceiro é opcional que habilita um filtro especifico para o shortcode, não explicado aqui nesse exemplo
            extract( shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'date'
                ),
                $atts,
                $tag
            ));

            // juntar os id's passados em um array chamado $id com os ids separados por , 
            // função absint faz uma conversao só apara numeros inteiros, 
            if( !empty( $id ) ){
                $id = array_map( 'absint', explode( ',', $id ) );
            }

            // shortcode é um filtro e precisa retornar algo, se nao retornar pode ter problema ao add e excluir algum json aparecendo na tela
            // uma saida de resolver isso do return é sequestrar toda a saída html que est+a gerando e enviar para um bufer interno (especie de memória)
            // para isso chamar a função ob_start(), onde vai pegar toda a saída html e jogar no buffer
            ob_start();
            // require do html do shortcode, nao requere_once pq o usuário pode querer colocar mais de um slider no site.
            require( MV_SLIDER_PATH . 'views/mv-slider_shortcode.php');
            // chamar os css e js criado em mv-slider.php / método register_scripts
            wp_enqueue_script( 'mv-slider-main-jq' );
            wp_enqueue_script( 'mv-slider-options-js' );
            wp_enqueue_style( 'mv-slider-main-css' );
            wp_enqueue_style( 'mv-slider-style-css' );
            // e depois disspo retornando o html com a função ob_get_clean();
            return ob_get_clean();
        }
    }
}