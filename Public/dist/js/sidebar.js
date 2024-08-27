document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const main = document.querySelector('.main');

    function adjustLayout() {
        if (window.innerWidth <= 757) {
            sidebar.classList.add('collapsed');
            main.classList.add('expanded');
            sidebar.style.transform = 'translateX(-100%)';
        } else {
            sidebar.classList.remove('collapsed');
            main.classList.remove('expanded');
            sidebar.style.transform = 'translateX(0)';
        }
    }

    adjustLayout();
    window.addEventListener('resize', adjustLayout);

    sidebarToggle.addEventListener('click', function() {
        if (window.innerWidth <= 757) {
            sidebar.style.transition = 'transform 0.35s ease-in-out';
            main.style.transition = 'margin-left 0.35s ease-in-out';
            
            if (sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                sidebar.style.transform = 'translateX(-100%)';
                main.style.marginLeft = '0';
            } else {
                sidebar.classList.add('show');
                sidebar.style.transform = 'translateX(0)';
                main.style.marginLeft = '264px';
            }
        } else {
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('expanded');
        }
    });
});