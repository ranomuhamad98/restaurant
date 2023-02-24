<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>RESTAURANT ABC</title>
	<?php $this->load->view('order/css'); ?>
</head>
<body>
    <div class="container">

      <?php $this->load->view('order/navbar'); ?>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Restaurant ABC</h1>
        <p>I'm Lovin it</p>
        <!-- <p>
          <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs »</a>
        </p> -->
      </div>
      <div class="row">
      	<?php
	      if(!empty($this->session->flashdata('info'))){
	          echo '<div class="col-sm-12 col-md-12 col-lg-12"><div class="alert alert-info">
	              <span class="close" data-dismiss="alert">×</span>
	              '.$this->session->flashdata('info').'
	          </div></div>';
	      }
	       
      	if($list_menu['error']){
      		echo '<div class="alert alert-info" role="alert">['.$list_menu['response_code'].']'.$list_menu['message'].'</div>';
      	}else{
      	?>
      	<?php foreach ($list_menu['data'] as $row){ ?>
      	<div class="col-sm-6 col-md-4 col-lg-3" style="height:425px">
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
						<span class="label label-<?=(($row['status']) ? 'success' : 'danger')?> ">
							<?=(($row['status']) ? 'Available' : 'Out Of Stock')?>
						</span>
					</p>
					<p>
					<a href="<?=base_url('do_cart/'.$row['id'])?>" class="btn btn-primary <?=(($row['status']) ? '' : 'disabled')?>" role="button">Add To Cart</a>
					</p>
				</div>
			</div>
			<div class="clearfix"></div>
      	</div>
      	<?php } }?>
    </div> <!-- /container -->

    <?php $this->load->view('order/js'); ?>
  
</body>
</html>