<title><?php $this->print_html_head_title() ?></title>
<!-- google fonts -->
<link href="https://fonts.googleapis.com/css?family=Milonga&display=swap&subset=latin-ext" rel="stylesheet" /><!-- font-family: 'Milonga', cursive; -->
<link href="https://fonts.googleapis.com/css2?family=Exo&display=swap" rel="stylesheet" />
<!-- javascript -->
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="/deco/js/lightbox/lightbox.js"></script>
<script src="/deco/js/change_css_in_situ.js"></script>
<!-- theme css -->
<link rel="stylesheet" type="text/css" href="<?php echo mk_css_file_path($_COOKIE['theme']??'', 1); ?>" />
<!-- layout css -->
<link rel="stylesheet" type="text/css" href="/deco/js/lightbox/lightbox.css" />
<link rel="stylesheet" type="text/css" href="/deco/css/site_base.css" />
<?php $this->print_html_head_block(); ?>
