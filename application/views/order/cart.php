<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CART</title>
	<?php $this->load->view('order/css'); ?>
</head>
<body>
    <div class="container">

      <?php $this->load->view('order/navbar'); ?>


      <div class="row">
      	<?php 
      	if(!get_cookie('cart')){
      		echo '<div class="col-md-12 col-lg-12 col-sm-12"><div class="alert alert-info" role="alert">Cart is empty <a href="'.base_url().'">Order Now</a></div></div>';
      	}else{ 
      	$data_cart = json_decode($data_cart, TRUE);
      	foreach ($data_cart as $row){ ?>
      	<form action="<?=base_url('order')?>" method="post">
      	<div class="col-sm-6 col-md-4 col-lg-3" style="height:500px">
			<div class="thumbnail">
				<img src="<?=base_url('assets/img/thumbnail-242x200.svg')?>" alt="...">
				<div class="caption">
					<h3><?=$row['nama']?></h3>
					<p>Rp<?=number_format($row['harga'],'0','.','.')?></p>
					<p>
						<span class="label label-info">
							<?=ucfirst($row['jenis'])?>
						</span>
						&nbsp;
						<span class="label label-<?=(($row['status']) ? 'info' : 'danger')?> ">
							<?=(($row['status']) ? 'Available' : 'Out Of Stock')?>
						</span>
					</p>
					<p>
						<small>Quantity</small>
						<input type="number" name="quantity[]" class="form-control" min="1" value="<?=$row['quantity']?>">
					</p>
					<a class="btn btn-xs btn-danger" href="<?=base_url('del_cart/'.$row['id'])?>">Remove <i class="glyphicon glyphicon-remove"></i></a>
					
				</div>
			</div>
			<div class="clearfix"></div>
      	</div>
      	<?php } ?>
      	<div class="col-md-12 col-lg-12 col-sm-12">
			<input type="submit" class="btn btn-primary btn-lg" role="button" value="Checkout">
		</div>
		</form>
		<?php } ?>
    </div> <!-- /container -->

    <?php $this->load->view('order/js'); ?>
  
</body>
</html>