<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>FINISH ORDER</title>
	<?php $this->load->view('order/css'); ?>
</head>
<body>
    <div class="container">

      <?php $this->load->view('order/navbar'); ?>


      <div class="row">
      	<div class="col-md-12 col-lg-12 col-sm-12">
      	<h2>Transaction Number</h2>
      	<p>
      		<h3><code><?=$data['nomor_pesanan']?></code></h3>
      	</p>
      	<h2>My Order</h2>
      	<table class="table table-responsive table-bordered">
      		<tr>
      			<th>No</th>
      			<th>Nama Menu</th>
      			<th>Quantity</th>
      			<th>Harga</th>
      			<th>Sub Total</th>
      		</tr>
      	<?php 
      	// echo json_encode($data);
      	$_total = 0;$i=1;
      	foreach ($detail as $row){ 
      		$_harga = $row['total']/$row['quantity'];
      		$menu = $this->Curl->get_menu($row["id_menu"]);
      		$menu = $menu['data'];
      	?>
      	<tr>
      		<td><?=$i++?></td>
      		<td><?=$menu['nama']?></td>
      		<td><?=$row['quantity']?></td>
      		<td>Rp<?=number_format($_harga,'0','.','.')?></td>
      		<td>Rp<?=number_format($row['total'],'0','.','.')?></td>
      	</tr>
      	<?php $_total += $row['total']; } ?>
      	<tr style="font-weight:bold">
      		<td colspan="4" align="right">Total</td>
      		<td>Rp<?=number_format($_total,'0','.','.')?></td>
      	</tr>
      	</table>
      	<h2>Table No</h2>
      	<p>
      		<h4><code><?=$data['nomor_meja']?></code></h4>
      	</p>
      	<h2>Payment Method</h2>
      	<p>
      		<h4><code><?=ucfirst($data['metode_bayar'])?></code></h4>
      	</p>
		</div>
    </div> <!-- /container -->

    <?php $this->load->view('order/js'); ?>
  
</body>
</html>