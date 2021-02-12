<?php
global $install_config;
$install_config = [
    'readme' => './readme.txt',  // 安装说明
    'check_table' => 'my_urls',  // 用于检查安装的数据表名
    'check_file' => 'install.ink',  // 用于检查安装的文件
    'config_file' => '../config.php',  // 配置文件
    'sql_file' => './urls.sql',  // sql文件
    'admin' => 'admin',  // 管理员账户
    'password' => '123456',  // 管理员密码
];
$id = isset($_GET['do']) ? $_GET['do'] : 0;
if ($installed = file_exists($install_config['check_file'])) {
    $id = '0';
}
function check_func($f, $m = false)
{
    if (function_exists($f)) {
        return '<font color="green">可用</font>';
    } else {
        if ($m) {
            return '<font color="red">不支持</font>';
        } else {
            return '<font color="black">不支持</font>';
        }
    }
}

function check_class($f, $m = false)
{
    if (class_exists($f)) {
        return '<font color="green">可用</font>';
    } else {
        if ($m == false) {
            return '<font color="black">不支持</font>';
        } else {
            return '<font color="red">不支持</font>';
        }
    }
}

function check_connect($config, $w = false)
{
    global $install_config;
    print_r($config);
    // 提取配置信息
    $conf = [
        'host' => isset($config['host']) ? $config['host'] : NULL,
        'port' => isset($config['port']) ? $config['port'] : NULL,
        'user' => isset($config['user']) ? $config['user'] : NULL,
        'password' => isset($config['password']) ? $config['password'] : NULL,
        'dbname' => isset($config['dbname']) ? $config['dbname'] : NULL,
    ];
    // 检查配置信息
    if ($conf['host'] == null || $conf['port'] == null || $conf['user'] == null || $conf['dbname'] == null) {
        echo '<div class="alert alert-danger">配置参数错误,请确保每项都不为空<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
        return false;
    }
    // 尝试连接
    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};port={$config['port']}";
        $dao = new PDO($dsn, $config['user'], $config['password']);
    } catch (PDOException $e) {
        if ($e->getCode() == 2002)
            echo '<div class="alert alert-warning">连接数据库失败，数据库地址填写错误！</div>';
        elseif ($e->getCode() == 1045)
            echo '<div class="alert alert-warning">连接数据库失败，数据库用户名或密码填写错误！</div>';
        elseif ($e->getCode() == 1049)
            echo '<div class="alert alert-warning">连接数据库失败，数据库名不存在！</div>';
        else
            echo '<div class="alert alert-warning">连接数据库失败，[' . $e->getCode() . ']' . $e->getMessage() . '</div>';
        return false;
    }
    // 保存配置文件
    if ($w) {
        $config_opt = "<?php\n\$config = " . var_export($conf, true) . ';';
        if (file_put_contents($install_config['config_file'], $config_opt)) {
            echo '<div class="alert alert-success">数据库配置文件保存成功！</div>';
        } else {
            echo '<div class="alert alert-danger">保存失败，请确保网站根目录有写入权限<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
            return false;
        }
    }
    return $dao;
}

function check_table($dao)
{
    global $install_config;
    // 检查数据表是否存在
    if ($dao->query("select * from `" . $install_config['check_table'] . "` where 1") == FALSE) {
        echo '<p align="right"><a class="btn btn-primary btn-block" href="?do=4">创建数据表>></a></p>';
    } else {
        echo '<div class="list-group-item list-group-item-info">系统检测到你已安装过程序</div>
                <div class="list-group-item">
                    <a href="?do=5" class="btn btn-block btn-info">跳过安装</a>
                </div>
                <div class="list-group-item">
                    <a href="?do=4" onclick="if(!confirm(\'全新安装将会清空所有数据，是否继续？\')){return false;}"
                       class="btn btn-block btn-warning">强制全新安装</a>
                </div>';
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>程序安装</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
    <!--    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>-->
    <!--    <script src="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>-->
</head>
<body>
<div class="container" style="padding-top:10px;">
    <div class="col-xs-12 col-sm-8 col-lg-8 center-block" style="float: none;">
        <!-- 0 -->
        <?php if ($id == '0') { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" align="center">安装说明</h3>
                </div>
                <div class="panel-body">
                    <p>
                        <iframe src="<?php echo $install_config['readme']; ?>"
                                style="width:100%;height:250px;"></iframe>
                    </p>
                    <?php if ($installed) { ?>
                        <div class="alert alert-warning">您已经安装过，如需重新安装请删除<font color=red>
                                install/<?php echo $install_config['check_file']; ?> </font>文件后再安装！
                        </div>
                    <?php } else { ?>
                        <p align="center"><a class="btn btn-primary" href="?do=1">开始安装</a></p>
                    <?php } ?>
                </div>
            </div>
            <!-- 1 -->
        <?php } elseif ($id == '1') { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" align="center">环境检查</h3>
                </div>
                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                        <span class="sr-only">10%</span>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width:20%">函数检测</th>
                            <th style="width:15%">需求</th>
                            <th style="width:15%">当前</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>PHP 7.0+</td>
                            <td>必须</td>
                            <td><?php echo phpversion(); ?></td>
                        </tr>
                        <tr>
                            <td>curl_exec()</td>
                            <td>必须</td>
                            <td><?php echo check_func('curl_exec', true); ?></td>
                        </tr>
                        <tr>
                            <td>file_get_contents()</td>
                            <td>必须</td>
                            <td><?php echo check_func('file_get_contents', true); ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <p>
                    <span>
                        <a class="btn btn-primary" href="?do=0">上一步</a>
                    </span>
                        <span style="float:right">
                        <a class="btn btn-primary" href="?do=2" align="right">下一步</a>
                    </span>
                    </p>
                </div>
            </div>
            <!-- 2 -->
        <?php } elseif ($id == '2') { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" align="center">数据库配置</h3>
                </div>
                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width: 30%">
                        <span class="sr-only">30%</span>
                    </div>
                </div>
                <div class="panel-body">
                    <form action="?do=3" class="form-sign" method="post">
                        <label for="host">数据库地址:</label>
                        <input id="host" type="text" class="form-control" name="host" value="localhost">
                        <label for="port">数据库端口:</label>
                        <input id="port" type="text" class="form-control" name="port" value="3306">
                        <label for="user">数据库用户名:</label>
                        <input id="user" type="text" class="form-control" name="user">
                        <label for="password">数据库密码:</label>
                        <input id="password" type="text" class="form-control" name="password">
                        <label for="dbname">数据库名:</label>
                        <input id="dbname" type="text" class="form-control" name="dbname">
                        <br><input type="submit" class="btn btn-primary btn-block" name="submit" value="保存配置">
                    </form>
                    <br/>
                    （如果已事先填写好config.php相关数据库配置，请 <a href="?do=3&jump=1">点击此处</a> 跳过这一步！）
                </div>
            </div>
            <!-- 3 -->
        <?php } elseif ($id == '3') { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" align="center">保存数据库</h3>
                </div>
                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width: 50%">
                        <span class="sr-only">50%</span>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_GET['jump']) && $_GET['jump'] == 1) {
                        if (file_exists($install_config['config_file'])) {
                            require_once($install_config['config_file']);
                            // 检测配置文件是否可以连接数据库
                            if (check_connect($config))
                                echo '<div class="alert alert-success">数据库配置文件配置成功！</div>';
                            else
                                echo '<a href="javascript:history.back(-1)">&lt;&lt; 返回上一页</a>';
                        } else {
                            echo '<div class="alert alert-danger">未检测到配置文件，请先填写好数据库并保存后再安装！<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
                        }
                    } else {
                        if ($dao = check_connect($_POST, true))
                            check_table($dao);
                        else
                            echo '<a href="javascript:history.back(-1)">&lt;&lt; 返回上一页</a>';
                    }
                    ?>
                </div>
            </div>
            <!-- 4 -->
        <?php } elseif ($id == '4') { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" align="center">创建数据表</h3>
                </div>
                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                        <span class="sr-only">70%</span>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    include_once $install_config['config_file'];
                    if ($dao = check_connect($config)) {
                        $sql_file = file_get_contents($install_config['sql_file']);
                        $sqls = array_filter(explode(';', $sql_file));
                        $dao->query("set sql_mode = ''");
                        $dao->query("set names utf8");
                        $t = 0;
                        $f = 0;
                        $error = '';
                        foreach ($sqls as $sql) {
                            if ($sql == '') continue;
                            try {
                                $dao->exec($sql);
                                $t++;
                            } catch (PDOException $e) {
                                $error .= $e->getMessage() . '<br/>';
                                $f++;
                            }
                        }
                        if ($f == 0) {
                            echo '<div class="alert alert-success">安装成功！<br/>SQL成功' . $t . '句/失败' . $f . '句</div><p align="right"><a class="btn btn-block btn-primary" href="?do=5">下一步>></a></p>';
                        } else {
                            echo '<div class="alert alert-danger">安装失败<br/>SQL成功' . $t . '句/失败' . $f . '句<br/>错误信息：' . $error . '</div><p align="right"><a class="btn btn-block btn-primary" href="?do=4">点此进行重试</a></p>';
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- 5 -->
        <?php } elseif ($id == '5') { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" align="center">安装完成</h3>
                </div>
                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                         aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                        <span class="sr-only">100%</span>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    @file_put_contents($install_config['check_file'], '');
                    echo '<div class="alert alert-info"><font color="green">安装完成！管理账号和密码是:' . $install_config['admin'] . '/' . $install_config['password'] . '</font><br/><br/><a href="../">>>网站首页</a>｜<a href="../admin/">>>后台管理</a><hr/>更多设置选项请登录后台管理进行修改。<br/><br/><font color="#FF0033">如果你的空间不支持本地文件读写，请自行在install/ 目录建立 ' . $install_config['check_file'] . ' 文件！</font></div>';
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</div>
</body>
</html>
