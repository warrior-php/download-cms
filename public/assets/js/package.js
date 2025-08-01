// 定义包路径
const js = document.scripts;
const basePath = js[js.length - 1].src.substring(0, js[js.length - 1].src.lastIndexOf("/") + 1);
const lang = document.documentElement.lang;

// 定义加载核心库
Do.setConfig('coreLib', [basePath + 'jquery.min.js']);

// 基础
Do.add('base', {path: basePath + 'common.js', type: 'js', requires: ['nprogress']});

// 进度条
Do.add('nprogress_css', {path: basePath + 'libs/nprogress/nprogress.css', type: 'css'});
Do.add('nprogress', {path: basePath + 'libs/nprogress/nprogress.js', type: 'js', requires: ['nprogress_css']});

// 弹出层
Do.add('layer_lib', {path: basePath + 'libs/layer/layer.js', type: 'js'});
Do.add('layer', {path: basePath + 'libs/layer/layer.extend.js', type: 'js', requires: ['layer_lib']});

// 表单
Do.add('validator', {path: basePath + 'libs/nice-validator/jquery.validator.js?local=' + lang, type: 'js'});
Do.add('jquery.form', {path: basePath + 'libs/http/jquery.form.js', type: 'js'});
Do.add('form', {path: basePath + 'libs/http/form.js', type: 'js', requires: ['nprogress', 'layer', 'jquery.form', 'validator']});