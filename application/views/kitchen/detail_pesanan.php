<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DETAIL ORDER</title>
	<?php $this->load->view('order/css'); ?>
</head>
<body>
    <div class="container">

      <?php $this->load->view('kitchen/navbar'); ?>


      <div class="row">
      	<div class="col-md-12 col-lg-12 col-sm-12">
      	<?php 
	      	if(!empty($this->session->flashdata('info'))){
	            echo '<div class="alert alert-info">
	                <span class="close" data-dismiss="alert">×</span>
	                '.$this->session->flashdata('info').'
	            </div>';
	        }
      	?>
      	<h2 class="pull-left">Order Details</h2> 
      	<a href="<?=base_url('dashboard')?>" class="btn btn-default btn-lg pull-right">Back</a>
      	<table class="table table-responsive table-bordered">
      		<tr>
      			<th>No</th>
      			<th>Nama Menu</th>
      			<th>Status</th>
      			<th>Quantity</th>
      			<th>Harga</th>
      			<th>Sub Total</th>
      			<th>Opsi</th>
      		</tr>
      	<?php 
      	$_total = 0;$i=1;
      	$data = json_decode($data,TRUE);
      	foreach ($data as $row){ 
      		$_harga = $row['total']/$row['quantity'];
      		$menu = $this->Curl->get_menu($row["id_menu"]); $menu = $menu['data'];
      		
      		$_label_link = array('btn-primary','btn-info','btn-success');
      		$_link[0] = base_url('update_order/'.$row['id_transaction'].'/'.$row["id_menu"].'/0');
      		$_link[1] = base_url('update_order/'.$row['id_transaction'].'/'.$row["id_menu"].'/1');
      		$_link[2] = base_url('update_order/'.$row['id_transaction'].'/'.$row["id_menu"].'/2');
      		if($row['status']==0){
      			$_status = "Reject";
      			$_label_link[0] = 'btn-default disabled';	$_link[0] = '#';
      		}else if($row['status']==1){
      			$_status = "Pending";
      			$_label_link[1] = 'btn-default disabled';	$_link[1] = '#';
					}else if($row['status']==2){
						$_status = "Ready";
						$_label_link[2] = 'btn-default disabled';	$_link[2] = '#';
						
					}      		
      	?>
      	<tr>
      		<td><?=$i++?></td>
      		<td><?=$menu['nama']?></td>
      		<td><?=$_status?></td>
      		<td><?=$row['quantity']?></td>
      		<td>Rp<?=number_format($_harga,'0','.','.')?></td>
      		<td>Rp<?=number_format($row['total'],'0','.','.')?></td>
      		<td>
      			<a class="btn btn-xs <?=$_label_link[0]?>" href="<?=$_link[0]?>">Reject</a>
      			<a class="btn btn-xs <?=$_label_link[1]?>" href="<?=$_link[1]?>">Pending</a>
      			<a class="btn btn-xs <?=$_label_link[2]?>" href="<?=$_link[2]?>">Ready</a>
      		</td>
      	</tr>
      	<?php $_total += $row['total']; } ?>
      	</table>
		</div>
    </div> <!-- /container -->

    <?php $this->load->view('order/js'); ?>
  
</body>
</html>