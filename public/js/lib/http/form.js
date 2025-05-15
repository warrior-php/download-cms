(function ($) {
    /**
     * 表单验证 or 提交
     * @param options
     * @param callback
     * @returns {*}
     */
    $.fn.isForm = function (options = {}, callback = null) {
        let param = {
            type: 'POST', timeout: 10000, datatype: "JSON", ignore: ':hidden', headers: {
                "X-SOFT-NAME": "HOYM++ SaaS Framework", 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        };

        // 如果传递了 options 参数，合并到 param 中
        if (typeof options !== 'function') {
            param = $.extend(param, options);
        }

        return this.each(function () {
            $(this).validator({
                ignore: ':hidden', // 忽略所有隐藏的字段
                stopOnError: true, // 第一个错误发生时停止验证
                focusCleanup: true, // 验证成功后清除失去焦点的错误提示
                valid: function (form) {
                    this.holdSubmit(true);
                    NProgress.start();
                    if (param.type.toUpperCase() === 'POST') {
                        $(form).ajaxSubmit({
                            type: param.type, dataType: param.datatype, headers: param.headers, timeout: param.timeout, async: true, success: (rel) => {
                                NProgress.done();
                                switch (rel.code) {
                                    case 200: // 提示并跳转或静默
                                        parent.layer.toast(rel.message, {'skin': 'success'});
                                        break;
                                    case 204: // 静默待处理
                                        break;
                                    case 302: // 跳转
                                        if (rel.hasOwnProperty('is_parent') && rel.is_parent === true) {
                                            rel.hasOwnProperty('url') ? parent.window.location.replace(rel.url) : parent.window.location.reload();
                                        } else {
                                            rel.hasOwnProperty('url') ? window.location.replace(rel.url) : window.location.reload();
                                        }
                                        break;
                                    default: // 失败
                                        parent.layer.toast(rel.msg, {skin: 'warning'});
                                        break;
                                }
                                "function" == typeof options ? options(rel) : "function" == typeof callback && callback(rel);
                                this.holdSubmit(false);
                            }, error: (rel) => {
                                this.holdSubmit(false);
                            }
                        })
                    } else {
                        form.submit();
                        this.holdSubmit(false);
                    }
                },
            });
        });
    };
})(jQuery);