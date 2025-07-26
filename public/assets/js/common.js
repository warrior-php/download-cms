/**
 * 声明全局 jQuery 变量
 * 这里的 @type 注释表明 jQuery 是一个全局变量，并且类型为 jQuery 对象，HTMLElement 或任意对象。
 * @type {$|HTMLElement|*}
 */
const jQuery = $;

(function ($) {
    /**
     * 随机字符串插件
     * 通过点击元素来生成指定长度的随机字符串，并将生成的字符串填充到指定的表单元素中。
     * @param {Object} options 配置选项
     * @param {number} options.len 生成的随机字符串长度（默认 43）
     * @param {HTMLElement} options.dom 随机字符串填充到的目标元素（默认填充到当前点击的元素）
     * @param {number} options.min 最小随机长度（默认情况下不使用，代码中实际未使用该属性）
     */
    $.fn.randomWord = function (options) {
        // 合并默认选项和传入的选项
        options = $.extend({len: 43, dom: this}, options);
        // 为目标元素绑定点击事件
        $(this).on('click', function (e) {
            let str = "", range = options.min;
            // 定义生成随机字符串的字符集（包含数字和大小写字母）
            const arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            // 如果传入了长度参数，则随机生成一个在指定长度范围内的数字
            if (options.len) {
                // 根据传入的 len 值计算范围，默认生成的随机字符串长度为 options.len
                range = Math.round(Math.random() * (options.len - options.len)) + options.len;
            }
            // 生成随机字符串
            let pos;
            for (let i = 0; i < range; i++) {
                pos = Math.round(Math.random() * (arr.length - 1)); // 从字符集中随机取一个字符
                str += arr[pos];  // 拼接到字符串中
            }
            // 将生成的随机字符串填充到目标元素中（如文本框）
            $(options.dom).val(str);
        });
    };

    /**
     * toast 回调方法
     * @param msg 提示的消息
     * @param options  layer.toast参数
     * @param callback 回调
     */
    $.fn.toast = function (msg, options, callback) {
        parent.layer.toast(msg, options);
        if (options && options.time) {
            setTimeout(function () {
                callback && callback();
            }, options.time);
        }
    }
})(jQuery);
