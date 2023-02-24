<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
	<div class="container">    
        <div style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                <div class="panel-heading">
                    <div class="panel-title">Sign In</div>
                </div>     
                <div style="padding-top:30px" class="panel-body" >
                	<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <?php 
                    $flashSession = '';
                    if(!empty($this->session->flashdata('failed'))){
                        $flashSession .= '<div class="alert alert-danger">
                            <span class="close" data-dismiss="alert">×</span>
                            <i class="fa fa-info-circle fa-fw"></i> '.$this->session->flashdata('failed').'
                        </div>';
                    }
                    if(!empty($this->session->flashdata('success'))){
                        $flashSession .= '<div class="alert alert-success">
                            <span class="close" data-dismiss="alert">×</span>
                            <i class="fa fa-info-circle fa-fw"></i> '.$this->session->flashdata('success').'
                        </div>';
                    }
                    echo $flashSession;
                    ?>
                    <form id="loginForm" action="dologin" method="post" class="form-horizontal" role="form">
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                              <input type="submit" class="btn btn-success" value="Login">
                              <a href="<?=base_url()?>" class="btn btn-default">Back</a>
                            </div>
                        </div>
                    </form>     
                </div>                     
            </div>  
        </div>
    </div>
</body>
</html>