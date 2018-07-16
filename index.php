<?php
    require_once("inc/header.php");

    $page = "Find your dream kicks"
?>
<div class="container">


    <div class="jumbotron p-4 p-md-5 text-white rounded" id="homepage" style="background-image: url('uploads/img/kicks.jpg');background-repeat:no-repeat;background-size:100%;height:690px;opacity:0.8; ">
          
    <h1 class="display-4 font-italic" style='color:#DAA520;width:300px;margin:auto;margin-top:-25px;opacity:1.5'><strong><?= $page ?></strong></h1>
        <p style="margin-top:100px;" ><button type="button" class="btn btn-grey" ><a href="<?= URL ?>signup.php" style="color:black" >Start shopping</a></button></p>
    </div>
</div class="container">
    
<?php
    require_once("inc/footer.php");
?>