<?php
    require_once("inc/header.php");

    $page = "My Profile";

    if(!userConnect())
    {
        header('location:login.php');
        exit();
    }

    //debug($_FILES['picture']);

    if(isset($_POST) && !empty($_POST) && isset($_GET['update'])) {
        // check pseudo
        if(!empty($_POST['pseudo'])) {
            $pseudo_verif = preg_match('#^[a-zA-Z0-9-._]{3,20}$#', $_POST['pseudo']); // function preg_match() allows me to check what info will be be allowed in a result. It takes 2 arguments: REGEX (Regular Expressions) + the result to check. At the end, I will have a TRUE or FALSE condition

            if(!$pseudo_verif)
                $msg_error .= "<div class='alert alert-danger'>Your pseudo should countain letters (upper/lower), numbers, between 3 and 20 characters and only '.' and '_' are accepted. Please try again !</div>";
        } else
            $msg_error .= "<div class='alert alert-danger'>Please enter a valid pseudo.</div>";

        // check password
        if(!empty($_POST['pwd'])) {
            $pwd_verif = preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{6,15})$#', $_POST['pwd']); // it means we ask between 6 to 15 characters + 1 UPPER + 1 LOWER + 1 number + 1 symbol

            if(!$pwd_verif)
                $msg_error .= "<div class='alert alert-danger'>Your password should countain between 6 and 15 characters with at least 1 uppercase, 1 lowercase, 1 number and 1 symbol.</div>";
        }

        // check email
        if (!empty($_POST['email'])) {
           $email_verif = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

           $forbidden_mails = [
                'mailinator.com',
                'yopmail.com',
                'mail.com'
           ];

           $email_domain = explode('@', $_POST['email']);

           if(!$email_verif || in_array($email_domain[1], $forbidden_mails)) {
                $msg_error .= "<div class='alert alert-danger'>Please enter a valid email.</div>";
           }

        } else
            $msg_error .= "<div class='alert alert-danger'>Please enter a valid email.</div>";

        // check gender
        if(!isset($_POST['gender']) || ($_POST['gender'] != "m" && $_POST['gender'] != "f" && $_POST['gender'] != "o"))
            $msg_error .= "<div class='alert alert-danger'>Choose a valid gender.</div>";

        if(empty($msg_error)) {
            foreach ($_POST as $key => $value)
                $_POST[$key] = addslashes($value);

            $result = $pdo->prepare("SELECT pseudo FROM user WHERE pseudo=:pseudo AND id_user<>:id_user");
            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
            $result->execute();

            if($result->rowCount() == 1)
                $msg_error .= "<div class='alert alert-secondary'>The pseudo $_POST[pseudo] is already taken, please choose another one.</div>";
            else {
                /*if(!empty($_FILES['picture']['name'])) {
                    $picture_name = $_POST['pseudo'] . '_' . time() . '-' . rand(1,999) .  '_' . $_FILES['picture']['name'];

                    $picture_name = str_replace(' ', '-', $picture_name);

                    $picture_path = ROOT_TREE . 'uploads/img/' . $picture_name;

                    $max_size = 2000000;

                    if($_FILES['picture']['size'] > $max_size || empty($_FILES['picture']['size']))
                        $msg_error .= "<div class='alert alert-danger'>Please select a 2Mo file maximum !</div>";

                    $type_picture = ['image/jpeg', 'image/png', 'image/gif'];

                    if(!in_array($_FILES['picture']['type'], $type_picture) || empty($_FILES['picture']['type']))
                        $msg_error .= "<div class='alert alert-danger'>Please select a JPEG/JPG, a PNG or a GIF file.</div>";
                }
                elseif (isset($_POST['actual_picture'])) // if I update a product, I target the new input created with my $update_product
                    $picture_name = $_POST['actual_picture'];
                else
                    $picture_name = 'default.jpg';*/


                $user_query = $pdo->prepare("UPDATE user SET pseudo=:pseudo, firstname=:firstname, lastname=:lastname, pwd=:pwd, email=:email, gender=:gender, city=:city, zip_code=:zip_code, address=:address, privilege=:privilege WHERE id_user = :id_user");
                $user_query->bindValue(":pseudo", $_POST['pseudo'], PDO::PARAM_STR);
                $user_query->bindValue(":firstname", $_POST['firstname'], PDO::PARAM_STR);
                $user_query->bindValue(":lastname", $_POST['lastname'], PDO::PARAM_STR);

                if(!empty($_POST['pwd']))
                    $user_query->bindValue(":pwd", password_hash($_POST['password'], PASSWORD_BCRYPT), PDO::PARAM_STR);
                else {
                    $pwd_query = $pdo->prepare("SELECT pwd FROM user WHERE id_user=:id_user");
                    $pwd_query->bindValue(":id_user", $_SESSION['user']['id_user'], PDO::PARAM_INT);

                    if($pwd_query->execute())
                        $user_query->bindValue(":pwd", $pwd_query->fetch()["pwd"], PDO::PARAM_STR);
                }

                $user_query->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
                $user_query->bindValue(":gender", $_POST['gender'], PDO::PARAM_STR);
                $user_query->bindValue(":city", $_POST['city'], PDO::PARAM_STR);
                $user_query->bindValue(":zip_code", $_POST['zip_code'], PDO::PARAM_STR);
                $user_query->bindValue(":address", $_POST['address'], PDO::PARAM_STR);
                $user_query->bindValue(":privilege", $_POST['privilege'], PDO::PARAM_INT);
                //$user_query->bindValue(":picture", $_POST['picture'], PDO::PARAM_STR);
                $user_query->bindValue(":id_user", $_SESSION['user']['id_user'], PDO::PARAM_INT);

                if($user_query->execute()) {
                    $user_query = $pdo->query("SELECT * FROM user WHERE id_user=".$_SESSION['user']['id_user']);
                    foreach($user_query->fetch() as $key => $value) {
                        if($key != 'pwd')
                            $_SESSION['user'][$key] = $value;
                    }

                    /*debug($_FILES['picture']['name']);
                    debug($_FILES['picture']['tmp_name']);
                    if(!empty($_FILES['picture']['name']))
                        copy($_FILES['picture']['tmp_name'], $picture_path);*/

                    header("location:".URL."profile.php");
                    exit();
                }
            }
        }
    }
?>

<div class="d-flex justify-content-center">
    <h1 style='margin-right: 15px;'><?= $page ?></h1>
    <a style='font-size: 1.5em; margin-right: 15px;' href='?update'><i class='fas fa-pencil-alt text-warning'></i></a>
    <a style='font-size: 1.5em;'><i class='fas fa-trash-alt text-danger'></i></a>
</div>

<?= $msg_error ?>

<p>Please find your informations below:</p>
<form action="" method="post">
    <!--<div class="form-group text-left r">
        <?php
            /*if(isset($_SESSION['user']['picture'])) {
                echo "<input name='actual_picture' value='".$_SESSION['user']['picture']."' type='hidden'>";
                echo "<img style='width:25%;' src='" . URL . "uploads/img/".$_SESSION['user']['picture']."'>";
            }
        ?>
        <?php if(isset($_GET['update'])) : ?>
        <label for="picture">Picture</label>
        <input type="file" class="form-control-file" id="picture" name="picture">
    <?php endif; */?>
    </div>-->
    <ul style="list-style-type: none;" class="text-left list-group">
        <li class="form-group">
            <label for="pseudo">Pseudo:</label>
            <input class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>" type="text" id="pseudo" name="pseudo" value="<?= $_SESSION['user']['pseudo'] ?>"></li>
        <li class="form-group">
            <label for="firstname">Firstname:</label>
            <input type="text" id="firstname" name="firstname" value="<?= $_SESSION['user']['firstname'] ?>" class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>"></li>
        <li class="form-group">
            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname" name="lastname" value="<?= $_SESSION['user']['lastname'] ?>" class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>"></li>
        <li class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="pwd" class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>"></li>
        <liv class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $_SESSION['user']['email'] ?>" class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>"></li>
        <li class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" class='form-control' <?= (!isset($_GET['update']) ? 'disabled':''); ?>>
                <option value="m" <?php if($_SESSION['user']['gender'] == 'm'){echo 'selected';} ?>>Men</option>
                <option value="f" <?php if($_SESSION['user']['gender'] == 'f'){echo 'selected';} ?>>Women</option>
                <option value="o" <?php if($_SESSION['user']['gender'] == 'o'){echo 'selected';} ?>>Other</option>
            </select>
        </li>
        <li  class="form-group">
            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?= $_SESSION['user']['city'] ?>" class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>"></li>
        <li  class="form-group">
            <label for="zip_code">Zipcode:</label>
            <input type="text" id="zip_code" name="zip_code" value="<?= $_SESSION['user']['zip_code'] ?>" class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>"></li>
        <li  class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?= $_SESSION['user']['address'] ?>" class="<?= (!isset($_GET['update']) ? 'form-control-plaintext':'form-control'); ?>"></li>
        <?php if(userAdmin()) : ?>
        <li class="form-group">
            <label for="privilege">Privilege:</label>
            <select class="form-control" name="privilege" <?= (!isset($_GET['update']) ? 'disabled':''); ?>>
                <option value="1" selected>admin</option>
                <option value="0">user</option>
            </select>
        </li>
        <?php endif ?>
    </ul>
    <?php if(isset($_GET['update'])) : ?>
    <div class=""><input type="submit" value="Update profile" class="btn btn-success btn-block btn-lg" /></div>
    <?php endif ?>
</form>

<?php
    require_once("inc/footer.php");
?>
