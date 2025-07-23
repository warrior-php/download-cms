/**
* Theme: Lunoz - Responsive Bootstrap 5 Admin Dashboard
* Author: Myra Studio
* Module/App: Theme Config Js
*/

class Config {

    adjustLayout() {
        var self = this;

        const html = document.getElementsByTagName("html")[0];
        if (window.innerWidth <= 1200) {
            html.setAttribute("data-sidebar-size", "full");
        }
    }

    initWindowSize() {
        var self = this;
        window.addEventListener('resize', function (e) {
            self.adjustLayout();
        })
    }

    init() {
        this.adjustLayout();
        this.initWindowSize();
    }
}

new Config().init();