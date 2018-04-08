<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title><?= $this->seo_manager->title ?></title>

<!-- Developed by Codeion -->
<!-- http://www.codeion.com -->

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="rs" />
<meta name="Description" content="<?= $this->seo_manager->description ?>" />
<meta name="Keywords" content="<?= $this->seo_manager->keywords ?>" />

<?= $this->resource_manager->load() ?>

<script type="text/javascript">
$(document).ready(function(){
    $('a.forgotPass').toggle(function() {
      $('#loginForm').slideUp(function(){
              $('#loginForm').attr('style','display:none');
              $('#forgotForm').slideDown(function(){
                  $('#forgotForm').attr('style','display:block');
              });
          });
      $('a.forgotPass').text('Login');
    }, function() {
      $('#forgotForm').slideUp(function(){
              $('#forgotForm').attr('style','display:none');
              $('#loginForm').slideDown(function(){
                  $('#loginForm').attr('style','display:block');
              });
          });
          $('a.forgotPass').text('Zaboravili ste lozinku');
    });
});
</script>

</head>

<body class="login">

    <div id="boxHolder">
    
        <img src="<?= layout_url( 'logo.png' ) ?>" alt="" />
        <!--<h1>Backoffice</h1>-->
        
        <a href="#" class="forgotPass">Zaboravili ste lozinku?</a>
        
        <form action="<?= site_url( 'login' ) ?>" method="post" id="loginForm" style="display:block">
        
            <ul class="loginForm">
            
                <li>
                    <label>E-mail:</label>
                    <span class="inputField normal"><input type="text" name="login_email" /></span>
                </li>
                <li>
                    <label>Lozinka:</label>
                    <span class="inputField normal"><input type="password" name="login_password" /></span>
                </li>
                <li>
                    <!--<a href="#" class="forgotPass">Zaboravili ste lozinku?</a>-->
                    <span class="button enter"><input type="submit" value="Potvrdi" name="submit" /></span>
                </li>
            
            </ul>
            
            <?php 
                if($this->session->flashdata('login_error') != ''){
                echo '<li id="login_error">' . $this->session->flashdata('login_error') . '</li>';
            }
            ?>
        
        </form>
        
        <form method="post" action="" id="forgotForm" style="display:none;">

            <ul class="loginForm">

                <li style="margin-top:116px">
                    <label>E-mail:</label>
                    <span class="inputField normal"><input type="text" name="pass_recovery" id="forgot_email" /></span>
                </li>
                <li>
                    <span class="button enter" style="position:absolute; left:120px"><input type="button" style="text-align:left;" value="<?= $this->lang->line('form_send')?>" onclick="forgotPass('forgot_email')"/></span>
                </li>

            </ul>

        </form>
    </div>
    
    <a href="http://www.codeion.com/" id="dev" target="_blank">Developed by Codeion</a>
    
    <p class="copyRight">Copyright &copy; 2014 KiddyJoy. All Rights Reserved.</p>
        
</body>

</html>