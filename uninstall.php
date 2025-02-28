<?php
/*
 * Tasks to perform when uninstalling plugin
 */
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  $message = "<h2 style='color:red'>Erro no Plugin</h2>
  Plugin <span style='color:blue;font-family:monospace'>add-leadbooster</span> diz:</p>
  <p>Não chame este arquivo diretamente. Se você deseja desinstalar, use a ferramenta de administração para fazer isso.</p>";
  wp_die( $message );
} else {
  if ( ! defined( 'ABSPATH' ) ) exit;

  $the_option = "acth_options";
  /*
    If running in multisite environment,
    we'll need to remove the option by brute force
  */
  if( is_multisite() ) {
      global $wpdb;
      $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
      if( $blogs ) {
          foreach( $blogs as $blog ) {
              switch_to_blog( $blog['blog_id'] );
              delete_option( $the_option );
          }
          restore_current_blog();
       }
  } else {
    delete_option( $the_option );
  }
}
?>
