document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menu-toggle');
    const overlay = document.getElementById('overlay');
    const navLinks = document.querySelectorAll('.nav-link');


    document.querySelectorAll('.nav-header').forEach(header => {
        header.classList.add('collapsed');
    });

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }

    function showSection(id, updateHistory = true) {
        window.scrollTo(0, 0);
        document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
        const target = document.getElementById(id);
        if (target) target.classList.add('active');
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        const link = document.querySelector(`.nav-link[href="#${id}"]`);
        if (link) link.classList.add('active');
        if (updateHistory && window.history.pushState) {
            window.history.pushState({ path: `#${id}` }, '', `#${id}`);
        }
    }

    function toggleMenu(header) {
        const sub = header.nextElementSibling;
        if (!sub || !sub.classList.contains('sub-menu')) return;
        const isOpen = sub.classList.contains('open');
        if (!isOpen) {
            document.querySelectorAll('.sub-menu.open').forEach(sm => {
                sm.classList.remove('open');
                sm.style.maxHeight = null;
                sm.previousElementSibling.classList.add('collapsed');
            });
            sub.classList.add('open');
            header.classList.remove('collapsed');
            sub.style.maxHeight = sub.scrollHeight + "px";
        } else {
            sub.classList.remove('open');
            header.classList.add('collapsed');
            sub.style.maxHeight = null;
        }
    }

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    });

    overlay.addEventListener('click', closeSidebar);

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const id = link.getAttribute('href').substring(1);
            showSection(id);
            if (window.innerWidth < 1024) closeSidebar();
        });
    });

    document.querySelectorAll('.nav-header').forEach(header => {
        header.addEventListener('click', () => toggleMenu(header));
    });

    document.querySelectorAll('.copy-button').forEach(button => {
        button.addEventListener('click', function () {
            const preElement = this.closest('.code-block-container').querySelector('pre');
            if (preElement) {
                navigator.clipboard.writeText(preElement.innerText).then(() => {
                    const originalText = this.innerText;
                    this.innerText = 'کپی شد!';
                    this.disabled = true;
                    setTimeout(() => {
                        this.innerText = originalText;
                        this.disabled = false;
                    }, 2000);
                });
            }
        });
    });

    const currentHash = window.location.hash.substring(1);
    const isPhpPage = document.body.classList.contains('theme-php');
    const defaultSectionId = isPhpPage ? 'intro_php' : 'intro';
    const targetSectionId = currentHash || defaultSectionId;
    showSection(targetSectionId, false);
    const activeLink = document.querySelector(`.nav-link[href="#${targetSectionId}"]`);
    if (activeLink) {
        const parentSubMenu = activeLink.closest('.sub-menu');
        if (parentSubMenu) toggleMenu(parentSubMenu.previousElementSibling);
    } else {
        const firstHeader = document.querySelector('.nav-header');
        if (firstHeader) toggleMenu(firstHeader);
    }
});