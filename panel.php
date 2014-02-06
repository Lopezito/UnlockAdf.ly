<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript">
function rmregister(ID){
var formData = {id:ID};
$.ajax({
    url : "<?php echo admin_url('admin.php?page=unlockadfly&rm=true'); ?>",
    type: "POST",
    data : formData,
    success: function(data, textStatus, jqXHR){
	alert('El registro eliminó correctamente. Se actualizará la página.');
	window.location.reload();
    }
});
}
</script>
<style type="text/css">
.tablenav .tablenav-pages{float:left}
a.prev-page{font-size:12px!important;padding:10px!important}
.styleform{padding:10px;background:white;border-radius:3px;box-shadow:0px 0px 8px rgba(34, 34, 34, 0.08);text-shadow:1px 1px #fff}
.styleform input[type="text"]{margin:5px 0px}
p.submit{padding:0}
.exitobloq{padding:5px;margin-bottom:5px;background:rgb(133, 201, 80);border-radius:3px;display:block;color:white;text-shadow:1px 1px rgba(34, 34, 34, 0.27);width:100%;max-width:200px}
.errorbloq{padding:5px;margin-bottom:5px;background:rgb(201, 80, 80);border-radius:3px;display:block;color:white;text-shadow:1px 1px rgba(34, 34, 34, 0.27);width:100%;max-width:284px}
</style>
<div class="wrap">
<h2 id="title">Unlock Adf.ly - Ajustes</h2>
<?php
global $wpdb;
$table_name = $wpdb->prefix . "unlockadfly";
if(isset($_GET['edit']) && $_GET['edit'] != NULL){
if($_POST['y'] == 'Y' && $_POST['nombre'] != NULL && $_POST['slug'] != NULL) {
$wpdb->show_errors();
$nombre = addslashes(mysql_real_escape_string(trim($_POST['nombre'])));
$slug = addslashes(mysql_real_escape_string(trim($_POST['slug'])));
if($wpdb->update($table_name,array('name' => $nombre,'slug' => $slug),	array( 'slug' => $slug ), array('%s','%s'), array('%s')) == TRUE){
$msgcreatetrue = '<div class="exitobloq">Se ha editado con &eacute;xito el bloque</div>';}else{
$msgcreatefalse = '<div class="errorbloq">No se pudo guardar los cambios</div>';}
$h4background = $_POST['h4background'];
update_option('h4background_'.$slug, $h4background);
$hidebloqbackground = $_POST['hidebloqbackground'];
update_option('hidebloqbackground_'.$slug, $hidebloqbackground);
$titcolor = $_POST['titcolor'];
update_option('titcolor_'.$slug, $titcolor);
$txtshadowcolor = $_POST['txtshadowcolor'];
update_option('txtshadowcolor_'.$slug, $txtshadowcolor);
$linkcolor = $_POST['linkcolor'];
update_option('linkcolor_'.$slug, $linkcolor);
}
wp_enqueue_script('jscolor.js', plugins_url('unlockadfly/') . 'js/jscolor.js');
$id = addslashes(mysql_real_escape_string(trim($_GET['edit'])));
$getdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} WHERE ID='{$id}'"));
if($getdata){
foreach($getdata as $data){
$slug = $data->slug;
$h4background = get_option('h4background_'.$slug);
$hidebloqbackground = get_option('hidebloqbackground_'.$slug);
$titcolor = get_option('titcolor_'.$slug);
$txtshadowcolor = get_option('txtshadowcolor_'.$slug);
$linkcolor = get_option('linkcolor_'.$slug);
?>
<div class="tablenav bottom">
<div class="tablenav-pages">
<a class="prev-page" title="Ir a la página anterior" href="<?php echo admin_url('admin.php?page=unlockadfly'); ?>">‹ Volver</a>
</div>
</div>
<h3>Editar bloque</h3>
<form name="createunlockadfly" class="styleform" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<?php if($msgcreatetrue != NULL){echo $msgcreatetrue;}else if($msgcreatefalse != NULL){echo $msgcreatefalse;} ?>
<input type="hidden" name="y" value="Y" />
<span class="description">Nombre: </span><br/>
<input type="text" placeholder="Ej: Mega" value="<?php echo $data->name; ?>" name="nombre" /><br/>
<span class="description">Slug: </span><br/>
<input type="text" placeholder="Ej: dwmega, mg, mega" value="<?php echo $data->slug; ?>" name="slug" /><br/>
<span class="description">Color de fondo de la barra: </span><br/>
<input class="color" name="h4background" value="<?php echo $h4background; ?>" /><br/>
<span class="description">Color de fondo del contenedor de enlaces: </span><br/>
<input class="color" name="hidebloqbackground" value="<?php echo $hidebloqbackground; ?>" /><br/>
<span class="description">Color del t&iacute;tulo del bloque: </span><br/>
<input class="color" name="titcolor" value="<?php echo $titcolor; ?>" /><br/>
<span class="description">Color de la sobra del t&iacute;tulo: </span><br/>
<input class="color" name="txtshadowcolor" value="<?php echo $txtshadowcolor; ?>" /><br/>
<span class="description">Color de los enlaces: </span><br/>
<input class="color" name="linkcolor" value="<?php echo $linkcolor; ?>" />
<?php submit_button( 'Guardar cambios', 'primary' ); ?>
</form>
<?php
}
}
}else{
if($_POST['y2'] == 'Y2' && $_POST['idadflytype'] != NULL) {
$idadflytype = $_POST['idadflytype'];
update_option('idadflytype', $idadflytype);
$idadminadfly = $_POST['idadminadfly'];
update_option('idadminadfly', $idadminadfly);
$timeadfly = $_POST['timeadfly'];
update_option('timeadfly', $timeadfly);
}
$idadflytype = get_option('idadflytype');
$idadminadfly = get_option('idadminadfly');
$timeadfly = get_option('timeadfly');
?>
<h3>Configuraci&oacute;n</h3>
<form name="createunlockadfly" class="styleform" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<?php if($msgcreatetrue != NULL){echo $msgcreatetrue;}else if($msgcreatefalse != NULL){echo $msgcreatefalse;} ?>
<input type="hidden" name="y2" value="Y2" />
<span class="description">Mostrar: </span><br/>
<select name="idadflytype">
<option value="onlyauthor"<?php if($idadflytype == 'onlyauthor'){echo' selected';} ?>>Siempre ID Adf.ly del autor del post</option>
<option value="interauad"<?php if($idadflytype == 'interauad'){echo' selected';} ?>>Intervalo entre ID Adf.ly del autor del post e ID Adf.ly del admin</option>
<option value="onlyadmin"<?php if($idadflytype == 'onlyadmin'){echo' selected';} ?>>Siempre ID Adf.ly del admin</option>
</select><br/>
<span class="description">ID Adf.ly: </span><br/>
<input type="text" placeholder="ID Adf.ly de tu cuenta" value="<?php echo $idadminadfly; ?>" name="idadminadfly" /><br/>
<span class="description">Tiempo de duración de PopUp(en segundos): </span><br/>
<input type="text" placeholder="10" value="<?php echo $timeadfly; ?>" name="timeadfly" />
<?php submit_button( 'Guardar cambios', 'primary' ); ?>
</form>
<?php
if($_POST['y'] == 'Y' && $_POST['nombre'] != NULL && $_POST['slug'] != NULL) {
$wpdb->show_errors();
$nombre = addslashes(mysql_real_escape_string(trim($_POST['nombre'])));
$slug = addslashes(mysql_real_escape_string(trim($_POST['slug'])));
if($wpdb->insert($table_name,array('name' => $nombre,'slug' => $slug),array('%s','%s')) == TRUE){
$msgcreatetrue = '<div class="exitobloq">Se ha creado con &eacute;xito el bloque</div>';}else{
$msgcreatefalse = '<div class="errorbloq">Hubo un error al crear el bloque</div>';}
}
if(isset($_GET['rm']) && $_POST['id'] != NULL){
$rmid = addslashes(mysql_real_escape_string(trim($_POST['id'])));
$wpdb->query($wpdb->prepare("DELETE FROM {$table_name} WHERE id='{$rmid}'"));
}
$getdata = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} ORDER BY id DESC"));
?>
<h3>Crea un nuevo bloque</h3>
<form name="createunlockadfly" class="styleform" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<?php if($msgcreatetrue != NULL){echo $msgcreatetrue;}else if($msgcreatefalse != NULL){echo $msgcreatefalse;} ?>
<input type="hidden" name="y" value="Y" />
<span class="description">Introduce el nombre: </span><br/>
<input type="text" placeholder="Ej: Mega" name="nombre" /><br/>
<span class="description">Introduce un slug(sin espacios): </span><br/>
<input type="text" placeholder="Ej: dwmega, mg, mega" name="slug" />
<?php submit_button( 'Crear', 'primary' ); ?>
</form>
<h3>Listado de bloques</h3>
<?php
$ret = '';
if($getdata){
?>
<table class="wp-list-table widefat fixed posts" cellspacing="0">
<thead>
<tr><th>Nombre</th><th>Slug</th><th>Acciones</th></tr>
</thead>
<tbody id="the-list">
<?php
$i = 0;
foreach($getdata as $data){
?>
<tr id="bloque-<?php echo $data->ID; ?>" class="<?php if($i == 0){ echo 'alternate'; $i++;}else if($i != 0){$i = 0;} ?>" valign="top"><th><?php echo $data->name; ?></th><th><?php echo $data->slug; ?></th><th><a href="<?php echo admin_url('admin.php?page=unlockadfly&edit='.$data->id); ?>">Editar</a> | <span class="trash"><a href="#" onclick="rmregister(<?php echo $data->id; ?>)">Borrar</a></span></th></tr>
<?php } ?>
</body>
</table>
<?php
}else{
echo '<h1 style="color:red">&iexcl;No hay nada creado!</h1>';
}
}
?>
</div>