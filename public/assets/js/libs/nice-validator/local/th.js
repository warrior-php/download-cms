/*********************************
 * Themes, rules, and i18n support
 * Locale: Thai; ภาษาไทย
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
            digits: [/^\d+$/, "กรุณากรอกตัวเลข"],
            letters: [/^[a-z]+$/i, "กรุณากรอกตัวอักษรภาษาอังกฤษ"],
            date: [/^\d{4}-\d{2}-\d{2}$/, "กรุณากรอกวันที่ให้ถูกต้อง รูปแบบ: yyyy-mm-dd"],
            time: [/^([01]\d|2[0-3])(:[0-5]\d){1,2}$/, "กรุณากรอกเวลาให้ถูกต้อง ระหว่าง 00:00 ถึง 23:59"],
            email: [/^[\w\+\-]+(\.[\w\+\-]+)*@[a-z\d\-]+(\.[a-z\d\-]+)*\.([a-z]{2,4})$/i, "กรุณากรอกอีเมลให้ถูกต้อง"],
            url: [/^(https?|s?ftp):\/\/\S+$/i, "กรุณากรอก URL ให้ถูกต้อง"],
            qq: [/^[1-9]\d{4,}$/, "กรุณากรอกหมายเลข QQ ให้ถูกต้อง"],
            IDcard: [/^\d{6}(19|2\d)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)?$/, "กรุณากรอกหมายเลขบัตรประชาชนให้ถูกต้อง"],
            tel: [/^(?:(?:0\d{2,3}[\- ]?[1-9]\d{6,7})|(?:[48]00[\- ]?[1-9]\d{6}))$/, "กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง"],
            mobile: [/^1[3-9]\d{9}$/, "กรุณากรอกเบอร์มือถือให้ถูกต้อง"],
            zipcode: [/^\d{6}$/, "กรุณาตรวจสอบรูปแบบรหัสไปรษณีย์"],
            chinese: [/^[\u0391-\uFFE5]+$/, "กรุณากรอกตัวอักษรภาษาจีน"],
            username: [/^\w{3,12}$/, "กรุณากรอกชื่อผู้ใช้ 3-12 ตัวอักษร (ตัวเลข/ตัวอักษร/ขีดล่าง)"],
            password: [/^[\S]{6,16}$/, "กรุณากรอกรหัสผ่าน 6-16 ตัวอักษร และห้ามมีช่องว่าง"],
            accept: function (element, params) {
                if (!params) return true;
                var ext = params[0], value = $(element).val();
                return (ext === '*') || (new RegExp(".(?:" + ext + ")$", "i")).test(value) || this.renderMsg("รองรับไฟล์ที่มีนามสกุล {1} เท่านั้น", ext.replace(/\|/g, ','));
            }

        },

        // Default error messages
        messages: {
            0: "ช่องนี้", fallback: "{0} มีรูปแบบไม่ถูกต้อง", loading: "กำลังตรวจสอบ...", error: "เครือข่ายมีปัญหา", timeout: "การร้องขอหมดเวลา", required: "{0} ห้ามเว้นว่าง", remote: "{0} ถูกใช้ไปแล้ว", integer: {
                '*': "กรุณากรอกจำนวนเต็ม", '+': "กรุณากรอกจำนวนเต็มบวก", '+0': "กรุณากรอกจำนวนเต็มบวกหรือ 0", '-': "กรุณากรอกจำนวนเต็มลบ", '-0': "กรุณากรอกจำนวนเต็มลบหรือ 0"
            }, match: {
                eq: "{0} ต้องตรงกับ {1}", neq: "{0} ต้องไม่เหมือนกับ {1}", lt: "{0} ต้องน้อยกว่า {1}", gt: "{0} ต้องมากกว่า {1}", lte: "{0} ต้องไม่มากกว่า {1}", gte: "{0} ต้องไม่ต่ำกว่า {1}"
            }, range: {
                rg: "กรุณากรอกค่าระหว่าง {1} ถึง {2}", gte: "กรุณากรอกค่ามากกว่าหรือเท่ากับ {1}", lte: "กรุณากรอกค่าไม่เกิน {1}", gtlt: "กรุณากรอกค่าระหว่าง {1} ถึง {2}", gt: "กรุณากรอกค่ามากกว่า {1}", lt: "กรุณากรอกค่าน้อยกว่า {1}"
            }, checked: {
                eq: "กรุณาเลือก {1} รายการ", rg: "กรุณาเลือก {1} ถึง {2} รายการ", gte: "กรุณาเลือกอย่างน้อย {1} รายการ", lte: "กรุณาเลือกไม่เกิน {1} รายการ"
            }, length: {
                eq: "กรุณากรอก {1} อักขระ", rg: "กรุณากรอก {1} ถึง {2} อักขระ", gte: "กรุณากรอกอย่างน้อย {1} อักขระ", lte: "กรุณากรอกไม่เกิน {1} อักขระ", eq_2: "", rg_2: "", gte_2: "", lte_2: ""
            }
        }
    });

    /* Themes
     */
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