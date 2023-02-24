<!-- Static navbar -->
<nav class="navbar navbar-default">
<div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?=base_url()?>">Restaurant ABC</a>
  </div>
  <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav navbar-right">
      <li class="<?=(($this->uri->segment(1)=='menu'||!$this->uri->segment(1)) ? 'active' : '')?>"><a href="<?=base_url()?>">Menu</a></li>
      <li class="<?=(($this->uri->segment(1)=='status') ? 'active' : '')?>"><a href="<?=base_url('status')?>">Check Status Order</a></li>
      <li class="<?=(($this->uri->segment(1)=='cart') ? 'active' : '')?>"><a href="<?=base_url('cart')?>">Cart <i class="glyphicon glyphicon-shopping-cart"></i></a></li>
      <li class="<?=(($this->uri->segment(1)=='login') ? 'active' : '')?>"><a href="<?=base_url('login')?>">Login <i class="glyphicon glyphicon-user"></i></a></li>
    </ul>
  </div><!--/.nav-collapse -->
</div><!--/.container-fluid -->
</nav>