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

<?= $this->resource_manager->load( $resources ) ?>

</head>

<body class="inner">

<div id="container">

<div id="wrap">

<?= $this->load->view( 'master/menu_view' ) ?>

<div id="mainContent">

<?= $this->load->view( 'master/header_view' ) ?>

<?= $this->load->view( $page_view ) ?>

</div>

</div>

<?= $this->load->view( 'master/footer_view' ) ?>

</div>
    
</body>

</html>