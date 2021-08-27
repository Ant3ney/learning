<?php

/**
 * Plugin Name: Client Tracker
 */

define(plugins_url('/clientTracker/index.php'), __FILE__);

register_activation_hook(__FILE__, 'r_activate_plugin');
register_deactivation_hook( __FILE__, 'r_uninstall_plugin' );
add_action( 'init', 'init_plugin' );
add_action('admin_menu', 'make_menu');
add_action( 'admin_enqueue_scripts', 'loadAdminPage' );

Global $wpdb;

 function r_activate_plugin(){
    Global $wpdb;

    $createSQL      =   "
    CREATE TABLE `wp_devsite_client_tracker` (
        ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        CLIENT_NAME VARCHAR(30) NOT NULL,
        IMAGE VARCHAR(300) NOT NULL,
        DESCRIPTION VARCHAR(1000) NOT NULL,
        MESSAGE_LINK VARCHAR(300) NOT NULL,
        DOC_DIR_LINK VARCHAR(300) Not NULL,
        PRIMARY KEY (ID)
      )" . $wpdb->get_charset_collate() . ";";

      
      require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
      dbdelta($createSQL);
      
 }

function r_uninstall_plugin(){
    Global $wpdb;
    $wpdb->query('DROP TABLE `wp_devsite_client_tracker`;');
}

 function loadAdminPage(){
    /* styles */
    wp_register_style('clientTracker.css', plugins_url('/clientTracker/assets/clientTracker.css')); 
    wp_enqueue_style('clientTracker.css', plugins_url('/clientTracker/assets/clientTracker.css'));
    wp_enqueue_style('admin.css', plugins_url('/clientTracker/assets/admin.css'));
    wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', false );

    /* Scripts */
    wp_enqueue_script( '0d4692f1c3', 'https://kit.fontawesome.com/0d4692f1c3.js', false );
    wp_enqueue_script("jquery");
    wp_enqueue_script( 'popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', false );
    wp_enqueue_script( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', false );
 }

function client_page(){
    echo '<div class="admin-container" >';
    Global $wpdb;
    $createSQL =  "Select";

    $clients = $wpdb->get_results("SELECT * FROM wp_devsite_client_tracker;");
    
    function createClient($clients) {
        $createSQL      =   "INSERT INTO wp_devsite_client_tracker (ID, CLIENT_NAME) VALUES(UUID() , ANTHONY)";
        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
        dbdelta($createSQL);

        $wpdb->insert('wp_devsite_client_tracker', [
            'ID' => null,
            'CLIENT_NAME' => $_POST['clientName'],
            'IMAGE' => $_POST['image'],
            'DESCRIPTION' => $_POST['DESCRIPTION'],
            'MESSAGE_LINK' => $_POST['MESSAGE_LINK'],
            'DOC_DIR_LINK' => $_POST['DOC_DIR_LINK']
        ],['%d', '%s', '%s', '%s', '%s', '%s']);
        ?>
            <script>document.location.reload(true);</script>
        <?php
    }
    function updateClient(){
        Global $wpdb;
        $wpdb->update('wp_devsite_client_tracker', [
            'ID' => $_POST['ID'],
            'CLIENT_NAME' => $_POST['clientName'],
            'IMAGE' => $_POST['image'],
            'DESCRIPTION' => $_POST['DESCRIPTION'],
            'MESSAGE_LINK' => $_POST['MESSAGE_LINK'],
            'DOC_DIR_LINK' => $_POST['DOC_DIR_LINK'] 
        ],['ID' => $_POST['ID']],['%d', '%s', '%s', '%s', '%s', '%s']);
        ?>
            <script>document.location.reload(true);</script>
        <?php
    }
    function deleteAllUsers() {
        Global $wpdb;
        $wpdb->query('DELETE FROM wp_devsite_client_tracker;');
        ?>
            <script>document.location.reload(true);</script>
        <?php
    }

    if(array_key_exists('createClient', $_POST)) {
        createClient($clients);
    }
    if(array_key_exists('updateClient', $_POST)){
        updateClient();
    }
    else if(array_key_exists('DeleteAllUsers', $_POST)) {
        deleteAllUsers();
    }

    
    $showCreation = false;
    $showEdit = false;
    $avalibleImages;
    global $wp;
    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    if(array_key_exists('beginBuildingClient', $_POST)) {
        $showCreation = true;
        $avalibleImages = $wpdb->get_results("SELECT `guid` FROM wp_devsite_posts WHERE post_mime_type = 'image/jpeg';");
    }
    if(array_key_exists('edit_client', $_POST)){
        $avalibleImages = $wpdb->get_results("SELECT `guid` FROM wp_devsite_posts WHERE post_mime_type = 'image/jpeg';");
        $ID = $_POST['client_id'];
        $edit_client = $wpdb->get_results("SELECT * FROM wp_devsite_client_tracker WHERE ID = '" . $ID ."';")[0];
        $showEdit = true;
    }
    if(array_key_exists('delete_client', $_POST)){
        $wpdb->query('DELETE FROM wp_devsite_client_tracker WHERE ID = ' . $_POST['client_id'] .';');
        ?>
            <script>document.location.reload(true);</script>
        <?php
    }

    ?>
        <form name='client_createor' method="post">
            <input type="submit" name="beginBuildingClient" class="button" value="Begin Building Client" />
            <?php
                if($showCreation){
                    ?>
                        <script>
                            function imagePressed(imageSrc){
                                document.client_createor.image.value = imageSrc;
                            }
                        </script>
                        <p class="mt-3">Enter clients name</p>
                        <input type="text" name="clientName" class="button" />
                        <p class="mt-3">Select an avatar for client</p>
                        <input type='hidden' name='image' value='null' />
                        <?php

                            for($i = 0; $i < count($avalibleImages); $i++){
                                $currentImageSrc = $avalibleImages[$i];    
                                ?>
                                    <img onClick="imagePressed('<?php echo $currentImageSrc->guid ?>')" src='<?php echo $currentImageSrc->guid ?>' style='max-width: 100%; max-height: 5rem;' />
                                <?php
                            }

                        ?>
                        <br>
                        <p class="mt-3">Enter custumer description</p>
                        <textarea name="DESCRIPTION" class="button"></textarea>
                        <br>
                        <p class="mt-3">Enter message link</p>
                        <input type="text" name="MESSAGE_LINK" class="button" />
                        <p>link to document directory</p>
                        <input type="text" name="DOC_DIR_LINK" class="button" />
                        <br>
                        <input type="submit" name="createClient" class="button" value="Create Client" />
                    <?php
                }
            ?>
            <input type="submit" name="DeleteAllUsers" class="button" value="Delete all users" />  
        </form>
    <?php

    function displayClientInfo($clients){
        ?>
        <div class='box-container'>
            <?php
                for($i = 0; $i < count($clients); $i++){
                    $client = $clients[$i];
                    $custum_margin = ((($i + 2) % 3) == 0) ? 'custum01-margin-sides' : '';
                    ?>
                        
                            <div class='neu-concave-sbox p-3 client-box mt-3 <?php echo $custum_margin ?>'>
                                <h4><?php echo $client->CLIENT_NAME; ?></h4>
                                <img class='images-display' src='<?php echo $client->IMAGE; ?>' />
                                <p class="mt-1 mb-0"><?php echo $client->DESCRIPTION; ?></p>
                                <a class="mt-1" href='<?php echo $client->MESSAGE_LINK ?>'>Converstaion link</a>
                                <a class="mt-1" href='<?php echo $client->DOC_DIR_LINK ?>'>Documnets link</a>
                                <form method="post">
                                    <input type='hidden' name='client_id' value='<?php echo $client->ID; ?>' />
                                    <input  class='mt-1 button' type='submit' name='edit_client' value='Edit client'/>
                                    <input class='mt-1 button' type='submit' name='delete_client' value='Delete client'/>
                                </form>
                            </div>
                        
                    <?php
                }
            ?>   
        </div> 
        <?php
    }

    displayClientInfo($clients);

    if($showEdit){
        ?>
            <script>
                document.client_editor.image.value = "<?php if(!empty($edit_client->IMAGE)){echo $edit_client->IMAGE;}else{echo 'unset';}?>";
                function imagePressed(imageSrc){
                    document.client_editor.image.value = imageSrc;
                    console.log(document.client_editor.image.value);
                }
            </script>
            <form name='client_editor' class='neu-convex-sbox p-3 mt-5 edit-box-container' method='post'>
                <h1>Editing <?php echo $edit_client->CLIENT_NAME; ?></h1>
                <p class="mt-3">Enter clients name</p>
                <input type="text" name="clientName" class="button" value="<?php echo $edit_client->CLIENT_NAME; ?>"/>
                <p class="mt-3">Select an avatar for client</p>
                <input type='hidden' name='image' value='<?php $edit_client->IMAGE; ?>' />
                <input type='hidden' name='ID' value='<?php echo $edit_client->ID; ?>' />
                <?php
                    for($i = 0; $i < count($avalibleImages); $i++){
                        $currentImageSrc = $avalibleImages[$i];
                        ?>
                            <img onClick="imagePressed('<?php echo $currentImageSrc->guid ?>')" src='<?php echo $currentImageSrc->guid ?>' style='max-width: 100%; max-height: 5rem;' />
                        <?php
                    }
                ?>
                <br>
                <p class="mt-3">Enter custumer description</p>
                <textarea name="DESCRIPTION" class="button"><?php echo $edit_client->DESCRIPTION; ?></textarea>
                <br>
                <p class="mt-3">Enter message link</p>
                <input type="text" name="MESSAGE_LINK" class="button" value="<?php echo $edit_client->MESSAGE_LINK; ?>"/>
                <p class="mt-3">link to document directory</p>
                <input type="text" name="DOC_DIR_LINK" class="button" value="<?php echo $edit_client->DOC_DIR_LINK; ?>"/>
                <br>
                <input type="submit" name="updateClient" class="button mt-3" value="Update Client" />
            </form>
        <?php
    }

    echo '</div>';
}

function make_menu(){
    add_menu_page( 'Clients 1', 'Client Tracker', 'manage_options', 'sin', 'client_page', '', 3, null );
}

function init_plugin(){
    wp_register_script('nice_bg', 'sin/build/index.js');

}

function add_custom_meta_box()
{
    add_post_meta("post", "Custom Meta Box", 'Hello');
}

function custom_meta_box_markup()
{
    
}

