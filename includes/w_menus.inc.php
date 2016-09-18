<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 */

$_menus = array();

// ----- 管理中心 -----
$_menus['admin']                            = array('name'=>'后台',        'display'=>1);

$_menus['admin/home']                       = array('name'=>'后台',     'display'=>1);
$_menus['admin/home/index']                 = array('name'=>'默认页',   'display'=>1);

$_menus['admin/system']                     = array('name'=>'系统管理',     'display'=>1);
$_menus['admin/system/config']              = array('name'=>'网站配置',     'display'=>1);
$_menus['admin/system/configupdate']        = array('name'=>'网站配置更新', 'display'=>0);
$_menus['admin/system/adminlog']            = array('name'=>'管理日志',     'display'=>1);
$_menus['admin/system/adminlogdelete']      = array('name'=>'管理日志删除', 'display'=>0);
$_menus['admin/system/errorlog']            = array('name'=>'错误日志',     'display'=>1);
$_menus['admin/system/errorlogread']        = array('name'=>'错误日志查看', 'display'=>0);
$_menus['admin/system/errorlogdelete']      = array('name'=>'错误日志删除', 'display'=>0);
$_menus['admin/system/cleardata']           = array('name'=>'清理数据',     'display'=>1);

$_menus['admin/user']                       = array('name'=>'用户管理',     'display'=>1);
$_menus['admin/user/role']                  = array('name'=>'角色管理',     'display'=>1);
$_menus['admin/user/roleadd']               = array('name'=>'角色添加',     'display'=>0);
$_menus['admin/user/roleupdate']            = array('name'=>'角色更新',     'display'=>0);
$_menus['admin/user/roledelete']            = array('name'=>'角色删除',     'display'=>0);
$_menus['admin/user/rolepermissioncontrol'] = array('name'=>'角色权限控制', 'display'=>1);
$_menus['admin/user/rolepermissionassign']  = array('name'=>'角色权限分配', 'display'=>0);
$_menus['admin/user/rolepermissionupdate']  = array('name'=>'角色权限更新', 'display'=>0);
$_menus['admin/user/list']                  = array('name'=>'用户列表',     'display'=>1);
$_menus['admin/user/add']                   = array('name'=>'用户添加',     'display'=>1);
$_menus['admin/user/update']                = array('name'=>'用户更新',     'display'=>0);
$_menus['admin/user/delete']                = array('name'=>'用户删除',     'display'=>0);
$_menus['admin/user/self']                  = array('name'=>'个人信息修改', 'display'=>1);

$_menus['admin/article']                    = array('name'=>'文章管理',     'display'=>1);
$_menus['admin/article/categoryadd']        = array('name'=>'文章分类添加', 'display'=>1);
$_menus['admin/article/categorylist']       = array('name'=>'文章分类列表', 'display'=>1);
$_menus['admin/article/categoryupdate']     = array('name'=>'文章分类更新', 'display'=>0);
$_menus['admin/article/categorydelete']     = array('name'=>'文章分类删除', 'display'=>0);
$_menus['admin/article/add']                = array('name'=>'文章添加',     'display'=>1);
$_menus['admin/article/list']               = array('name'=>'文章列表',     'display'=>1);
$_menus['admin/article/update']             = array('name'=>'文章更新',     'display'=>0);
$_menus['admin/article/delete']             = array('name'=>'文章删除',     'display'=>0);

$_menus['admin/singlepage']                 = array('name'=>'单页管理',     'display'=>1);
$_menus['admin/singlepage/add']             = array('name'=>'单页添加',     'display'=>1);
$_menus['admin/singlepage/list']            = array('name'=>'单页列表',     'display'=>1);
$_menus['admin/singlepage/update']          = array('name'=>'单页更新',     'display'=>0);
$_menus['admin/singlepage/delete']          = array('name'=>'单页删除',     'display'=>0);

// ----- 通用应用 -----
$_menus['generals']                         = array('name'=>'通用应用',     'display'=>1);

$_menus['generals/upload']                  = array('name'=>'上传文件',     'display'=>1);
$_menus['generals/upload/upload']           = array('name'=>'图片/文件上传', 'display'=>1);

$_menus['generals/ueditor']                 = array('name'=>'UEditor编辑器', 'display'=>1);
$_menus['generals/ueditor/controller']      = array('name'=>'上传/涂鸦/远程图片抓取...', 'display'=>1);

// ----- 会员中心 -----
$_menus['member']                           = array('name'=>'会员中心',     'display'=>1);

$_menus['member/home']                      = array('name'=>'会员首页',     'display'=>1);
$_menus['member/home/index']                = array('name'=>'会员默认页',   'display'=>1);

$_menus['member/finance']                   = array('name'=>'财务信息',     'display'=>1);
$_menus['member/finance/list']              = array('name'=>'消费列表',     'display'=>1);
$_menus['member/finance/log']               = array('name'=>'充值记录',     'display'=>1);

$_menus['member/profile']                   = array('name'=>'我的资料',     'display'=>1);
$_menus['member/profile/edit']              = array('name'=>'编辑资料',     'display'=>1);
$_menus['member/profile/authentication']    = array('name'=>'认证信息',     'display'=>1);

// ----- APP api -----
$_menus['appapi']                           = array('name'=>'手机api',      'display'=>1);
$_menus['appapi/user']                      = array('name'=>'用户中心',     'display'=>1);
$_menus['appapi/user/myprofile']            = array('name'=>'个人信息',     'display'=>1);

