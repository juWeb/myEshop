<?php require_once("init.php") ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">

    <meta name="author" content="TeamKeepers">

    <!-- CAREFUL to create the favicon -->
    <link rel="icon" href="">

    <title>Offbeat cultures | Kicks enthusiasts</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <!-- My CSS -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

  </head>

  <body>
  <div class='container'>
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1" style="display:inline-block">
            <?php if(!userConnect()) : ?>
            <a class="text-muted" href="<?= URL ?>signup.php"><strong style='color:#5c0120'>Sign up</strong></a>
            <?php else : ?>
              <a class="nav-link" href="<?= URL ?>profile.php">Profile picture</a>
              <a href="<?= URL ?>cart.php"><i class="fas fa-cart-arrow-down"></i></a>
            <?php endif; ?>
          </div>
          <div class="col-4 text-center">
            <a class="blog-header-logo text-dark" href="<?= URL ?>"><img src="uploads/img/logo.png" alt="logo"></a>
          </div>
          <div class="col-4 d-flex justify-content-end align-items-center">
            <a class="text-muted" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
            </a>
            <?php if(!userConnect()) : ?>
            <a class="btn btn-sm btn-outline-secondary" href="<?= URL ?>login.php">Log in</a>
            <?php else : ?>
            <a class="btn btn-sm btn-outline-secondary" href="<?= URL ?>logout.php">Log out</a>
            <?php endif; ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </div>
        </div>
      </header>


      <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between">
          <a class="p-2 text-muted" href="<?= URL ?>">Home</a>
          <a class="p-2 text-muted" href="<?= URL ?>eshop.php">Eshop</a>
          <a class="p-2 text-muted" href="#">Contact</a>
          <a class="p-2 text-muted" href="#">Culture</a>
          <a class="p-2 text-muted" href="#">Business</a>
          <?php if(userAdmin()) : ?>
              <a class="p-2 text-muted" href="<?= URL ?>admin/product_form.php">BackOffice access</a>
          <?php endif; ?>
        </nav>
      </div>
      </div>

    <main role="main" class="container">
        <div class="starter-template">