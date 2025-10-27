/**
 * Component Loader Utility
 * Loads reusable HTML components into pages
 */

class ComponentLoader {
    constructor() {
        this.componentBase = 'components/';
    }

    /**
     * Load a component and insert it into a target element
     * @param {string} componentName - Name of the component file (without .html)
     * @param {string} targetSelector - CSS selector for target element
     * @param {string} insertMethod - 'replace', 'prepend', or 'append'
     */
    async loadComponent(componentName, targetSelector, insertMethod = 'replace') {
        try {
            const response = await fetch(`${this.componentBase}${componentName}.html`);
            
            if (!response.ok) {
                throw new Error(`Failed to load component: ${componentName}`);
            }

            const html = await response.text();
            const targetElement = document.querySelector(targetSelector);

            if (!targetElement) {
                console.error(`Target element not found: ${targetSelector}`);
                return;
            }

            switch (insertMethod) {
                case 'prepend':
                    targetElement.insertAdjacentHTML('afterbegin', html);
                    break;
                case 'append':
                    targetElement.insertAdjacentHTML('beforeend', html);
                    break;
                case 'replace':
                default:
                    targetElement.innerHTML = html;
                    break;
            }

            console.log(`Component loaded: ${componentName}`);
        } catch (error) {
            console.error('Error loading component:', error);
        }
    }

    /**
     * Load multiple components
     * @param {Array} components - Array of component configs
     */
    async loadComponents(components) {
        const promises = components.map(config => 
            this.loadComponent(config.name, config.target, config.method)
        );
        
        await Promise.all(promises);
    }

    /**
     * Initialize common page components
     */
    async initializePageComponents() {
        const commonComponents = [
            { name: 'header', target: '#header-placeholder', method: 'replace' },
            { name: 'footer', target: '#footer-placeholder', method: 'replace' },
            { name: 'floating-social', target: 'body', method: 'append' },
            { name: 'overlay-menu', target: 'body', method: 'append' }
        ];

        await this.loadComponents(commonComponents);
    }
}

// Initialize component loader when DOM is ready
document.addEventListener('DOMContentLoaded', async function() {
    const loader = new ComponentLoader();
    
    // Auto-load components if placeholders exist
    if (document.querySelector('#header-placeholder')) {
        // Determine which header to load based on the current page
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        const headerType = currentPage === 'index.html' ? 'header-homepage' : 'header-light';
        
        await loader.loadComponent(headerType, '#header-placeholder');
        
        // Initialize mobile navigation immediately after header loads
        setTimeout(() => {
            console.log('Initializing mobile navigation...'); // Debug log
            initMobileMenuHandler();
            initMobileNavEnhancements();
        }, 100);
    }
    
    if (document.querySelector('#footer-placeholder')) {
        await loader.loadComponent('footer', '#footer-placeholder');
    }
    
    // Always load floating social icons and overlay menu
    await loader.loadComponent('floating-social', 'body', 'append');
    await loader.loadComponent('overlay-menu', 'body', 'append');
    
    // Fallback mobile navigation initialization
    setTimeout(() => {
        if (!document.querySelector('#menu-btn').onclick && document.querySelector('#menu-btn')) {
            console.log('Fallback mobile navigation initialization...'); // Debug log
            initMobileMenuHandler();
        }
    }, 1000);
});

// Additional fallback - try to initialize mobile nav when window loads
window.addEventListener('load', function() {
    setTimeout(() => {
        if (document.querySelector('#menu-btn') && window.innerWidth <= 993) {
            console.log('Window load mobile navigation initialization...'); // Debug log
            initMobileMenuHandler();
        }
    }, 500);
});

// Initialize Mobile Menu Handler (replaces the one in designesia.js for components)
function initMobileMenuHandler() {
    let mobile_menu_show = 0;
    
    // Remove any existing click handlers to prevent duplicates
    jQuery('#menu-btn').off('click');
    
    // Add the mobile menu click handler
    jQuery('#menu-btn').on("click", function() {
        console.log('Mobile menu button clicked!'); // Debug log
        
        if (mobile_menu_show === 0) {
            jQuery('header').addClass('menu-open');
            jQuery('header').addClass('header-mobile'); // Ensure header-mobile class is present
            mobile_menu_show = 1;
            jQuery(this).addClass("menu-open");
            console.log('Menu opened'); // Debug log
        } else {
            jQuery('header').removeClass('menu-open');
            mobile_menu_show = 0;
            jQuery(this).removeClass("menu-open");
            
            // Close all open submenus when main menu closes
            document.querySelectorAll('header #mainmenu > li.submenu-open').forEach(item => {
                item.classList.remove('submenu-open');
            });
            
            console.log('Menu closed'); // Debug log
        }
    });
    
    // Ensure header gets mobile class on smaller screens
    function checkMobileHeader() {
        if (window.innerWidth <= 993) {
            jQuery('header').addClass('header-mobile');
            jQuery('#menu-btn').show();
        } else {
            jQuery('header').removeClass('header-mobile');
            jQuery('header').removeClass('menu-open');
            jQuery('#menu-btn').removeClass('menu-open');
            mobile_menu_show = 0;
            
            // Close all submenus when switching to desktop
            document.querySelectorAll('header #mainmenu > li.submenu-open').forEach(item => {
                item.classList.remove('submenu-open');
            });
        }
    }
    
    // Check on load and resize
    checkMobileHeader();
    jQuery(window).on('resize', checkMobileHeader);
}

// Mobile Navigation Enhancements
function initMobileNavEnhancements() {
    // Add click handlers for submenu toggles
    document.querySelectorAll('header #mainmenu > li').forEach(item => {
        const link = item.querySelector('a');
        const submenu = item.querySelector('ul');
        
        if (submenu) {
            item.classList.add('has-submenu');
            
            // Add click handler for mobile submenu toggle
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 993) {
                    e.preventDefault();
                    
                    // Toggle this submenu
                    item.classList.toggle('submenu-open');
                    
                    // Close other submenus
                    document.querySelectorAll('header #mainmenu > li.submenu-open').forEach(openItem => {
                        if (openItem !== item) {
                            openItem.classList.remove('submenu-open');
                        }
                    });
                    
                    console.log('Submenu toggled for:', link.textContent); // Debug log
                }
            });
        }
    });
    
    // Prevent body scroll when mobile menu is open
    const menuBtn = document.getElementById('menu-btn');
    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            setTimeout(() => {
                const header = document.querySelector('header');
                if (header && header.classList.contains('menu-open')) {
                    document.body.classList.add('mobile-menu-open');
                } else {
                    document.body.classList.remove('mobile-menu-open');
                }
            }, 100);
        });
    }
    
    // Close mobile menu when window is resized to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 993) {
            const header = document.querySelector('header');
            const menuBtn = document.getElementById('menu-btn');
            
            if (header && header.classList.contains('menu-open')) {
                header.classList.remove('menu-open');
                if (menuBtn) {
                    menuBtn.classList.remove('menu-open');
                }
                document.body.classList.remove('mobile-menu-open');
            }
            
            // Close all open submenus
            document.querySelectorAll('header #mainmenu > li.open').forEach(item => {
                item.classList.remove('open');
            });
        }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        const header = document.querySelector('header');
        const menuBtn = document.getElementById('menu-btn');
        
        if (window.innerWidth <= 993 && 
            header && header.classList.contains('menu-open') &&
            !header.contains(e.target) && 
            e.target !== menuBtn) {
            
            header.classList.remove('menu-open');
            if (menuBtn) {
                menuBtn.classList.remove('menu-open');
            }
            document.body.classList.remove('mobile-menu-open');
        }
    });
}

// Export for manual use
window.ComponentLoader = ComponentLoader;