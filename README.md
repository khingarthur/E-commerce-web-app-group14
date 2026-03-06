# E-commerce-web-app-group14

# Group Members

Abdul Latif Zakiya - 052441360094
Arthur Frederick - 052441360186
Noah Krah Dompee - 052441360189
Zakari Mumuni - 052441360293
Amoako Angela Blay - 052441360093
Justice owusu achiaw afriyie - 052441360026
Inusah Mubarick - 052441360182


# Brief Project Report

## Project Title
ShopEase: A Simple E-commerce Web Application

## Features Implemented
ShopEase is an e-commerce web application designed to demonstrate online shopping functionality using HTML, CSS, and PHP sessions.

## Key features implemented include:

- User authentication system
  - User registration and login pages
  - Session-based authentication for protected pages
  - Logout functionality

- Role-based access
  - General users can browse products and manage their cart
  - Admin-only access for dashboard

- Product browsing and shopping
  - Public shop page for browsing available products
  - Product listing (in cart) is rendered from product storage in session
  - Add-to-cart actions with immediate feedback messages

- Cart management
  - Add products to cart
  - Increase/decrease quantity
  - Remove products from cart
  - Dynamic cart total calculation

- Checkout feedback flow
  - Checkout action from cart page
  - Success message after checkout with order details (order ID, item count, total, and item summary)
  - Empty-cart functionality implemented with user-friendly error messaging

- UI consistency updates
  - Different header behavior for logged-in vs guest shop experience
  - Footer alignment and style consistency across key pages (`index`, `about`, `contact`, `shop`, `cart`)
  - Responsive styling adjustments for improved readability

## Challenges Encountered
Several practical challenges were encountered during implementation and refinement:

- **Maintaining consistent layout across pages**
  Different pages used slightly different CSS structures, which caused mismatched footers and spacing. This was resolved by aligning shared styles and removing conflicting CSS rules.

- **Session-based state handling**
  Managing cart state, flash messages, and login state required careful session logic. A Post/Redirect/Get pattern was used to avoid duplicate submissions and display one-time success/error messages cleanly.

- **Conditional navigations**
  The shop page needed to behave differently for authenticated and unauthenticated users. Header rendering had to be made conditional without breaking existing guest navigation.

- **Balancing UX simplicity with functionality**
  The project required simple interfaces while still showing meaningful details.


Overall, ShopEase now supports a complete basic shopping experience, add to cart, manage quantities, and checkout—with session-based authentication and improved UI consistency.
