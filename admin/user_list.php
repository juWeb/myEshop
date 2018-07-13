<?php

require_once("inc/header.php");

// DELETE USER
if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $user_query = $pdo->prepare("SELECT * FROM user WHERE id_user=:id_user");
    $user_query->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);
    
    if($user_query->execute()) {
        if($user_query->rowCount() == 1) {
            $user = $user_query->fetch();

            $user_deleted = $pdo->exec("DELETE FROM user WHERE id_user=$user[id_user]");
            if($user_deleted)
                header("location:".URL."admin/user_list.php?m=success");
        } else
            header("location:".URL."admin/user_list.php?m=fail");
    } else
        header("location:".URL."admin/user_list.php?m=fail");
}

// SELECT USERS
$user_list_query = $pdo->query("SELECT * FROM user");
$users = $user_list_query->fetchAll();

$content .= "<table class='table table-striped table-dark'><thead><tr>";

for($i=0;$i<$user_list_query->columnCount();$i++) {
    $column_name = $user_list_query->getColumnMeta($i)['name'];
    if($column_name != "pwd")
        $content .= "<th scope='col'>".str_replace('_', ' ', $column_name)."</th>";
}    

$content .= "<th scope='col'>actions</th></tr></thead><tbody>";

for($i=0;$i<count($users);$i++) {
    $id_user = $users[$i]['id_user'];

    $content .= "<tr>";
    foreach ($users[$i] as $key => $value) {
        if($key != "pwd")
            $content .= "<td>$value</td>";
    }
        
    $content .= "<td><a style='margin-right: 15px;' href='" . URL . "admin/user_form.php?id=" . $id_user . "'><i class='fas fa-pencil-alt text-warning'></i></a>";
    if($_SESSION['user']['id_user'] != $id_user)
        $content .= "<a data-toggle='modal' data-target='#deleteModal'><i class='fas fa-trash-alt text-danger'></i></a></td>"; 

    // DELETE MODAL
    $content .= "
    <div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
            <h5 class='modal-title text-danger' id='deleteModalLabel'>DELETE A USER</h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
            </div>
            <div class='modal-body'>
            Confirm to delete <strong>".$users[$i]['pseudo']."</strong> ?
            </div>
            <div class='modal-footer'>
                <a href='".URL."admin/user_list.php?id=$id_user'><button type='button' class='btn btn-success'>Validate</button></a>
                <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancel</button>
            </div>
        </div>
        </div>
    </div>";

    $content .= "</tr>";
}

if(isset($_GET['m']) && !empty($_GET['m'])) {
    switch($_GET['m']){
        case 'success':
            $msg_success .= "<div class='alert alert-success'>The user is deleted.</div>";
        break;
        case 'fail':
            $msg_error .= "<div class='alert alert-danger'>Error during the operation. If you still have this mistake after few tries, please call the dev' team.</div>";
        break;
        default:
            $msg_success .= "<div class='alert alert-secondary'>Don't understand, please try again.</div>";
        break;
    }
}

$content .= "</tbody></table>";
?>

<h1>List of users</h1>

<?= $msg_success ?>
<?= $msg_error ?>

<?= $content ?>

<?php require_once("inc/footer.php"); ?>