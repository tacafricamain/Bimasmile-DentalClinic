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
    }
    
    if (document.querySelector('#footer-placeholder')) {
        await loader.loadComponent('footer', '#footer-placeholder');
    }
    
    // Always load floating social icons and overlay menu
    await loader.loadComponent('floating-social', 'body', 'append');
    await loader.loadComponent('overlay-menu', 'body', 'append');
});

// Export for manual use
window.ComponentLoader = ComponentLoader;