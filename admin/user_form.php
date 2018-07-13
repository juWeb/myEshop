<?php

    require_once('inc/header.php');

    $page = "Update user credentials";

    //A.Get value initially inputted by the user
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){

        $req = "SELECT * FROM user WHERE id_user = :id_user";

        $result = $pdo->prepare($req);
        $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);
        $result->execute();

        if($result->rowCount() == 1){

            $update_user = $result->fetch();
        
        }
    }

    $id_user = (isset($update_user)) ? $update_user['id_user'] : '';
    $pseudo = (isset($update_user)) ? $update_user['pseudo'] : '';
    $firstname = (isset($update_user)) ? $update_user['firstname'] : '';
    $lastname = (isset($update_user)) ? $update_user['lastname'] : '';
    $email = (isset($update_user)) ? $update_user['email'] : '';
    $gender = (isset($update_user)) ? $update_user['gender'] : '';
    $city = (isset($update_user)) ? $update_user['city'] : '';
    $zip_code = (isset($update_user)) ? $update_user['zip_code'] : '';
    $address = (isset($update_user)) ? $update_user['address'] : '';
    $privilege = (isset($update_user)) ? $update_user['privilege'] : '';
    

    //B. Procedure if an update of user is performed by admin
    if($_POST){

        //B.1 Repeat conditions applied in signup
    
        // check pseudo
        if(!empty($_POST['pseudo'])){

            $pseudo_verif = preg_match('#^[a-zA-Z0-9-._]{3,20}$#', $_POST['pseudo']); // function preg_match() allows me to check what info will be be allowed in a result. It takes 2 arguments: REGEX (Regular Expressions) + the result to check. At the end, I will have a TRUE or FALSE condition
            
            if(!$pseudo_verif){

                $msg_error .= "<div class='alert alert-danger'>Your pseudo should countain letters (upper/lower), numbers, between 3 and 20 characters and only '.' and '_' are accepted. Please try again !</div>";
            }

        }else{

            $msg_error .= "<div class='alert alert-danger'>Please enter a valid pseudo.</div>";
        }


        // check email
        if (!empty($_POST['email'])){

            $email_verif = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // function filter_var() allows us to check a result (STR -> email, URL ...). It takes 2 arguments: the result to check + the method. It returns a BOOLEAN

            $forbidden_mails = [
                    'mailinator.com',
                    'yopmail.com',
                    'mail.com'
            ];

             $email_domain = explode('@', $_POST['email']); // function explode() allow me to explode a result into 2 parts regarding the element I've chosen

        // debug($email_domain);

            if(!$email_verif || in_array($email_domain[1], $forbidden_mails)){

                    $msg_error .= "<div class='alert alert-danger'>Please enter a valid email.</div>";
            
            }

        }else{

            $msg_error .= "<div class='alert alert-danger'>Please enter a valid email.</div>";
        
        }

        if(!isset($_POST['gender']) || ($_POST['gender'] != "m" && $_POST['gender'] != "f" && $_POST['gender'] != "o")){

            $msg_error .= "<div class='alert alert-danger'>Choose a valid gender.</div>";
        
        }

        //B.2 Update the database if no error

        if(empty($msg_error)){

            //B.2.1 Mysql request

            if(!empty($_POST['id_user'])){

            $result = $pdo->prepare("UPDATE user SET pseudo=:pseudo, firstname=:firstname, lastname=:lastname, email=:email, 
            gender=:gender, city=:city, zip_code=:zip_code, address=:address, privilege=:privilege WHERE id_user=:id_user");

            //Bind values
            
            $result->bindValue(':id_user', $_POST['id_user'], PDO::PARAM_INT);

            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR);
            $result->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR);
            $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $result->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
            $result->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
            $result->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
            $result->bindValue(':zip_code', $_POST['zc'], PDO::PARAM_STR);
            $result->bindValue(':privilege', $_POST['privilege'], PDO::PARAM_INT);
    
            
            }

            
            //debug($_POST);
        

            if($result->execute()){
                //B.2.1 Mysql request
                header('location:user_list.php');
            }
        }
    }


    

    
   




?>

 <h1><?= $page ?></h1>
        
        <form action="" method="post">
            <?= $msg_error ?> <!-- tio check -->
            <input type='hidden' name="id_user" value="<?= $id_user ?>">
            <div class="form-group">
            <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" name="pseudo" placeholder="Pseudo" value="<?= $pseudo ?>" required>
            </div>
            <div class="form-group">
                <label for="firstname">Firstname</label>
                <input type="text" class="form-control" name="firstname" placeholder="Firstname" value="<?= $firstname ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Lastname</label>
                <input type="text" class="form-control" name="lastname" placeholder="Lastname" value="<?= $lastname ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email" value="<?= $email ?>">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" class="form-control">
                    <option value="m" <?php if($gender == 'm'){echo 'selected';} ?>>Men</option>
                    <option value="f" <?php if($gender == 'f'){echo 'selected';} ?>>Women</option>
                    <option value="o" <?php if($gender == 'o'){echo 'selected';} ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
            <label for="Address">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" value="<?= $address ?>">
            </div>
            <div class="form-group">
            <label for="zc">Zip code</label>
                <input type="text" class="form-control" name="zc" placeholder="Zip code" value="<?= $zip_code ?>">
            </div>
            <div class="form-group">
            <label for="city">City</label>
                <input type="text" class="form-control" name="city" placeholder="City" value="<?= $city ?>">
            </div>
            <div class="form-group">
                <label for="privilege">Privilege</label>
                <select name="privilege" class="form-control">
                        <option value="0" <?php if($privilege == '0'){echo 'selected';} ?>>Regular user</option>
                        <option value="1" <?php if($privilege == '1'){echo 'selected';} ?>>Administrator</option>
                </select>
            </div>
            <input type="submit" value="Update credentials" class="btn btn-success btn-lg btn-block">
        </form>


<?php
    require_once('inc/footer.php');
?>