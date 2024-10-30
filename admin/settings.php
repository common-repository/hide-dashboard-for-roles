<?php
global $wp_roles;
$all_roles = $wp_roles->roles;
$editable_roles = apply_filters('editable_roles', $all_roles);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_admin_referer( 'sagrev_save_roles', 'sagrev_save_role_nonce' );
    $posted_roles = [];

    foreach ($_POST['role_to_hide'] as $posted_role) {
        // if role exists in WP
        if (isset($editable_roles[$posted_role])) {
            array_push($posted_roles, $posted_role);
        }
    }

    update_option( 'sagrev_sol-roles_hidden_dashboard', $posted_roles);
    $disabled_roles = $posted_roles;

    ?>
    <div class="notice notice-success is-dismissible">
        <p>Configuration saved</p>
    </div>
    <?php
} else {
    $disabled_roles = get_option( 'sagrev_sol-roles_hidden_dashboard' );
    $disabled_roles = is_array($disabled_roles) == true ? $disabled_roles : [];
}

?>
<div class="wrap">
    <h2>Sagrev Solutions - Hide Dashboard for Roles</h2>
</div>
<form  method="post" >
    <h3>Hide Admin Dashboard for the following roles:</h3><br>
    <?php wp_nonce_field( 'sagrev_save_roles', 'sagrev_save_role_nonce' ); ?>
<?php
    $i = 0;
    foreach ($editable_roles as $role_name=>$role_obj) {
        $is_disabled = '';
        if(in_array($role_name, $disabled_roles)){
            $is_disabled = 'checked';
        }
        ?>
        <label>
        <input type='checkbox' name="role_to_hide[<?=$i;?>]" <?=$is_disabled?> value="<?=$role_name?>">
        <?=$role_name?></label>
        <br>
        <?php
        $i++;
    }
    submit_button('Save');
    ?>
</form>
