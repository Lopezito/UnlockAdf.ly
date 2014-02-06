<?php
/*
Plugin Name: Unlock Adf.ly
Plugin URI: http://lopezito.com/blog/unlockadfly/
Description: Agrega un bloqueador u ocultador de enlaces que obliga al usuario a dar click para ver el contenido, a su vez el usuario verá un enlace Adf.ly que permitirá generar ganancias con ese acortador para el autor del post o en su defecto, para el administrador.
Version: 1.0
Author: Lopezito
Author URI: http://lopezito.com
*/
define(DIRPLUGIN_URL, plugins_url('unlockadfly/'));

register_activation_hook( __FILE__, 'unlockadfly_install' );
function unlockadfly_install() {
global $wpdb;
$table_name = $wpdb->prefix . "unlockadfly"; 
if ($wpdb->get_var('SHOW TABLES LIKE '.$table_name) != $table_name) {
$sql = "CREATE TABLE $table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  name text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  slug text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}
}

add_action( 'show_user_profile', 'addfields' );
add_action( 'edit_user_profile', 'addfields' );
function addfields($user) {
$idadflytype = get_option('idadflytype');
if($idadflytype != 'onlyadmin'){
?>
<h3><?php _e("Vincular cuenta Adf.ly", "blank"); ?></h3>
<table class="form-table">
   <tr>
      <th><label for="idadfly"><?php _e("ID Cuenta Adf.ly"); ?></label></th>
      <td>
         <input type="text" name="idadfly" id="idadfly" value="<?php echo esc_attr(get_the_author_meta('idadfly', $user->ID)); ?>" class="regular-text" /><br />
         <span class="description"><?php _e("Inserta el id de tu cuenta Adf.ly. Haz <a href='https://adf.ly/account/referrals'>click aquí</a> para obtener el tuyo."); ?></span>
      </td>
   </tr>
</table>
<?php
}
}
add_action( 'personal_options_update', 'savechangesonfields' );
add_action( 'edit_user_profile_update', 'savechangesonfields' );
function savechangesonfields($userid){
if(!current_user_can( 'edit_user', $userid)){ return false; }
update_usermeta( $userid, 'idadfly', $_POST['idadfly'] );
}
function panel_ajustes_unlockadfly() { include('panel.php'); }
add_action('admin_menu', 'panel_admin_menu_unlockadfly');
function panel_admin_menu_unlockadfly() {
add_menu_page('Unlock Adf.ly', 'Unlock Adf.ly', 'administrator', 'unlockadfly', 'panel_ajustes_unlockadfly', plugins_url('unlockadfly/imgs/icon.png'));
}
function bbcode_unlockadfly($atts,$content = null){
extract( shortcode_atts( array('slug' => ''), $atts ) );
$ret = '';
$content = str_replace('<p>', '', $content);
$content = str_replace('</p>', '', $content);
$content = explode(',', $content);
if($slug == NULL){
foreach($content as $cont){
if($cont != NULL){
$contout .= '<a href="'.$cont.'" target="_blank">'.$cont.'</a><br/>';
}
}
$content = base64_encode($contout);
$ret = '<div class="downbloq"><h4 id="downbloq">Links protegidos<span>'.get_option('timeadfly').'</span></h4><div class="hidebloq" id="hidebloq">'.$content.'</div></div>';
}else{
global $wpdb;
$table_name = $wpdb->prefix . "unlockadfly";
$slug = addslashes(mysql_real_escape_string(trim($slug)));
$getdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} WHERE slug='{$slug}'"));
if($getdata){
foreach($getdata as $data){
$h4background = get_option('h4background_'.$slug);
$hidebloqbackground = get_option('hidebloqbackground_'.$slug);
$titcolor = get_option('titcolor_'.$slug);
$txtshadowcolor = get_option('txtshadowcolor_'.$slug);
$linkcolor = get_option('linkcolor_'.$slug);
foreach($content as $cont){
if($cont != NULL){
$contout .= '<a href="'.$cont.'" target="_blank" style="color:#'.$linkcolor.'">'.$cont.'</a><br/>';
}
}
$content = base64_encode($contout);
$ret = '<div class="downbloq" id="'.$slug.'"><h4 id="downbloq" style="background:#'.$h4background.';color:#'.$titcolor.';text-shadow: 1px 1px #'.$txtshadowcolor.'">'.$data->name.'<span>'.get_option('timeadfly').'</span></h4><div class="hidebloq" id="hidebloq" style="background:#'.$hidebloqbackground.'">'.$content.'</div></div>';
}
} else { echo '<span style="color:red">ERROR</span>'; }
}
return $ret;
}
add_shortcode('ua', 'bbcode_unlockadfly');
function styleua(){
if(is_singular()){
?>
<style type="text/css">
.downbloq h4{cursor:pointer;width:100%;padding:10px 0px;text-align:center;background:rgb(241, 241, 241);text-shadow:1px 1px #fff;position:relative}
.downbloq h4 span{display:none;background:#444;padding:3px;border-radius:3px;position:absolute;right:3px;top:7px;color:#fff;text-shadow:none}
.downbloq .hidebloq{display:none;text-align:center;background:rgb(207, 207, 207);padding:10px 0px}
</style>
<?php
}
}
add_action('wp_head','styleua');
function jsua(){
if(is_singular()){
global $post;
$posts = get_posts('orderby=rand&numberposts=1');
foreach($posts as $post) {
$link = get_permalink($post->ID);
}
$idauthor = $post->post_author;
?>
<script type="text/javascript" src="<?php echo DIRPLUGIN_URL; ?>stringdec.js"></script>
<script type="text/javascript">
$('h4#downbloq').click(function(){
var objthis = $(this);
if(objthis.hasClass('notopen')){return false;}
objthis.find('span').show();
intervalcuenaatras = setInterval(function() {
var numm = parseInt(objthis.find('span').html());
var equal = numm-1;
objthis.find('span').html(equal);
},1000);
<?php
$idadflytype = get_option('idadflytype');
$timeadfly = get_option('timeadfly');
if($idadflytype == 'onlyadmin'){
?>
var url = 'http://adf.ly/<?php echo get_option('idadminadfly').'/'.$link; ?>';
<?php } else if($idadflytype == 'interauad') {
$adflyauthor = esc_attr(get_the_author_meta('idadfly', $idauthor));
$adflyadmin = get_option('idadminadfly');
$intervalue = array($adflyadmin, $adflyauthor);
$randkey = array_rand($intervalue);
?>
var url = 'http://adf.ly/<?php echo $intervalue[$randkey].'/'.$link; ?>';
<?php } else if($idadflytype == NULL || $idadflytype == 'onlyauthor') { ?>
var url = 'http://adf.ly/<?php echo esc_attr(get_the_author_meta('idadfly', $idauthor)).'/'.$link; ?>';
<?php } ?>
var win = window.open(url, 'AdflyWin', '_blank');
setTimeout(function() {
if (win == null || win.closed) {
alert('Cerraste la ventana :/');
objthis.find('span').hide();
clearInterval(intervalcuenaatras);
objthis.find('span').html('11');
}else{
objthis.parent().find('#hidebloq').slideToggle( "slow", function() {});
var string_encode = objthis.parent().find('#hidebloq').html();
objthis.parent().find('.hidebloq').html(Base64.decode(string_encode));
objthis.parent().find('#hidebloq').removeAttr('id');
objthis.find('span').hide();
objthis.removeAttr('id');
objthis.addClass('notopen');
clearInterval(intervalcuenaatras);
}
},<?php if($timeadfly == NULL){ echo '10'; }else{ echo $timeadfly; } ?>000);
});
</script>
<?php
}
}
add_action('wp_footer','jsua');
?>