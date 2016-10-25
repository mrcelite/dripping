<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `app_admin_group` (
  `group_id` SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '组id',
  `group_name` VARCHAR(20) NOT NULL COMMENT '组名称',
  `group_content` VARCHAR(64) NOT NULL COMMENT '组描述',
  PRIMARY KEY (`group_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='管理员组';
INSERT INTO `app_admin_group` VALUES (1,'超级管理员','拥有所有权限'),(2,'超级管理员（无删除）','超级管理员（无删除）');

CREATE TABLE `app_admin_user_group` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,	
  `user_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员id',
  `group_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '组id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_group` (`user_id`,`group_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='管理员对应的组';
INSERT INTO `app_admin_user_group` VALUES (NULL,1,1);

CREATE TABLE `app_admin_group_module` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '组id',
  `module_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '模块id',
  `permission_container` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '权限集',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_group_module` (`group_id`,`module_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='管理员组功能权限设定表';
INSERT INTO `app_admin_group_module`(`group_id`,`module_id`,`permission_container`) VALUES (1,3,31),(1,4,31),(1,5,31),(1,6,31),(1,12,31),(1,11,31),(1,14,31),(1,16,31),(1,9,31),(1,17,31),(1,19,31),(1,20,31);


CREATE TABLE `app_admin_user` (
  `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `user_name` VARCHAR(40) NOT NULL COMMENT '管理员登录名',
  `real_name` VARCHAR(10) NOT NULL COMMENT '真实姓名',
  `password` VARCHAR(32) NOT NULL COMMENT '密码',
  `mobile_phone` CHAR(11) NOT NULL DEFAULT '' COMMENT '联系电话',
  `is_forbidden` TINYINT(1) UNSIGNED NOT NULL DEFAULT '2' COMMENT '是否被禁用，1是',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '数据生成时间',
  `last_signin_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后一次登录时间',
  `last_signin_ip` VARCHAR(11) NOT NULL DEFAULT '' COMMENT '最后一次登录ip',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `idx_user_name` (`user_name`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='后台管理员表';


INSERT INTO `app_admin_user`(`user_name`,`real_name`,`password`,`mobile_phone`) VALUES ('marongcai','马荣财','e10adc3949ba59abbe56e057f20f883e','15012345678');


CREATE TABLE `app_admin_module` (
  `module_id` SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_name` VARCHAR(20) NOT NULL COMMENT '功能名称',
  `module_content` VARCHAR(64) DEFAULT NULL COMMENT '功能说明',
  `parent_module_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父节点id',
  `module_route` VARCHAR(64) NOT NULL DEFAULT '#' COMMENT '功能对应的url',
  `module_level` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '模块分级',
  `module_weight` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`module_id`),
  KEY `parent_module_id` (`parent_module_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='后台模块管理';

INSERT INTO `app_admin_module` (`module_id`, `module_name`, `module_content`, `parent_module_id`, `module_route`, `module_weight`, `module_level`)
VALUES
	(2,'管理员','管理员分组、权限管理',0,'#',1,1),
	(3,'管理员列表','管理员列表',2,'manager',1,2),
	(4,'功能列表','功能列表',2,'module',2,2),
	(5,'分组权限设置','分组权限设置',2,'mgroup',3,2),
	(6,'管理员分组设置','管理员分组设置',2,'primary',4,2);
	(7,'壁纸管理','壁纸管理',0,'#',1,1),
	(8,'壁纸','壁纸列表',7,'/manager/wall',2,2),
	(9,'壁纸类别','类别列表',7,'/manager/category',3,2),
	(10,'壁纸影集','影集列表',7,'/manager/album',4,2);

CREATE TABLE `app_album` (
  `id` SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `album_name` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '专辑名',
  `album_word` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '专辑唯美描述',
  `album_cover` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '专辑封面地址',
  `is_recommend` TINYINT(1) UNSIGNED NOT NULL DEFAULT '2' COMMENT '是否推荐专辑,1推荐',
  `is_show` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否推荐专辑,1展示',
  `view_count` INT (10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '专辑浏览次数',
  `favorite_count` INT (10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞次数',
  `collect_count` INT (10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modified_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='专集' ;

CREATE TABLE `app_album_view_user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `album_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '专辑id',
  `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '浏览人id',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_album_user`( `album_id`,`user_id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='专集浏览记录' ;

CREATE TABLE `app_wallpaper_category` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(10) NOT NULL DEFAULT '0' COMMENT '分类名称',
  `parent_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分级id',
  `category_level` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '等级,1是顶级,2是顶级的下属',
  `is_show` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否推荐专辑,1展示',
  `is_recommend` TINYINT(1) UNSIGNED NOT NULL DEFAULT '2' COMMENT '是否推荐专辑,1推荐',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modified_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='壁纸分类' ;

CREATE TABLE `app_wallpaper` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `warming_word` VARCHAR(64) NOT NULL DEFAULT '0' COMMENT '壁纸寄语',
  `wall_url` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '壁纸地址',
  `view_count` INT (10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `down_count` INT (10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下载次数',
  `favorite_count` INT (10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点赞次数',
  `collect_count` INT (10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `is_show` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否推荐专辑,1展示',
  `is_recommend` TINYINT(1) UNSIGNED NOT NULL DEFAULT '2' COMMENT '是否推荐专辑,1推荐',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modified_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='壁纸分类';

CREATE TABLE `app_wallpaper_view_user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `wall_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '壁纸id',
  `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '浏览人id',
  `view_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1浏览2点赞3下载',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_wall_user`( `wall_id`,`user_id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='壁纸浏览记录' ;

CREATE TABLE `app_wallpaper_album_relationship` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `wall_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '壁纸id',
  `album_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '专辑id',
  PRIMARY KEY (`id`),
  KEY `idx_wall_album`( `wall_id`,`album_id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='专辑与壁纸关系表' ;

CREATE TABLE `app_wallpaper_category_relationship` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `wall_id` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '壁纸id',
  `category_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '专辑id',
  PRIMARY KEY (`id`),
  KEY `idx_wall_category_id`( `wall_id`,`category_id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='壁纸类别关系表' ;

SQL;
        DB::unprepared($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
