<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>STATUS ORDER</title>
	<?php $this->load->view('order/css'); ?>
</head>
<body>
    <div class="container">

    <?php $this->load->view('order/navbar'); ?>


    <div class="row">
      	<div class="col-md-12 col-lg-12 col-sm-12">
      		
      		<?php if($this->input->post()){ 
      			if($info!=""){
				echo '<div class="alert alert-info">
				  <span class="close" data-dismiss="alert">×</span>
				  '.$info.'
				</div>';
				}else{
      				$data = json_decode($data,TRUE);
      		?>
      			<div class="col-md-6 col-lg-6 col-sm-6">
		      		<h2>Transaction Number</h2>
			      	<p>
			      		<h3><code><?=$data['nomor_pesanan']?></code></h3>
			      	</p>
		      	</div>
		      	<div class="col-md-6 col-lg-6 col-sm-6">
		      		<h2>Status</h2>
			      	<p>
			      		<h3><code><?=ucfirst($data['status_pesanan'])?></code></h3>
			      	</p>
		      	</div>
	      		<div class="col-md-12 col-lg-12 col-sm-12">
	      		<h2>My Order</h2>
	      		<table class="table table-responsive table-bordered">
	      		<tr>
	      			<th>No</th>
	      			<th>Menu Name</th>
	      			<th>Status</th>
	      			<th>Quantity</th>
	      			<th>Price</th>
	      			<th>Sub Total</th>
	      		</tr>
			      	<?php 
			      	// echo json_encode($data);
			      	$_total = 0;$i=1;
			      	foreach (json_decode($detail,TRUE) as $row){ 
			      		$_harga = $row['total']/$row['quantity'];
			      		$menu = $this->Curl->get_menu($row["id_menu"]);
			      		$menu = $menu['data'];
			      		if($row['status']==0){
			      			$_status = "Reject";
			      		}else if($row['status']==1){
			      			$_status = "Pending";
			      		}else if($row['status']==2){
			      			$_status = "Ready";
			      		}
			      	?>
				      	<tr>
				      		<td><?=$i++?></td>
				      		<td><?=$menu['nama']?></td>
				      		<td><?=$_status?></td>
				      		<td><?=$row['quantity']?></td>
				      		<td>Rp<?=number_format($_harga,'0','.','.')?></td>
				      		<td>Rp<?=number_format($row['total'],'0','.','.')?></td>
				      	</tr>
			      	<?php if($row['status']!=0) $_total += $row['total']; } ?>
				      	<tr style="font-weight:bold">
				      		<td colspan="4" align="right">Total</td>
				      		<td>Rp<?=number_format($_total,'0','.','.')?></td>
				      	</tr>
		      	</table>
		      	</div>
	      		<div class="col-md-6 col-lg-6 col-sm-6">
			      	<h2>Table No</h2>
			      	<p>
			      		<h4><code><?=$data['nomor_meja']?></code></h4>
			      	</p>
		      	</div>
		      	<div class="col-md-6 col-lg-6 col-sm-6">
			      	<h2>Payment Method</h2>
			      	<p>
			      		<h4><code><?=ucfirst($data['metode_bayar'])?></code></h4>
			      	</p>
			    </div>
	      	<?php } }else{ ?>
	    	<div class="col-md-12 col-lg-12 col-sm-12">
	    		<form action="<?=base_url('status')?>" method="post">
					<h2>Transaction No</h2>
      				<p><input type="text" name="transaction_number" class="form-control"></p>
      				<p>
      					<input type="submit" class="btn btn-primary btn-lg" role="button" value="Check Status">
      				</p>
      			</form>
      		</div>
	      	<?php } ?>
    </div> <!-- /container -->

    <?php $this->load->view('order/js'); ?>
  
</body>
</html>