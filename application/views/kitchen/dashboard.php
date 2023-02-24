<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DASHBOARD - LIST ORDER</title>
	<?php $this->load->view('order/css'); ?>
</head>
<body>
    <div class="container">

      <?php $this->load->view('kitchen/navbar'); ?>

      <div class="row">
      	<div class="col-sm-12 col-md-12 col-lg-12">
	      	<?php
		      if($info!=""){
		          echo '<div class="alert alert-info">
		              <span class="close" data-dismiss="alert">×</span>
		              '.$info.'
		          </div>';
		      }else{
	      	?>
	      	<h2>Order</h2>
					<table class="table table-responsive table-bordered">
					<tr>
						<th>No</th>
						<th>Nomor Meja</th>
						<th>Nomor Pesanan</th>
						<th>Status Pesanan</th>
						<th>Status Bayar</th>
						<th>Metode Bayar</th>
						<th>Order Time</th>
						<th>Opsi</th>
					</tr>
						<?php 
						// echo json_encode($data);
						$i=1;
						foreach (json_decode($data,TRUE) as $row){ 
						?>
					  	<tr>
					  		<td><?=$i++?></td>
					  		<td><?=$row['nomor_meja']?></td>
					  		<td><?=$row['nomor_pesanan']?></td>
					  		<td><?=ucfirst($row['status_pesanan'])?></td>
					  		<td><?=(($row['status_bayar']==0) ? 'Unpaid' : 'Paid')?></td>
					  		<td><?=ucfirst($row['metode_bayar'])?></td>
					  		<td><?=$row['input_time']?></td>
					  		<td>
					  			<a href="<?=base_url('list_order/'.$row['id'])?>" class="label label-info">Detail</a>
					  		</td>
					  	</tr>
						<?php  } ?>
					</table>
				</div>
      	<?php }?>
    </div> <!-- /container -->

    <?php $this->load->view('order/js'); ?>
  
</body>
</html>