<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo get_info('title') . ' - ' . get_info('description'); ?></title>
    <link rel="shortcut icon" href="static/icon/favicon.ico" type="image/x-icon">
    <link rel="icon" sizes="any" mask="" href="static/icon/favicon.svg">
    <meta name="description" content="">
    <meta name="keywords" content="url,网址,域名,短网址,短域名,跳转,防红">
    <meta name="author" content="愿与君长安">
    <meta name="founder" content="愿与君长安">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <!-- <link type="text/css" rel="stylesheet" href="static/css/main.css"> -->
    <link rel="stylesheet/less" href="static/css/main.less">
    <link type="text/css" rel="stylesheet" href="static/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="static/js/less.min.js"></script>
</head>
<body>
<div class="container">
    <!-- 主体 -->
    <div class="row row-xs">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-10 col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
            <!-- error 提示框 -->
            <div style="height: 50px;">
                <div id="error-tips" class="alert top top-xs alert-dismissible alert-danger expand-transition"
                     style="display: none;">
                </div>
            </div>

            <!-- 主体头部 -->
            <div class="page-header">
                <!-- 随机图API -->
                <div class="apiimg">
                    <!-- <img class="img-xys" src="http://api.btstu.cn/sjbz/?lx=dongman" /> -->
                </div>
                <div class="meta">
                    <h2 class="title"><span style="font-family:Qiu">
									<?php echo get_info('title'); ?></span></h2>
                    <h3 class="description"><span style="font-family:Qiu">
									<?php echo get_info('description'); ?></span></h3>
                </div>
            </div>

            <!-- 主体输入框 -->
            <div class="form-group " id="input-wrap">
                <label class="control-label" for="inputContent">请输入长网址:</label>
                <input type="text" id="inputContent" class="form-control" placeholder="如:url.sd.cn特殊后缀请加http://">
            </div>
            <!-- 生成按钮 -->
            <div class="text-right">
                <div class="input_group_addon btn btn-primary" id="shorten">点击生成</div>
            </div>

            <!-- 生成模拟框 -->
            <div class="modal expand-transition" id="result-wrap" style="display:none">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" onclick="closeWrapper()"
                                    aria-hidden="true">×
                            </button>
                            <h4 class="modal-title">生成成功！</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group"><input type="text" class="form-control" id="gen_result_url"
                                                           value="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="copy" type="button" class="btn btn-success pull-left">复制</button>
                            <a target="_blank" id="preViewBtn" href="#">
                                <button type="button" class="btn btn-success">点击预览</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php require_once('footer.php') ?>
</div>
</body>
<script type="text/javascript" src="static/js/app.js"></script>
</html>
