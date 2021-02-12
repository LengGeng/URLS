<?php
ini_set('display_errors', 0);
?>
    <html>
    <head>
        <title>管理后台</title>
        <link rel="shortcut icon" href="../../icon/favicon.ico">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="../../css/default.css"/>
        <link href="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../../js/admin.js"></script>
        <style type="text/css">
            .style2 {
                font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
            }
        </style>
    </head>
<body>
<?php if (is_admin_login()): ?>
    <div class="container container-fluid">
    <h1><a href="<?php echo SITE_URL ?>/admin"><h1 style="color:black;"><?php echo SITE_TITLE ?></h1></a></h1>

    <nav class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#">管理</a></li>
        <li role="presentation"><a href="#">其它</a></li>
        <li role="presentation"><a href="../../../admins/logout.php">注销</a></li>
    </nav>
    <div class="row">
    <h2>索引短网址<a href="<?php echo SITE_TITLE ?>"
                style="font-size: 0.5em;float: right;color: #ccc;"><?php echo SITE_TITLE ?></a></h2>
    <hr/>
    <!--    <div class="pull-left">-->
    <!--        <span class="btn glyphicon glyphicon-plus"></span>-->
    <!--    </div>-->
    <form class="form-inline">
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon">别名</span>
                <input type="text" class="form-control" name="search_alias" size="30"
                       placeholder="通过自定义后缀搜索"
                       value="<?php echo @htmlentities($_GET['search_alias']) ?>"/>
            </div>
            <span class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
        </div>
        <div class="form-group has-feedback">
            <div class="input-group">
                <span class="input-group-addon">网址</span>
                <input type="text" class="form-control" name="search_url" size="30" placeholder="通过字符搜索"
                       value="<?php echo @htmlentities($_GET['search_url']) ?>"/>
            </div>
            <span class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
        </div>
        <ul class="pull-right">
            <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></button>
            <button type="submit" class="btn btn-default">搜索</button>
            <button type="reset" class="btn btn-default reset"><a href="../../../admins/index.php"
                                                                  style="text-decoration: none;color: #000;">重置</a>
            </button>
        </ul>
    </form>
<?php endif; ?>