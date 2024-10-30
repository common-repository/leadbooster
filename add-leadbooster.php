<?php
/*
 * Plugin Name: LeadBooster by Nexline
 * Text Domain: leadbooster-nexline
 * Plugin URI: https://leadbooster.com.br
 * Description: Ative o LeadBooster no seu site utilizando esse plugin do Wordpress.
 * Version: 0.1
 * Author: Nexline Advertising
 * Author URI: https://github.com/nexline
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists('LeadBoosterByNexline' ) ) {

  /*** Wrapper class to isolate us from the global space in order to prevent method collision */

  class LeadBoosterByNexline {

    /*** Set up all actions, instantiate other */
    function __construct() {
      add_action( 'admin_menu', array( $this, 'add_admin' ) );
      add_action( 'admin_init', array( $this, 'admin_init' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'leadbooster_admin_js' ) );
      add_action( 'wp_head', array( $this, 'leadbooster_display_js' ) );
    }

    /*** Add our options to the settings menu */
    function add_admin() {
      add_menu_page('LeadBooster by Nexline', 'LeadBooster', 'manage_options', 'leadbooster-nexline', array( $this, 'plugin_options_page' ), 'dashicons-format-status', 51 );
    }

    /** Callback for options page - set up page title and instantiate field */
    function plugin_options_page() {
?>
      <div class="plugin-options">
        <h2><span>Adicione o código do seu Chatbot</span></h2>
        <h4><span>Não possui um Chatbot ainda? <a href="https://leadbooster.com.br" target="_blank">Clique Aqui</a> para criar o seu</span></h4>
        <form action="options.php" method="post">
<?php
        settings_fields( 'leadbooster-nexline_options' );
        do_settings_sections( 'leadbooster-nexline' );
?>
          <input name="Submit" type="submit" value="<?php esc_attr_e( 'Salvar' ); ?>" />
        </form>
      </div>
<?php
    }

    /** Define options section (only one) and fields (also only one!)*/
    function admin_init() {
      register_setting( 'leadbooster-nexline_options', 'leadbooster-nexline_options', array( $this, 'options_validate' ) );
      add_settings_section( 'leadbooster-nexline_section', '', array( $this, 'main_section' ), 'leadbooster-nexline' );
      add_settings_field( 'leadbooster-nexline_string', 'Código do seu Chatbot', array( $this, 'text_field'), 'leadbooster-nexline', 'leadbooster-nexline_section');
    }

    /** Static content for options section*/
    function main_section() {
      // GNDN
    }

    /** Code for field*/
    function text_field() {
      $options = get_option( 'leadbooster-nexline_options' );
?>

      <input type="text" id="leadbooster-nexline_options" name="leadbooster-nexline_options[text_string]" value="<?php echo esc_attr( $options['text_string'] ); ?>" size="90" />

<?php
    }

    /** No validation, just remove leading and trailing space*/
    function options_validate($input) {
      $newinput['text_string'] = trim( $input['text_string'] );
      return $newinput;
    }

    /* Display the code(s) on the admin page.*/
    function leadbooster_admin_js() {
      if (is_admin()) {
        wp_enqueue_script(
            'leadbooster-admin-script',
            plugins_url( '/js/admin.js', __FILE__ ),
            array(),
            null,
            true
          );
      }
    }

    /* Display the code(s) on the public page. We do an extra check to ensure that the codes don't show up in the admin tool. */
    function leadbooster_display_js() {
      if (!is_admin()) {
          $options = get_option('leadbooster-nexline_options');
          wp_enqueue_script(
              'leadbooster-chatbot-script',
              'https://leadbooster.com.br/chatbot/' . esc_html($options['text_string']) . '.js',
              array(),
              null,
              true
          );
      }
    }
  }
}

/** Sanity - was there a problem setting up the class? If so, bail with error Otherwise, class is now defined; create a new one it to get the ball rolling. */
if( class_exists( 'LeadBoosterByNexline' ) ) {
  new LeadBoosterByNexline();
} else {
  $message = "<h2 style='color:red'>Erro no Plugin</h2>
  <p>Desculpe! Não foi possível iniciar o plugin <span style='color:blue;font-family:monospace'>leadbooster-nexline</span></p>
  <p><a href='mailto:lead@leadbooster.com.br?subject=Plugin%20Error&body=Qual versão do Wordpress você está executando? Relate aqui no seu email para que possamos ajudar</p>
  <ul><li>Verifique se você está executando a versão mais recente do plug-in; atualize o plugin se não.</li>
  <li>Pode haver um conflito com outros plugins. Você pode tentar desabilitar todos os outros plugins; se o problema desaparecer, há um conflito.</li>
  <li>Tente um tema diferente para ver se há algum conflito entre o tema e o plug-in.</li>
  </ul>";
  wp_die( esc_html( $message ) );
}
?>
