/*********************************
 * Themes, rules, and i18n support
 * Locale: Korean; 한국어
 *********************************/
(function (factory) {
    typeof module === "object" && module.exports ? module.exports = factory(require("jquery")) : typeof define === 'function' && define.amd ? define(['jquery'], factory) : factory(jQuery);
}(function ($) {
    /**
     * Global configuration
     */
    $.validator.config({
        // Custom rules
        rules: {
            digits: [/^\d+$/, "숫자를 입력해주세요"],
            letters: [/^[a-z]+$/i, "영문자만 입력해주세요"],
            date: [/^\d{4}-\d{2}-\d{2}$/, "유효한 날짜를 입력해주세요. 형식: yyyy-mm-dd"],
            time: [/^([01]\d|2[0-3])(:[0-5]\d){1,2}$/, "유효한 시간을 입력해주세요. 00:00~23:59"],
            email: [/^[\w\+\-]+(\.[\w\+\-]+)*@[a-z\d\-]+(\.[a-z\d\-]+)*\.([a-z]{2,4})$/i, "유효한 이메일을 입력해주세요"],
            url: [/^(https?|s?ftp):\/\/\S+$/i, "유효한 URL을 입력해주세요"],
            qq: [/^[1-9]\d{4,}$/, "유효한 QQ번호를 입력해주세요"],
            IDcard: [/^\d{6}(19|2\d)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)?$/, "올바른 주민등록번호를 입력해주세요"],
            tel: [/^(?:(?:0\d{2,3}[\- ]?[1-9]\d{6,7})|(?:[48]00[\- ]?[1-9]\d{6}))$/, "유효한 전화번호를 입력해주세요"],
            mobile: [/^1[3-9]\d{9}$/, "유효한 휴대폰 번호를 입력해주세요"],
            zipcode: [/^\d{6}$/, "우편번호 형식을 확인해주세요"],
            chinese: [/^[\u0391-\uFFE5]+$/, "중국어 문자만 입력해주세요"],
            username: [/^\w{3,12}$/, "3~12자의 영문, 숫자, 밑줄만 입력해주세요"],
            password: [/^[\S]{6,16}$/, "6~16자 비밀번호를 입력해주세요. 공백은 사용할 수 없습니다"],
            accept: function (element, params) {
                if (!params) return true;
                var ext = params[0], value = $(element).val();
                return (ext === '*') || (new RegExp(".(?:" + ext + ")$", "i")).test(value) || this.renderMsg("{1} 확장자만 허용됩니다", ext.replace(/\|/g, ','));
            }
        },

        // Default error messages
        messages: {
            0: "이 항목", fallback: "{0} 형식이 올바르지 않습니다", loading: "검증 중...", error: "네트워크 오류", timeout: "요청 시간이 초과되었습니다", required: "{0}은(는) 필수 입력입니다", remote: "{0}은(는) 이미 사용 중입니다", integer: {
                '*': "정수를 입력해주세요", '+': "양의 정수를 입력해주세요", '+0': "양의 정수 또는 0을 입력해주세요", '-': "음의 정수를 입력해주세요", '-0': "음의 정수 또는 0을 입력해주세요"
            }, match: {
                eq: "{0}과(와) {1}이(가) 일치하지 않습니다", neq: "{0}과(와) {1}은(는) 같을 수 없습니다", lt: "{0}은(는) {1}보다 작아야 합니다", gt: "{0}은(는) {1}보다 커야 합니다", lte: "{0}은(는) {1}보다 클 수 없습니다", gte: "{0}은(는) {1}보다 작을 수 없습니다"
            }, range: {
                rg: "{1}에서 {2} 사이 값을 입력해주세요", gte: "{1} 이상 값을 입력해주세요", lte: "{1} 이하 값을 입력해주세요", gtlt: "{1}에서 {2} 사이 값을 입력해주세요", gt: "{1}보다 큰 값을 입력해주세요", lt: "{1}보다 작은 값을 입력해주세요"
            }, checked: {
                eq: "{1}개를 선택해주세요", rg: "{1}~{2}개를 선택해주세요", gte: "{1}개 이상 선택해주세요", lte: "{1}개 이하로 선택해주세요"
            }, length: {
                eq: "{1}자를 입력해주세요", rg: "{1}~{2}자 사이로 입력해주세요", gte: "{1}자 이상 입력해주세요", lte: "{1}자 이하로 입력해주세요", eq_2: "", rg_2: "", gte_2: "", lte_2: ""
            }
        }
    });

    /* Themes */
    var TPL_ARROW = '<span class="n-arrow"><b>◆</b><i>◆</i></span>';
    $.validator.setTheme({
        'simple_right': {
            formClass: 'n-simple', msgClass: 'n-right'
        }, 'simple_bottom': {
            formClass: 'n-simple', msgClass: 'n-bottom'
        }, 'yellow_top': {
            formClass: 'n-yellow', msgClass: 'n-top', msgArrow: TPL_ARROW
        }, 'yellow_right': {
            formClass: 'n-yellow', msgClass: 'n-right', msgArrow: TPL_ARROW
        }, 'yellow_right_effect': {
            formClass: 'n-yellow', msgClass: 'n-right', msgArrow: TPL_ARROW, msgShow: function ($msgbox, type) {
                var $el = $msgbox.children();
                if ($el.is(':animated')) return;
                if (type === 'error') {
                    $el.css({left: '20px', opacity: 0})
                        .delay(100).show().stop()
                        .animate({left: '-4px', opacity: 1}, 150)
                        .animate({left: '3px'}, 80)
                        .animate({left: 0}, 80);
                } else {
                    $el.css({left: 0, opacity: 1}).fadeIn(200);
                }
            }, msgHide: function ($msgbox, type) {
                var $el = $msgbox.children();
                $el.stop().delay(100).show()
                    .animate({left: '20px', opacity: 0}, 300, function () {
                        $msgbox.hide();
                    });
            }
        }
    });
}));