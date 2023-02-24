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
    <a class="navbar-brand" href="<?=base_url()?>">Restaurant ABC - KITCHEN</a>
  </div>
  <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav navbar-right">
      <li class="<?=(($this->uri->segment(1)=='list_order'||$this->uri->segment(1)=='dashboard'||!$this->uri->segment(1)) ? 'active' : '')?>"><a href="<?=base_url('dashboard')?>">Order</a></li>
      <li class="<?=(($this->uri->segment(1)=='list_menu') ? 'active' : '')?>"><a href="<?=base_url('list_menu')?>">Menu</a></li>
      <li><a href="<?=base_url('logout')?>">Logout <i class="glyphicon glyphicon-logout"></i></a></li>
    </ul>
  </div><!--/.nav-collapse -->
</div><!--/.container-fluid -->
</nav>