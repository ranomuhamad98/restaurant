<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CHECKOUT</title>
	<?php $this->load->view('order/css'); ?>
</head>
<body>
    <div class="container">

      <?php $this->load->view('order/navbar'); ?>


      <div class="row">
      	<div class="col-md-12 col-lg-12 col-sm-12">

      	<form action="<?=base_url('do_order')?>" method="post">
      	<h2>My Order</h2>
      	<table class="table table-responsive table-bordered">
      		<tr>
      			<th>No</th>
      			<th>Nama Menu</th>
      			<th>Quantity</th>
      			<th>Harga</th>
      			<th>Sub Total</th>
      		</tr>
      	<?php $data_cart = json_decode($data_cart, TRUE);
      	$i=1;$_total = 0;
      	foreach ($data_cart as $row){ 
      		$_subtotal = $row['quantity']*$row['harga'];
      	?>
      	<tr>
      		<td><?=$i++?></td>
      		<td><?=$row['nama']?></td>
      		<td><?=$row['quantity']?></td>
      		<td>Rp<?=number_format($row['harga'],'0','.','.')?></td>
      		<td>Rp<?=number_format($_subtotal,'0','.','.')?></td>
      	</tr>
      	<?php $_total += $_subtotal; } ?>
      	<tr style="font-weight:bold">
      		<td colspan="4" align="right">Total</td>
      		<td>Rp<?=number_format($_total,'0','.','.')?></td>
      	</tr>
      	</table>
      	<h2>Table No</h2>
      	<p><input type="number" name="table_no" class="form-control" min="1"></p>
      	<h2>Payment Method</h2>
      	<p>
      		<select class="form-control" name="payment_method">
      			<option>Choose One..</option>
      			<option value="cash">Cash</option>
      			<option value="debit">Debit</option>
      			<option value="credit">Credit</option>
      		</select>
      	</p>
      	<div>&nbsp;</div>
		<p>
			<input type="submit" class="btn btn-primary btn-lg" role="button" value="Finish Order">
		</p>
		</div>
		</form>
    </div> <!-- /container -->

    <?php $this->load->view('order/js'); ?>
  
</body>
</html>