<?php 

// CLASSE PARA CRIAR POST TYPE E MENU

if( !class_exists( 'MV_Slider_Post_Type') ){
    class MV_Slider_Post_Type{
        function __construct(){
            // criar o post type
            add_action( 'init', array( $this, 'create_post_type' ) );
            // criar a meta box para o cpt, criando novos campos
            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            // hook para salvar os dados de uma meta box na base de dados
            // importante passar a prioridade 10 e receber 2 parametros
            add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );


            // filtrar os dados para apresentar os metabox criados na tela onde lista todos os cpt mv-slider

            // o hook filtro para filtrar as colunas que existe no wp e acrecentar novas colunas.
            // nunca tera o mesmo nome, 1 manage_<nome do teu cpt>_posts_columns manage_mv-slider_posts_columns é para apresentar as colunas na tela
            add_filter( 'manage_mv-slider_posts_columns', array( $this, 'mv_slider_cpt_columns' ) );

            // hook de ação para listar os dados já inseridos na base de dados (o nome do hook segue a explicação acima que nunca é o mesmo nome)
            // 2 pq precisa qual coluna vai ser add e o id do post
            add_action( 'manage_mv-slider_posts_custom_column', array( $this, 'mv_slider_custom_columns'), 10, 2 );

            // hook para liberar que apareça a setinha ao lado do titulo da coluna para ordernar crescente e decrescente a coluna
            add_filter( 'manage_edit-mv-slider_sortable_columns', array( $this, 'mv_slider_sortable_columns' ) );
        }

        // metodo para criar o post type
        public function create_post_type(){
            register_post_type(
                'mv-slider',
                array(
                    'label' => 'Slider',
                    'description'   => 'Sliders',
                    'labels' => array(
                        'name'  => 'Sliders',
                        'singular_name' => 'Slider'
                    ),
                    'public'    => true,
                    'supports'  => array( 'title', 'editor', 'thumbnail' ),
                    'hierarchical'  => false,
                    'show_ui'   => true,
                    'show_in_menu'  => false,
                    // para aparecer o cpt no menu indique true, indicado falso pq foi criado um menu no mv-slider.php para a configuração do menu
                    // 'show_in_menu'  => true,
                    'menu_position' => 5,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export'    => true,
                    'has_archive'   => false,
                    'exclude_from_search'   => false,
                    'publicly_queryable'    => true,
                    'show_in_rest'  => true,
                    'menu_icon' => 'dashicons-images-alt2',
                    //'register_meta_box_cb'  =>  array( $this, 'add_meta_boxes' )
                )
            );
        }


        // 3 METODOS PARA APRESENTAR OS DADOS DOS METADADOS NA LISTAGEM DOS POSTS NO ADM
        // metodo para o hook filtro (manage_mv-slider_posts_columns) para apresentar as colunas na listagem dos posts
        public function mv_slider_cpt_columns( $columns ){
            // passando a chave do metadados (name do html) e o escapamento do dado (esc_html__) (__ é a traduçao) 
            //Link Text seria o titulo da coluna e 'mv-slider' o valor para auxiliar na tradução
            $columns = array(
                'cb' => $columns['cb'],
                'title' => __( 'Title' ),
                'mv_slider_link_text' => esc_html__( 'Link Text', 'mv-slider' ),
                'mv_slider_link_url' => esc_html__( 'Link URL', 'mv-slider' ),
                'date' => __( 'Date' ),
            );
            return $columns;

            // outra opção onde não precisa pode se tirar o campo title ou data, cb se não citar aparece, cb é a edição rápida 
            // $columns['mv_slider_link_text'] = esc_html__( 'Link Text', 'mv-slider' );
            // $columns['mv_slider_link_url'] = esc_html__( 'Link URL', 'mv-slider' );
            // return $columns;
        }


        // inserir os valores salvos na base de dados 
        public function mv_slider_custom_columns( $column, $post_id ){
            switch( $column ){
                case 'mv_slider_link_text':
                    // TRUE pq não quer um array e sim só uma string
                    echo esc_html( get_post_meta( $post_id, 'mv_slider_link_text', true ) );
                break;
                case 'mv_slider_link_url':
                    echo esc_url( get_post_meta( $post_id, 'mv_slider_link_url', true ) );
                break;                
            }
        }


        // ter a opção de ordernar as colunas ao clicar na setinha ao lado do titulo da coluna
        public function mv_slider_sortable_columns( $columns ){
            // indicar qual é a coluna que deseja que apresente a seta 
            $columns['mv_slider_link_text'] = 'mv_slider_link_text';
            return $columns;
        }



        // função para adcionar os meta boxes, os parametros de 4 a 7 são opcionais
        public function add_meta_boxes(){
            // essa função wp add_meta_box é uma função wp que adciona os meta box em uma das telas do admin
            add_meta_box(
                // primeiro parametro é o id para a meta box
                'mv_slider_meta_box',
                // segundo parametro é o titulo da meta box
                'Link Options',
                // terceiro paramentro é uma função callback que serve para preencher o conteúdo da meta box. 
                // ou seja terá um formulário com alguns campos e essa função vai fornecer alguns campos  
                array( $this, 'add_inner_meta_boxes' ),
                // quarto paramentro é uma tela qual a meta box vai aparecer, uma das formas é passando a chave de um cpt
                'mv-slider',
                // quinto paramentro é chamado de contexto onde define a posiçao da caixa dentro da area de edição
                // side ficaria do lado direito e normal na parte de baixo.
                'normal',
                // sexto paramentro é a prioridade como apresenta uma meta box que tenha o mesmo contexto 
                'high'
                // setimo paramentro é um array que pode passar valores para a sua função callback add_inner_meta_boxes
                // deverá receber como segundo parametro esse array, claro além do $post passado abaixo 
            );
        }

        // metodo para apresentar o html no post para preecnhimento dos dados
        public function add_inner_meta_boxes( $post ){
            require_once( MV_SLIDER_PATH . 'views/mv-slider_metabox.php' );
        }

        // metodo para salvar o dado na base de dados nas tabelas de meta ex tabela wp_postmeta.
        // utilizando o parametro $post_id
        // funções para o crud do banco de dados:
        // get_post_meta / update_post_meta / delete_post_meta / add_post_meta para outras tabelas de meta dados como users, comments, terms 
        // apenas altere de post para o nome da tabela ex: add_user_meta
        public function save_post( $post_id ){
            // checar se existe o nonce, pegando o name que vem do form em 'views/mv-slider_metabox.php'
            if( isset( $_POST['mv_slider_nonce'] ) ){
                // verificar se o nonce é o valor que eu espero com o wp_verify_nonce
                if( ! wp_verify_nonce( $_POST['mv_slider_nonce'], 'mv_slider_nonce' ) ){
                    return;
                }
            }

            // garatir que os dados vão ser salvos apenas quando o usuário clicar no botão de salvar e nõa
            // em processos como data storage que guarda dados do navegador
            if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
                return;
            }

            // verificar se está no cpt correto
            if( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'mv-slider' ){
                // verificar se o usuário logado tem a capacidade de editar páginas
                if( ! current_user_can( 'edit_page', $post_id ) ){
                    return;
                // verificar se o usuário logado tem a capacidade de editar posts
                }elseif( ! current_user_can( 'edit_post', $post_id ) ){
                    return;
                }
            }


            // verificar se os dados do metabox foram enviados pelo formulario
            if( isset( $_POST['action'] ) && $_POST['action'] == 'editpost' ){
                // pegar o valor já existente, 3 parametros id do post, nome da chave da tabela (mesmo nome do name do input do views/mv-slider_metabox.php )
                // e por utilmo se deseja pegar o valor da chave como uma string ou array, true é string
                $old_link_text = get_post_meta( $post_id, 'mv_slider_link_text', true );
                // pegar o novo valor que vem do formulário
                $new_link_text = $_POST['mv_slider_link_text'];

                // o mesmo que acima (link) para o campo url
                $old_link_url = get_post_meta( $post_id, 'mv_slider_link_url', true );
                $new_link_url = $_POST['mv_slider_link_url'];

                // salvar os valores com sanitize para segurança
                if( empty( $new_link_text )){
                    update_post_meta( $post_id, 'mv_slider_link_text', 'Add some text' );
                }else{
                    update_post_meta( $post_id, 'mv_slider_link_text', sanitize_text_field( $new_link_text ), $old_link_text );
                }

                if( empty( $new_link_url )){
                    update_post_meta( $post_id, 'mv_slider_link_url', '#' );
                }else{
                    update_post_meta( $post_id, 'mv_slider_link_url', sanitize_text_field( $new_link_url ), $old_link_url );
                }
            }
        }

    }
}
