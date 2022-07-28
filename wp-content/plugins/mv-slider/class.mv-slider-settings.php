<?php 

// Classe para adcionar as seções e campos (tabs no admin do wp) para que o usuário crie a configuração do plugin


if( ! class_exists( 'MV_Slider_Settings' )){
    class MV_Slider_Settings{

        // atributo static para não ter necessidade de criar um objeto para acessar o atributo e pode ser acessado publicamente
        // objetivo é guardar nesse atributo um array com os valores de todas as settings do plugin
        public static $options;

        public function __construct(){
            // como o atributo options é statico usa o self em vez do this
            // recebe o resltado de um metodo wp(get_option) essa função é uma das principais da api opitions
            // ela vai na tabela wp_options e busca um valor de um campo que tenha o mesmo campo que passar como paramentro no exemplo passado mv_slider_options
            self::$options = get_option( 'mv_slider_options' );

            // criar seçoes e campos para esse formulário é preciso utilizar o hook admin_init
            add_action( 'admin_init', array( $this, 'admin_init') );
        }

        // função para criar seçoes e campos para esse formulário
        // criado 1 seçao só um campo com texto simples e não terá gravação no banco de dados) apenas como informativo
        // criado 1 seção com 3 campos (1 caixa de texto, 1 campo checkbox e 2 campo select) os campos caixa de texto, checkbok e select são criados em funções 
        // callbacks a parte
        public function admin_init(){
            // registrar a chave para que a setting seja gravada na base de dados. isso é a setting que é um array de dados
            // primeiro parametro é chamado option group usado para agrupar todas as configurações 
            // o segundo parametro é o que está no self::$options = get_option( 'mv_slider_options' );
            // o terceiro paramentro é para o validador dos dados que serão enviados para o campo
            register_setting( 'mv_slider_group', 'mv_slider_options', array($this, 'mv_slider_validate'));


            // criar a 1 seçao (4 parametros aceita)
            add_settings_section(
                // id para a seção. vai precisar para ligar os campos com a seção
                'mv_slider_main_section',
                // titulo da seção isso apresenta na view
                'How does it work?',
                // uma função callback para apresentar um texto explicativo na view. esse texto fica logo abaoxo da seção.
                null,
                // a página na qual a seção vai aparecer. vai precisar para ligar os campos com a seção
                'mv_slider_page1'
            );

            // criar a 2 seçao (seção onde terá mais de um campo)
            add_settings_section(
                'mv_slider_second_section',
                'Other Plugin Options',
                null,
                'mv_slider_page2'
            );

            // criar um campo para a 1 seçao (primeiro campo que apresnta apenas uma string)
            add_settings_field(
                // id para o campo. é esse valor que vai recuperar o valor guardado no campo
                'mv_slider_shortcode',
                // titulo do campo que irá aparecer na view
                'Shortcode',
                // função callback que cria o conteúdo do campo
                array( $this, 'mv_slider_shortcode_callback' ),
                // a página que o campo deve aparecer, isso foi configurado na criação da seçao acima
                'mv_slider_page1',
                // o id da seção que deve aparecer
                'mv_slider_main_section',
            );

            // 1 campo da seçao 2
            // passando mais um atributo, o utilmo que é opcional, onde se pode passar argumento para a callback mv_slider_title_callback
            // onde foi passado o id do campo para uma label, assim ao clicar no titulo Slider Title seleciona o input para facilitar a experiência do usuário
            // nota: para receber esse parametro o metodo callback mv_slider_title_callback precisa esperar um atributo, escrito $args no método 
            add_settings_field(
                'mv_slider_title',
                'Slider Title',
                array( $this, 'mv_slider_title_callback' ),
                'mv_slider_page2',
                'mv_slider_second_section',
                array (
                    'label_for' => 'mv_slider_title'
                )
            );

            // 2 campo da seçao 2 
            add_settings_field(
                'mv_slider_bullets',
                'Display Bullets',
                array( $this, 'mv_slider_bullets_callback' ),
                'mv_slider_page2',
                'mv_slider_second_section',
                array (
                    'label_for' => 'mv_slider_bullets'
                )
            );

           // 3 campo da seçao 2
            add_settings_field(
                'mv_slider_style',
                'Slider Style',
                array( $this, 'mv_slider_style_callback' ),
                'mv_slider_page2',
                'mv_slider_second_section',
                array (
                    'items' => array (
                        'style-1',
                        'style-2',
                    ),
                    'label_for' => 'mv_slider_style',

                )
            );
        }

        // função callback para o conteúdo html do campo da seção 1
        public function mv_slider_shortcode_callback(){
            ?>
            <span>Use the shortcode [mv_slider] to display the slider in any page/post/widget</span>
            <?php
        }


        // função callback para o conteúdo html do campo 1 da seção 2
        // note que o name é uma junção (gruopo) do mv_slider_options, não será passado valores separados
        public function mv_slider_title_callback($args){
            ?>
                <input 
                type="text" 
                name="mv_slider_options[mv_slider_title]" 
                id="mv_slider_title"
                value="<?php echo isset( self::$options['mv_slider_title'] ) ? esc_attr( self::$options['mv_slider_title'] ) : ''; ?>"
                >
            <?php
        }
        

        // função callback para o conteúdo html do campo 2 da seção 2 (checkbox)
        // função checked é do wp e verifica se o campo já foi marcada (1). o terceiro parametro do checked é se eu quero só retornar 
        // o valor da comparação ou se eu quero mostrar ele na tela
        // caso a opção seja verdadeira (1) vai ganhar um checked=checked.
        public function mv_slider_bullets_callback($args){
            ?>
                <input 
                    type="checkbox"
                    name="mv_slider_options[mv_slider_bullets]"
                    id="mv_slider_bullets"
                    value="1"
                    <?php 
                        if( isset( self::$options['mv_slider_bullets'] ) ){
                            checked( "1", self::$options['mv_slider_bullets'], true );
                        }    
                    ?>
                />
                <label for="mv_slider_bullets">Whether to display bullets or not</label>
                
            <?php
        }


        // função callback para o conteúdo html do campo 3 da seção 2
        // como existe o metodo checked conforme acima, existe o slected tb, se existir o valor style-1 ou 2 no banco aparece na tela
        // feito um foreach vindo style-1 e style-2 como parametro na criação do campo em add_settings_field
        // caso a opção seja verdadeira vai ganhar um select=select.
        public function mv_slider_style_callback($args){
            ?>
            <select 
                id="mv_slider_style" 
                name="mv_slider_options[mv_slider_style]">
                <?php foreach ($args['items'] as $key => $item) : ?>
                        <option value= "<?php echo esc_attr($item) ?>"
                    <?php isset( self::$options['mv_slider_style'] ) ? selected( $item, self::$options['mv_slider_style'], true ) : ''; ?>
                >
                    <!-- escrever na tela usando o escapamento -->
                    <?php echo esc_html(ucfirst($item)); ?>
                <?php endforeach; ?>

                <!-- versoa sem foreach, sem passar argumentos para o método callback -->
                <!-- <option value="style-1" 
                    <?php // isset( self::$options['mv_slider_style'] ) ? selected( 'style-1', self::$options['mv_slider_style'], true ) : ''; ?>>Style-1</option>
                <option value="style-2" 
                    <?php // isset( self::$options['mv_slider_style'] ) ? selected( 'style-2', self::$options['mv_slider_style'], true ) : ''; ?>>Style-2</option> -->
            </select>
            <?php
        }

        // metodo para validar parametros antes de salvar na base de dados
        // esse input que está no parametro é um array
        // no final é retornar um valor com os resultados das validações que foi feito nesse metodo mv_slider_validate
        // quem envia para o banco é o metodo acima register_setting
        // utiliza a função wp sanitize_text_field pq todos sao strings, caso tenha outros tipos sem ser string é preciso um case para cada key
        // no video, aula 33 aprensenta um exemplo.
        public function mv_slider_validate( $input ){
            $new_input = array();
            $msg = true;
            foreach( $input as $key => $value ){
                switch ($key){
                    case 'mv_slider_title':
                        if( empty( $value )){ 
                            // se caso estiver em branco um dos campos e deseja inserir a informaçao Please, type some text faça o que está comentado
                            // $value = 'Please, type some text';
                            // $new_input[$key] = sanitize_text_field( $value );
                            $msg = false;
                            add_settings_error('your_setting_key',
                            'my_plg_validate_num_tags_error',
                            'Incorrect value entered!',
                            'error');
                        } else {
                            $new_input[$key] = sanitize_text_field( $value ); 
                        }
                    break;
                    default:
                        $new_input[$key] = sanitize_text_field( $value );
                    break;
                }

            }
            if ($msg === true) {
                add_settings_error('your_setting_key',
                'my_plg_validate_num_tags_error',
                'Thanks',
                'success');
                return $new_input;
            } else {
                return null;
            }
        }

    }
}
