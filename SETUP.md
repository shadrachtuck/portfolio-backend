# Mishap Creative Works Portfolio Setup Guide

This guide will help you set up the headless WordPress + React portfolio site for Mishap Creative Works.

## Prerequisites

- WordPress installation (Local by Flywheel or similar)
- Node.js and npm installed
- Basic knowledge of WordPress and React

## WordPress Setup

### 1. Install Required Plugins

You need to install the following WordPress plugins:

1. **WPGraphQL** - Enables GraphQL API for WordPress
   - Download from: https://wordpress.org/plugins/wp-graphql/
   - Or install via WordPress admin: Plugins > Add New > Search "WPGraphQL"

2. **Advanced Custom Fields (ACF)** - For custom fields
   - Download from: https://wordpress.org/plugins/advanced-custom-fields/
   - Or install via WordPress admin: Plugins > Add New > Search "Advanced Custom Fields"

3. **WPGraphQL for Advanced Custom Fields** - Connects ACF to GraphQL
   - Download from: https://github.com/wp-graphql/wp-graphql-acf
   - Or install via WordPress admin if available

### 2. Activate the Custom Plugin

The custom plugin is located at:
`app/public/wp-content/plugins/mishap-creative-works/`

1. Go to WordPress Admin > Plugins
2. Find "Mishap Creative Works Portfolio"
3. Click "Activate"

This plugin will:
- Create custom post types: Concerts, Design Projects, Web Projects
- Register ACF fields for each post type
- Enable GraphQL support

### 3. Configure WPGraphQL

1. Go to WordPress Admin > GraphQL > Settings
2. Ensure GraphQL is enabled
3. Note the GraphQL endpoint URL (typically: `http://yoursite.local/graphql`)

### 4. Create Content

Once the plugins are activated, you'll see new menu items:
- **Concerts** - Add concert listings with show posters
- **Design Projects** - Add design portfolio items
- **Web Projects** - Add web development portfolio items

## React Frontend Setup

### 1. Navigate to Frontend Directory

```bash
cd portfolio-fontend
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Configure Environment Variables

Create a `.env` file in the `portfolio-fontend` directory:

```env
VITE_WP_GRAPHQL_URL=http://localhost:10004/graphql
```

**Important:** Update the URL to match your WordPress installation's GraphQL endpoint.

### 4. Start Development Server

```bash
npm run dev
```

The frontend will be available at `http://localhost:5173` (or the port shown in your terminal).

## Features

### 3D Interactive Models

- **Concert Model** - Click to navigate to concerts page
- **Easel Model** - Click to navigate to design projects
- **Desk Model** - Click to navigate to web projects

Each model has:
- Hover effects with glow/outline
- Label display on hover
- Click to navigate to respective pages
- Triggers skateboarder animation on hover

### Pages

- **Home** - Interactive 3D scene with models
- **Concerts** - Displays upcoming and past concerts with show posters
- **Projects** - Filterable gallery of design and web projects
- **About** - About/resume page
- **Contact** - Contact page

## Troubleshooting

### GraphQL Errors

If you see GraphQL errors:

1. Ensure WPGraphQL plugin is installed and activated
2. Check that the GraphQL endpoint URL in `.env` matches your WordPress installation
3. Verify ACF fields are properly configured
4. Check browser console for specific error messages

### ACF Fields Not Showing in GraphQL

1. Ensure WPGraphQL for Advanced Custom Fields plugin is installed
2. Check ACF field group settings - ensure "Show in GraphQL" is enabled
3. Clear WordPress cache if using a caching plugin

### Models Not Interactive

1. Ensure all dependencies are installed: `npm install`
2. Check browser console for JavaScript errors
3. Verify React Three Fiber is working by checking if models render

## Next Steps

1. Create content in WordPress (concerts, design projects, web projects)
2. Customize the 3D model positions if needed in `src/models/World.jsx`
3. Adjust styling and colors to match your brand
4. Test all navigation and interactions
5. Deploy to production

## Support

For issues or questions, please refer to:
- WPGraphQL Documentation: https://www.wpgraphql.com/
- React Three Fiber Documentation: https://docs.pmnd.rs/react-three-fiber/
- Advanced Custom Fields Documentation: https://www.advancedcustomfields.com/resources/


