<?php

//            Create group
$groups_created = array();
if (isset($_POST['create_group'])) {
    if (!empty($_POST['group_name'])) {
        $groups_created = get_post_meta(1, 'groups_created', true);
        if (!empty($groups_created)) {
            if (!in_array($_POST['group_name'], $groups_created)) {
                $groups_created[] = $_POST['group_name'];
                update_post_meta(1, 'groups_created', $groups_created);
                update_post_meta(1, $_POST['group_name'], array());
            }
        } else {
            update_post_meta(1, 'groups_created', array($_POST['group_name']));
            update_post_meta(1, $_POST['group_name'], array());
        }
    }
}

//            Delete group
if (isset($_POST['delete_group'])) {
    if ($_POST['group_name_delete']) {
        $delete_group = get_post_meta(1, $_POST['group_name_delete'], true);
        foreach ($delete_group as $key => $user_id) {
            update_user_meta($user_id, 'current_selected_group', '');
        }
        $groups_created = get_post_meta(1, 'groups_created', true);
        $key = array_search($_POST['group_name_delete'], $groups_created);
        unset($groups_created[$key]);
        update_post_meta(1, 'groups_created', $groups_created);
        delete_post_meta(1, $_POST['group_name_delete']);
    }
}

//            Rename group
if (isset($_POST['rename_group'])) {
    if ($_POST['group_name_rename']) {
        $rename_group = get_post_meta(1, $_POST['group_name_rename'], true);
        $new_name_group = update_post_meta(1, $_POST['rename'], $rename_group);
        foreach ($rename_group as $key => $user_id) {
            update_user_meta($user_id, 'current_selected_group', $_POST['rename']);
        }

        $groups_created = get_post_meta(1, 'groups_created', true);
        $key = array_search($_POST['group_name_rename'], $groups_created);
        unset($groups_created[$key]);
        $groups_created[] = $_POST['rename'];
        update_post_meta(1, 'groups_created', $groups_created);
        delete_post_meta(1, $_POST['group_name_rename']);
    }
}
?>

<form action="" method="POST" class="">
    <div class="row">
        <div class="col-sm-12 col-lg-12 form-group">
            <label for="group_name">Create Group</label>
            <input id="group_name" type="text" name="group_name" value="">
            <input class="btn btn-info" type="submit" name="create_group" value="Create">
        </div>
    </div>
</form>

<form action="" method="POST" style="display:block;">
    <div class="row">
        <div class="col-sm-3 col-lg-3 form-group">
            <label for="group_name_delete">Delete Group</label>
            <select id="group_name_delete"
                    name="group_name_delete"
                    class="form-control">
                <option value="">Please select...</option>
                <?php
                $groups_created = get_post_meta(1, 'groups_created', true);
                // Array of WP_User objects.
                foreach ($groups_created as $key => $group_name) {
                    if ($_POST['group_name'] == $group_name) {
                        echo '<option value="' . $group_name . '" selected>' . $group_name . '</option>';
                    } else {
                        echo '<option value="' . $group_name . '">' . $group_name . '</option>';
                    }
                } ?>
            </select>
        </div>
        <div class="col-sm-3 col-lg-3 form-group">
            <br>
            <input type="submit" class="btn btn-danger" name="delete_group" value="Delete Group">
        </div>
    </div>
</form>

<form action="" method="POST" style="display:block;">
    <div class="row">
        <div class="col-sm-3 col-lg-3 form-group">
            <label for="group_name_rename">Rename Group</label>
            <select id="group_name_rename"
                    name="group_name_rename"
                    class="form-control">
                <option value="">Please select...</option>
                <?php
                $groups_created = get_post_meta(1, 'groups_created', true);
                // Array of WP_User objects.
                foreach ($groups_created as $key => $group_name) {
                    if ($_POST['group_name'] == $group_name) {
                        echo '<option value="' . $group_name . '" selected>' . $group_name . '</option>';
                    } else {
                        echo '<option value="' . $group_name . '">' . $group_name . '</option>';
                    }
                } ?>
            </select>
        </div>
        <div class="col-sm-3 col-lg-3 form-group">
            <label for="rename">New Name Group</label>
            <input id="rename" type="text" value="" name="rename">
        </div>
        <div class="col-sm-3 col-lg-3 form-group">
            <br>
            <input type="submit" class="btn btn-info" name="rename_group" value="Rename Group">
        </div>
    </div>
</form>

<!--            <select name="" id="">-->
<?php
//                    $groups_created = get_post_meta(1, 'groups_created', true);
//                    foreach ($groups_created as $key => $group) {
//                        echo '<option value="' . $key . '">' . $group . '</option>';
//                    }
?>
<!--            </select>-->