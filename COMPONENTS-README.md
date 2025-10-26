# Bima Smile Dental Clinic - Component System

## Overview
This project now uses a reusable component system to maintain consistency across all pages and make updates easier. You'll no longer need to edit the same header, footer, or floating icons across multiple files.

## Components Structure

```
components/
├── header.html          # Main navigation and header
├── footer.html          # Footer with contact info and links
├── floating-social.html # Floating social media icons
└── overlay-menu.html    # Mobile overlay menu
```

## How It Works

### 1. Component Files
Each component is stored as a separate HTML file in the `components/` folder:

- **`header.html`** - Contains the main navigation, logo, and "Book Appointment" button
- **`footer.html`** - Contains contact information, links, and social media icons
- **`floating-social.html`** - Contains the small drawable floating social icons with CSS
- **`overlay-menu.html`** - Contains the mobile slide-out menu

### 2. Component Loader
The `js/component-loader.js` file automatically loads these components into your pages.

### 3. Using Components in Pages

#### Method 1: Automatic Loading (Recommended)
Add placeholder divs to your HTML pages:

```html
<!-- In your page HTML -->
<div id="header-placeholder"></div>

<!-- Your page content here -->

<div id="footer-placeholder"></div>

<!-- Include the component loader at the end -->
<script src="js/component-loader.js"></script>
```

#### Method 2: Manual Loading
Load specific components programmatically:

```javascript
const loader = new ComponentLoader();

// Load header into specific element
await loader.loadComponent('header', '#my-header-div');

// Load multiple components
await loader.loadComponents([
    { name: 'header', target: '#header-placeholder', method: 'replace' },
    { name: 'footer', target: '#footer-placeholder', method: 'replace' }
]);
```

## Implementation Guide

### For New Pages
1. Copy `template-page.html` as your starting point
2. Update the page title, meta tags, and content
3. The header, footer, and floating icons will load automatically

### For Existing Pages
1. Replace the header HTML with: `<div id="header-placeholder"></div>`
2. Replace the footer HTML with: `<div id="footer-placeholder"></div>`
3. Remove the floating social icons HTML (they'll load automatically)
4. Remove the overlay menu HTML (it'll load automatically)
5. Add `<script src="js/component-loader.js"></script>` before `</body>`

## Making Global Updates

### To Update Navigation
Edit `components/header.html` - changes will appear on all pages immediately

### To Update Footer
Edit `components/footer.html` - changes will appear on all pages immediately

### To Update Contact Information
Edit both `components/footer.html` and `components/overlay-menu.html`

### To Update Social Media Links
Edit `components/floating-social.html` and `components/footer.html`

## File Structure Example

```
your-website/
├── components/
│   ├── header.html
│   ├── footer.html
│   ├── floating-social.html
│   └── overlay-menu.html
├── js/
│   ├── component-loader.js
│   └── [other js files]
├── css/
│   └── [css files]
├── images/
│   └── [image files]
├── index.html
├── about.html
├── services.html
├── contact.html
├── booking.html
└── template-page.html
```

## Benefits

✅ **Single Source of Truth** - Edit components once, update everywhere
✅ **Consistency** - All pages use identical headers and footers
✅ **Easy Maintenance** - No more editing 20+ files for simple changes
✅ **Fast Updates** - Change contact info or add menu items in seconds
✅ **Clean Code** - Pages focus on their unique content
✅ **No Build Process** - Works with plain HTML/CSS/JS

## Quick Start Checklist

1. ✅ Components created in `/components/` folder
2. ✅ Component loader script created
3. ✅ Template page created for new pages
4. 🔄 Update existing pages to use components
5. 🔄 Test all pages to ensure components load correctly

## Troubleshooting

### Components Not Loading
- Check that `js/component-loader.js` is included before `</body>`
- Verify component files exist in `components/` folder
- Check browser console for error messages
- Ensure you're serving the site through a web server (not file://)

### Styles Not Applying
- Floating social icons include their own CSS in `floating-social.html`
- Other components inherit styles from your main CSS files
- Check that CSS files are loaded before components

### Links Not Working
- Verify all relative paths in components are correct
- Update component files if you move the site to a different folder structure

## Need Help?

If you need to make changes to the components or add new ones, refer to this documentation or edit the component files directly in the `components/` folder.

---

**Remember**: After implementing this system, you'll only need to edit components once to update your entire website! 🎉