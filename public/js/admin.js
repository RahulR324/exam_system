document.addEventListener("DOMContentLoaded", function () {

    // Admin Dropdown
    const adminBtn = document.querySelector(".admin-btn");
    const adminMenu = document.getElementById("adminMenu");

    if (adminBtn) {
        adminBtn.addEventListener("click", function (e) {
            e.stopPropagation();

            adminMenu.classList.toggle("show");
            adminBtn.classList.toggle("active");
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
        if (adminMenu && !adminBtn.contains(e.target) && !adminMenu.contains(e.target)) {
            adminMenu.classList.remove("show");
            adminBtn.classList.remove("active");
        }
    });

    // Close dropdown with Escape key
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && adminMenu) {
            adminMenu.classList.remove("show");
            adminBtn.classList.remove("active");
        }
    });

    // Sidebar Toggle
    const sidebarToggle = document.querySelector('[data-lte-toggle="sidebar"]');

    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function (e) {
            e.preventDefault();
            document.body.classList.toggle("sidebar-collapse");
        });
    }

    menuItems.forEach(link => {
        if (link.getAttribute('href') === currentLocation) {
            link.classList.add('active');
        }
    });

    // Add smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';

    // Stat cards animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card, .info-card').forEach(card => {
        observer.observe(card);
    });

    // Add fade-in animation to alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.animation = 'slideInDown 0.4s ease';
    });

    // Enhanced dropdown with keyboard navigation
    const dropdownLinks = document.querySelectorAll('.dropdown-content a');
    let currentFocusIndex = -1;

    function focusDropdownItem(index) {
        dropdownLinks.forEach(link => link.classList.remove('active'));
        if (index >= 0 && index < dropdownLinks.length) {
            dropdownLinks[index].focus();
            dropdownLinks[index].classList.add('active');
            currentFocusIndex = index;
        }
    }

    adminBtn.addEventListener('keydown', function (e) {
        if (adminMenu.classList.contains('show')) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                focusDropdownItem(currentFocusIndex + 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                focusDropdownItem(currentFocusIndex - 1);
            }
        }
    });

});

// Add fade-in animation keyframe if not already defined
if (!document.querySelector('style[data-animation="fade-in"]')) {
    const style = document.createElement('style');
    style.setAttribute('data-animation', 'fade-in');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
}

document.getElementById('courseSearch').addEventListener('keyup', function() {

    let value = this.value.toLowerCase();

    document.querySelectorAll('.course-item').forEach(card => {

        let course = card.dataset.course;

        if(course.includes(value)){
            card.style.display = '';
        }else{
            card.style.display = 'none';
        }

    });
});

function toggleMenu(button)
{
    document.querySelectorAll('.menu-dropdown').forEach(menu => {
        if(menu !== button.nextElementSibling){
            menu.classList.remove('show');
        }
    });

    button.nextElementSibling.classList.toggle('show');
}

document.addEventListener('click', function(e){

    if(!e.target.closest('.card-menu')){
        document.querySelectorAll('.menu-dropdown')
            .forEach(menu => menu.classList.remove('show'));
    }

});

