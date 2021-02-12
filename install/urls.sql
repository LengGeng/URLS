CREATE TABLE IF NOT EXISTS `my_urls`
(
    `id`     int(10) UNSIGNED                                NOT NULL auto_increment,
    `url`    text CHARACTER SET utf8 COLLATE utf8_bin        NOT NULL,
    `code`   varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL default '',
    `alias`  varchar(40) CHARACTER SET utf8 COLLATE utf8_bin,
    `create` datetime                                        NOT NULL,
    `count`  int                                             NOT NULL default 0,
    `ip`     varchar(20) CHARACTER SET utf8 COLLATE utf8_bin,
    `ua`     varchar(256) CHARACTER SET utf8 COLLATE utf8_bin,
    PRIMARY KEY (id),
    UNIQUE KEY code (code)
)