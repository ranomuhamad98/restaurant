<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LIST ORDER</title>
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
	      	<h2>Menu</h2>
					<table class="table table-responsive table-bordered">
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Harga</th>
						<th>Status Aktif</th>
						<th>Jenis</th>
						<th>Opsi</th>
					</tr>
					<?php $i=1;foreach (json_decode($data,TRUE) as $row){ ?>
			  	<tr>
			  		<td><?=$i++?></td>
			  		<td><?=$row['nama']?></td>
			  		<td>Rp<?=number_format($row['harga'],'0','.','.')?></td>
			  		<td><?=(($row['status']) ? 'Ready' : 'Out Of Stock')?></td>
			  		<td><?=ucfirst($row['jenis'])?></td>
			  		<td>
			  			<?php if($row['status']){ ?>
			  				<a href="<?=base_url('list_menu_off/'.$row['id'])?>" class="label label-success">
			  					Out Off Stock
			  				</a>
			  			<?php }else{ ?>
		  					<a href="<?=base_url('list_menu_on/'.$row['id'])?>" class="label label-danger">
		  						Ready
		  					</a>
			  			<?php } ?>
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