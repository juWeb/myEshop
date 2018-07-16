<?php
    require_once("inc/header.php");

    $page = "My Profile";

    if(!userConnect()){

        header('location:login.php');
        exit();
    }

    //Delete your account

    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        $user_query = $pdo->prepare("SELECT * FROM user WHERE id_user=:id_user");
        $user_query->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);
        
        if($user_query->execute()) {
            if($user_query->rowCount() == 1) {
                $user = $user_query->fetch();
    
                $user_deleted = $pdo->exec("DELETE FROM user WHERE id_user=$user[id_user]");
                if($user_deleted){
                    
                    header("location:".URL."index.php?m=success");
                    unset($_SESSION['user']);
                }
            }else{
                header("location:".URL."profile.php?m=fail");
            }
        }else{
            header("location:".URL."profilex.php?m=fail");
        }
    }

    
    //Select users
    $content .= "<div><a style='margin-right: 15px;' href='" . URL . "profile.php?id_edit=" . $_SESSION['user']['id_user']. "'><i class='fas fa-pencil-alt text-warning'></i></a></div>";
    $content .= "<div><a data-toggle='modal' data-target='#deleteModal' style='margin-right: 15px;' href='" . URL . "profile.php?id=" . $_SESSION['user']['id_user']. "'><i class='fas fa-trash-alt text-danger'></i></a></div>";

     // DELETE MODAL
     $content .= "
     <div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
         <div class='modal-dialog' role='document'>
         <div class='modal-content'>
             <div class='modal-header'>
             <h5 class='modal-title text-danger' id='deleteModalLabel'>Are you sure you want to delete your account?</h5>
             <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                 <span aria-hidden='true'>&times;</span>
             </button>
             </div>
             <div class='modal-body'>
             Confirm to delete your acount <strong>".ucfirst($_SESSION['user']['firstname'])."</strong> ?
             </div>
             <div class='modal-footer'>
                 <a href='".URL."profile.php?id=".$_SESSION['user']['id_user']."'><button type='button' class='btn btn-success'>Validate</button></a>
                 <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancel</button>
             </div>
         </div>
         </div>
     </div>";

    //Display status message
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

    // debug($_SESSION['user']['id_user']);

?>
    
    
    <h1><?= $page ?></h1>
    <?= $msg_success ?>
    <?= $msg_error ?>
        
    <p>Please find your informations below: </p>
    
    <?= $content ?>

    <div class="span4 well">
    <div class="row">
        <div class="span1">
            <a href="http://critterapp.pagodabox.com/others/admin" class="thumbnail">
                <img src="http://critterapp.pagodabox.com/img/user.jpg" alt=""></a>
        </div>
        <div class="span3">
            <ul>
                <li>Firstame: <?= $_SESSION['user']['firstname'] ?></li>
                <li>Lastname: <?= $_SESSION['user']['lastname'] ?></li>
                <li>Email: <?= $_SESSION['user']['email'] ?></li>
            </ul>
            <span class=" badge badge-warning">8 messages</span>
            <span class=" badge badge-info">15 followers</span>
        </div>
    </div>
</div>     
    
<?php
    require_once("inc/footer.php");
?>