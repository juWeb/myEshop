<?php
    require_once("inc/header.php");

    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
    {
        $result = $pdo->prepare("SELECT * FROM product WHERE id_product = :id_product");
        $result->bindValue(':id_product', $_GET['id'], PDO::PARAM_INT);

        $result->execute();

        if($result->rowCount() == 1)
        {
            $product_details = $result->fetch();

            extract($product_details);
        }
        else 
        {
            header('location:eshop.php?m=error');
        }
    }
    else
    {
        header('location:eshop.php?m=error');
    }

    if($_POST) { // si clique sur ajouter au panier
        if (!empty($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] != 0) {
            addProduct($id_product, $_POST['quantity'], $picture, $title, $price);
            header('location:cart.php');
        }     

    // we want to display product within the same category in order to maximpise the benefits of the website
    $suggest_resultat = $pdo->query("SELECT * FROM product WHERE category = '$product_details[category]' AND id_product != '$product_details[id_product]' ORDER BY price DESC LIMIT 0,3");
    $suggestion = $suggest_resultat->fetchAll();

    }else{
    
        header('location:eshop.php?m=error');
        exit();
}


    debug($_POST);

    $page = "$title";
?>

        
        <img src="uploads/img/<?=$picture?>" width="50%" style="margin-left:0%;margin-top:10%" alt="<?=$title?>">
        <div class="container" style="width:10000px;margin-left:50%;margin-top:-30%">
            
            <div class="d-flex align-items-center col-lg-6 col-xl-5 pl-5 mb-5 order-1 order-lg-2">
            <div>
              <ul class="breadcrumb justify-content-start">
                <li class="breadcrumb-item"><a href="eshop.php">Eshop</a></li>
                <li class="breadcrumb-item"><a href="eshop.php?cat=.$category"><?= $category ?></a></li>
                <li class="breadcrumb-item active"><?= $page ?></li>
              </ul>
              <h1 class="mb-4"><h1><?= $page ?></h1>
              <div class="d-flex align-items-center justify-content-between mb-4">
                <ul class="list-inline mb-0">
                  <li class="list-inline-item h4 font-weight-light mb-0">EUR <?= $price ?></li>
                  <li class="list-inline-item text-muted font-weight-light"> 
                    <del><?= ($price +100)?></del>
                  </li>
                </ul>
                <div class="d-flex align-items-center">
                  <ul class="list-inline mr-2 mb-0">
                    <li class="list-inline-item mr-0"><i class="fa fa-star text-primary"></i></li>
                    <li class="list-inline-item mr-0"><i class="fa fa-star text-primary"></i></li>
                    <li class="list-inline-item mr-0"><i class="fa fa-star text-primary"></i></li>
                    <li class="list-inline-item mr-0"><i class="fa fa-star text-primary"></i></li>
                    <li class="list-inline-item mr-0"><i class="fa fa-star text-gray-300"></i></li>
                  </ul><span class="text-muted text-uppercase text-sm">25 reviews</span>
                </div>
              </div>
              <p class="mb-4 text-muted"><?= $price ?></p>
              <form action="#" method="POST">
                <div class="row">
                  <div class="col-sm-6 col-lg-12 detail-option mb-3">
                    <h6 class="detail-option-heading"><?=$size?> <span>(required)</span></h6>
                    <label for="size_0" class="btn btn-sm btn-outline-secondary detail-option-btn-label">
                       
                      41
                      <input type="radio" name="size" value="41" id="size_0" required="" class="input-invisible">
                    </label>
                    <label for="size_1" class="btn btn-sm btn-outline-secondary detail-option-btn-label">
                       
                      42
                      <input type="radio" name="size" value="42" id="size_1" required="" class="input-invisible">
                    </label>
                    <label for="size_2" class="btn btn-sm btn-outline-secondary detail-option-btn-label">
                       
                      43
                      <input type="radio" name="size" value="43" id="size_2" required="" class="input-invisible">
                    </label>
                  </div>
                  <div class="col-12 detail-option mb-5">
                    <label class="detail-option-heading font-weight-bold">Items <span>(required)</span></label>
                    <input name="quantity" type="number" value="1" class="form-control detail-quantity">
                  </div>
                </div>
                <ul class="list-inline">
                  <li class="list-inline-item">
                    <button type="submit" class="btn btn-dark btn-lg mb-1" name="cart" value="<?= $_GET['id'] ?>"> <i class="fa fa-shopping-cart mr-2"></i>Add to Cart</button>
                  </li>
                  <li class="list-inline-item"><a href="#" class="btn btn-outline-secondary mb-1"> <i class="far fa-heart mr-2"></i>Add to wishlist</a></li>
                </ul>
              </form>
            </div>
          </div>
        </div>
        <div class="profil">
            <h2>Other members also bought :</h2>
            <?php foreach ($suggestion as $key => $value) : ?>
                <div>
                    <h3><?= $value['title'] ?></h3>
                    <a href=""><img src="uploads/img/<?= $value['picture'] ?>" height="100"></a>
                    <p style="font-weight: bold; font-size: 20px"> <?= $value['price'] ?> €</p>
                    <p style="height: 40px"> <?= substr($value['description'], 0, 40) ?> '</p>
                    <a style="padding:5px 15px; border:1px solid red; color: red; border-radius:4px" href="product_page.php?id=<?= $value['id_product'] ?>">See the product</a>
                </div>
            <?php endforeach; ?>
        </div>
       
<?php
    require_once("inc/footer.php");
?>


