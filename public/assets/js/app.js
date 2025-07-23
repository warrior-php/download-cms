/**
* Theme: Lunoz - Responsive Bootstrap 5 Admin Dashboard
* Author: Myra Studio
* ¸ü¶àÏÂÔØ£ºhttps://www.bootstrapmb.com 
* Module/App: Layout Js
*/


class ThemeLayout {

    constructor() {
        this.html = document.getElementsByTagName('html')[0]
    }

    initComponents() {

        Waves.init();

        lucide.createIcons();

        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        const offcanvasElementList = document.querySelectorAll('.offcanvas')
        const offcanvasList = [...offcanvasElementList].map(offcanvasEl => new bootstrap.Offcanvas(offcanvasEl))

        var toastPlacement = document.getElementById("toastPlacement");
        if (toastPlacement) {
            document.getElementById("selectToastPlacement").addEventListener("change", function () {
                if (!toastPlacement.dataset.originalClass) {
                    toastPlacement.dataset.originalClass = toastPlacement.className;
                }
                toastPlacement.className = toastPlacement.dataset.originalClass + " " + this.value;
            });
        }

        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl)
        })

        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
        const alert = (message, type) => {
            const wrapper = document.createElement('div')
            wrapper.innerHTML = [
                `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                `   <div>${message}</div>`,
                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('')

            alertPlaceholder.append(wrapper)
        }

        const alertTrigger = document.getElementById('liveAlertBtn')
        if (alertTrigger) {
            alertTrigger.addEventListener('click', () => {
                alert('Nice, you triggered this alert message!', 'success')
            })
        }

        // Light/Dark Mode Toggle Button
        var themeColorToggle = document.getElementById('theme-mode');
        if (themeColorToggle) {
            themeColorToggle.addEventListener('click', function (e) {
                var html = document.documentElement;
                var currentTheme = html.getAttribute('data-bs-theme');
                var newTheme = currentTheme === 'light' ? 'dark' : 'light';
                html.setAttribute('data-bs-theme', newTheme);
                sessionStorage.setItem('themeMode', newTheme);
            });
        }

        var storedThemeMode = sessionStorage.getItem('themeMode');
        var systemDefaultMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        var themeMode = storedThemeMode || systemDefaultMode;
        document.documentElement.setAttribute('data-bs-theme', themeMode);
        sessionStorage.setItem('themeMode', themeMode);
    }

    initfullScreenListener() {
        var self = this;
        var fullScreenBtn = document.querySelector('[data-bs-toggle="fullscreen"]');

        if (fullScreenBtn) {
            fullScreenBtn.addEventListener('click', function (e) {
                e.preventDefault();
                document.body.classList.toggle('fullscreen-enable')
                if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement.webkitRequestFullscreen) {
                        document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                } else {
                    if (document.cancelFullScreen) {
                        document.cancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    }
                }
            });
        }
    }

    initFormValidation() {
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    }

    initMainMenu() {
        var self = this;

        if ($(".app-menu").length) {
            var navCollapse = $('.app-menu .menu-item .collapse');
            var navToggle = $(".app-menu li [data-bs-toggle='collapse']");
            navToggle.on('click', function (e) {
                return false;
            });

            navCollapse.on({
                'show.bs.collapse': function (event) {
                    var parent = $(event.target).parents('.collapse.show');
                    $('.app-menu .collapse.show').not(event.target).not(parent).collapse('hide');
                }
            });

            var menuLinks = document.querySelectorAll(".app-menu .menu-link");
            var pageUrl = window.location.href.split(/[?#]/)[0];

            menuLinks.forEach(function (link) {
                if (link.href == pageUrl) {
                    link.classList.add("active");
                    link.parentNode.classList.add("active");
                    link.parentNode.parentNode.parentNode.classList.add("show");
                    link.parentNode.parentNode.parentNode.parentNode.classList.add("active");
                    link.parentNode.parentNode.parentNode.parentNode.parentNode.classList.add("active");
                    link.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.classList.add("show");
                }
            });

        }

        setTimeout(function () {
            var activatedItem = document.querySelector('li.active .active');
            if (activatedItem != null) {
                var simplebarContent = document.querySelector('.main-menu .simplebar-content-wrapper');
                var offset = activatedItem.offsetTop - 300;
                if (simplebarContent && offset > 100) {
                    scrollTo(simplebarContent, offset, 600);
                }
            }
        }, 200);

        function easeInOutQuad(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        function scrollTo(element, to, duration) {
            var start = element.scrollTop, change = to - start, currentTime = 0, increment = 20;
            var animateScroll = function () {
                currentTime += increment;
                var val = easeInOutQuad(currentTime, start, change, duration);
                element.scrollTop = val;
                if (currentTime < duration) {
                    setTimeout(animateScroll, increment);
                }
            };
            animateScroll();
        }
    }

    reverseQuery(element, query) {
        while (element) {
            if (element.parentElement) {
                if (element.parentElement.querySelector(query) === element) return element
            }
            element = element.parentElement;
        }
        return null;
    }

    initSwitchListener() {
        var self = this;

        var menuToggleBtn = document.querySelector('.button-toggle-menu');
        if (menuToggleBtn) {
            menuToggleBtn.addEventListener('click', function () {
                var html = document.getElementsByTagName("html")[0];
                var size = self.html.getAttribute('data-sidebar-size');

                if (size === 'full') {
                    self.showBackdrop();
                }
                self.html.classList.toggle('sidebar-enable');
            });
        }
    }

    showBackdrop() {
        function getBrowserScrollbarWidth() {
            const dummyElement = document.createElement('div');
            dummyElement.style.width = '100px';
            dummyElement.style.height = '100px';
            dummyElement.style.overflow = 'scroll';
            document.body.appendChild(dummyElement);
            const scrollbarWidth = dummyElement.offsetWidth - dummyElement.clientWidth;
            document.body.removeChild(dummyElement);
            return scrollbarWidth;
        }

        const scrollbarWidth = getBrowserScrollbarWidth();
        const backdrop = document.createElement('div');
        backdrop.id = 'custom-backdrop';
        backdrop.classList = 'offcanvas-backdrop fade show';
        document.body.appendChild(backdrop);
        document.body.style.overflow = "hidden";
        document.body.style.paddingRight = scrollbarWidth + "px";

        const self = this;
        backdrop.addEventListener('click', function (e) {
            self.html.classList.remove('sidebar-enable');
            self.hideBackdrop();
        });
    }

    hideBackdrop() {
        var backdrop = document.getElementById('custom-backdrop');
        if (backdrop) {
            document.body.removeChild(backdrop);
            document.body.style.overflow = null;
            document.body.style.paddingRight = null;
        }
    }

    init() {
        this.initComponents();
        this.initfullScreenListener();
        this.initFormValidation();
        this.initMainMenu();
        this.initSwitchListener();
    }
}

new ThemeLayout().init();