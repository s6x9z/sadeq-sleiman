# 🎁 Hadiya

## Responsive E-Commerce Gift Shop Website

**Hadiya** is a responsive e-commerce website developed as a Web Development project at **Beirut Arab University**.

The website was designed as an online gift store where users can browse gifts for different occasions, explore individual products, view a shopping cart interface, access account pages, and learn more about the store.

The project focuses on **frontend web development, responsive design, page navigation, product presentation, and user-interface design** using HTML, CSS, and JavaScript.

---

# 📌 Project Overview

Hadiya was designed around the idea of making gift shopping easier by organizing products according to different occasions.

The website includes dedicated sections for events such as:

- ❤️ Valentine's Day
- 👶 Baby Gifts
- 🌷 Mother's Day
- 👔 Father's Day
- 🎓 Graduation
- 🎄 Christmas
- 🎂 Birthdays

Users can browse the shop, open individual product pages, view product information and pricing, explore the cart interface, and navigate between the main areas of the website.

---

# ✨ Project Highlights

- 🎁 Multi-page gift shop website
- 🛍️ Product catalog
- 🏷️ Occasion-based product categories
- 🔎 Individual product detail pages
- 🛒 Shopping cart interface
- 🔐 Login interface
- 📝 Sign-up interface
- 🏠 Responsive landing page
- ℹ️ About page
- 📞 Contact page
- 🗺️ Embedded Google Maps location
- 📱 Responsive mobile navigation
- ⭐ Product ratings
- 📰 Newsletter interface
- 🔗 Social-media links
- 🎨 Custom responsive styling

---

# 👩‍💻 My Contribution

I developed the web interface and structure of the Hadiya project.

My work included:

- Designing the overall website structure
- Developing the main landing page
- Building the product shop interface
- Organizing products by occasion
- Creating individual product pages
- Designing the shopping cart interface
- Developing the login page
- Developing the sign-up page
- Creating the About page
- Creating the Contact page
- Building the navigation system
- Implementing responsive layouts
- Developing mobile navigation behavior using JavaScript
- Styling the website using custom CSS
- Integrating product images and visual assets
- Creating promotional banners and product sections
- Adding newsletter and footer sections
- Testing navigation between website pages
- Preparing the website for different screen sizes

---

# 🏠 Home Page

The Hadiya home page acts as the main entry point to the website.

It contains:

- Main navigation
- Hero section
- Shop call-to-action
- Best-selling products
- Promotional banners
- Occasion-based collections
- Store features
- Newsletter section
- Footer navigation

The hero section introduces the store with the message:

```text
Surprise, Delight, Repeat
```

and directs users toward the shop.

---

# 🛍️ Product Catalog

The Shop page contains products organized into different occasion categories.

Examples include:

```text
Valentine's Day
Baby Gifts
Mother's Day
Father's Day
Graduation
Christmas
Birthday
```

Each product card presents information such as:

- Product image
- Product name
- Star rating
- Price
- Shopping-cart icon

Some products also link to dedicated detail pages.

---

# 🎁 Occasion-Based Shopping

One of the key design ideas behind Hadiya was organizing products according to the occasion they were intended for.

The homepage contains promotional sections that link users directly to relevant parts of the shop.

A simplified navigation flow looks like:

```text
Home Page
    │
    ├── Valentine's Day
    │
    ├── Baby Gifts
    │
    ├── Mother's Day
    │
    ├── Father's Day
    │
    ├── Graduation
    │
    ├── Christmas
    │
    └── Birthday
            │
            ▼
        Shop Section
```

This allows customers to browse gifts based on the event they are shopping for.

---

# 🔎 Product Detail Pages

The project contains multiple dedicated product pages.

A product page includes:

- Product image
- Product category
- Product name
- Price
- Quantity field
- Add-to-cart interface
- Product description
- Featured product recommendations

Example structure:

```text
Product Image

Product Category

Product Name

Price

Quantity

[ Add to Cart ]

Product Details
```

The project contains dedicated pages such as:

```text
sproduct.html
sproduct2.html
sproduct3.html
sproduct4.html
sproduct5.html
sproduct6.html
sproduct7.html
sproduct8.html
```

---

# 🛒 Shopping Cart Interface

Hadiya includes a shopping-cart page that demonstrates the structure of an e-commerce cart.

The cart interface contains:

- Product image
- Product name
- Price
- Quantity
- Subtotal
- Remove icon
- Coupon field
- Cart subtotal
- Shipping information
- Total cost
- Checkout button

A simplified structure looks like:

```text
Shopping Cart
      │
      ▼
Product
Image
Name
Price
Quantity
Subtotal
      │
      ▼
Coupon Section
      │
      ▼
Cart Totals
      │
      ▼
Proceed to Checkout
```

---

# 🔐 Login Interface

The website contains a dedicated login page.

The interface includes:

- Username field
- Password field
- Remember Me option
- Forgot Password link
- Login button
- Link to registration

```text
Username
Password

☐ Remember Me

Forgot Password?

[ Login ]

Don't have an account?
Register
```

---

# 📝 Sign-Up Interface

A separate registration page allows the user interface to collect:

- Username
- Name
- Password
- Password confirmation
- Terms and Conditions agreement

The page also provides navigation back to the login interface.

---

# ℹ️ About Page

The About page introduces the Hadiya concept and presents information about the online gift store.

The page includes:

- Store introduction
- Brand description
- Community section
- Promotional video
- Store-service features
- Newsletter section

Service features presented include:

- Delivery
- Exchange & Return
- Save Money
- Safe & Secure
- Customer Service

---

# 📞 Contact Page

The Contact page provides a complete contact interface.

It includes:

- Contact information
- Opening hours
- Contact form
- Embedded Google Map
- Team information
- Newsletter section

The embedded map demonstrates the use of an external web service inside the website interface.

---

# 📱 Responsive Design

The website uses CSS media queries to adapt the interface to different device sizes.

Responsive layouts were implemented for:

- Navigation
- Hero section
- Product cards
- Contact page
- Forms
- Cart interface
- General page spacing

The stylesheet includes breakpoints for tablet and mobile layouts.

Example:

```css
@media (max-width: 799px) {
    /* Tablet and smaller layouts */
}

@media (max-width: 477px) {
    /* Mobile layouts */
}
```

---

# 📱 Mobile Navigation

JavaScript is used to control the responsive navigation menu.

The navigation menu can be opened and closed on smaller screen sizes.

Example:

```javascript
const bar = document.getElementById('bar');
const close = document.getElementById('close');
const nav = document.getElementById('navbar');

if (bar) {
    bar.addEventListener('click', () => {
        nav.classList.add('active');
    });
}

if (close) {
    close.addEventListener('click', () => {
        nav.classList.remove('active');
    });
}
```

This adds interactive behavior to the mobile interface without requiring an external JavaScript framework.

---

# 🏗️ Website Structure

The website is organized as a multi-page frontend application.

```text
                 Hadiya
                    │
        ┌───────────┼───────────┐
        │           │           │
        ▼           ▼           ▼
      Home         Shop       Account
        │           │           │
        │           │       ┌───┴────┐
        │           │       ▼        ▼
        │           │     Login    Sign Up
        │           │
        │           ▼
        │       Product Pages
        │
        ├──────────────► About
        │
        ├──────────────► Contact
        │
        └──────────────► Cart
```

---

# 🎨 User Interface Design

The project uses custom CSS to create the Hadiya visual identity.

The design includes:

- Product cards
- Hero banners
- Promotional sections
- Navigation bars
- Buttons
- Responsive grids
- Forms
- Footer layouts
- Product images
- Icons
- Mobile interfaces

The main stylesheet contains more than a thousand lines of CSS covering the different pages and responsive layouts.

---

# 🖼️ Visual Assets

The website contains a large collection of visual assets used throughout the shopping experience.

These include:

- Product images
- Promotional banners
- Store branding
- Feature icons
- Occasion-based imagery
- About-page imagery
- Team images
- Video content

The imagery helps create a complete e-commerce-style experience rather than a simple text-based website.

---

# 🛠️ Technologies Used

## 🌐 Frontend

- HTML5
- CSS3
- JavaScript

## 🎨 Interface & Design

- Responsive Web Design
- CSS Media Queries
- Flexbox
- Custom styling
- Product-card layouts
- Form design

## 🔧 External Resources

- Font Awesome
- Boxicons
- Google Maps Embed

## 🧰 Development Tools

- Visual Studio Code
- Web Browser Developer Tools

---

# 📂 Project Structure

```text
hadiya/
│
├── README.md
│
├── src/
│   ├── mainshop.html
│   ├── shop.html
│   ├── about.html
│   ├── contact.html
│   ├── cart.html
│   ├── Login.html
│   ├── SignUp.html
│   │
│   ├── sproduct.html
│   ├── sproduct2.html
│   ├── sproduct3.html
│   ├── sproduct4.html
│   ├── sproduct5.html
│   ├── sproduct6.html
│   ├── sproduct7.html
│   ├── sproduct8.html
│   │
│   ├── mainshop.css
│   ├── Login.css
│   ├── SignUp.css
│   ├── mainshop.js
│   │
│   ├── img/
│   ├── video.mp4
│   ├── favicon.png
│   └── favicon-2.ico
│
└── screenshots/
    ├── main-page-1.png
    ├── main-page-2.png
    ├── main-page-3.png
    ├── shop-page.png
    ├── cart-page.png
    ├── about-page.png
    ├── contact-page.png
    └── footer.png
```

---

# 📸 Project Screenshots

## 🏠 Home Page

![Hadiya Main Page](./screenshots/main-page-1.png)

---

## 🛍️ Home Page Collections

![Hadiya Home Page Collections](./screenshots/main-page-2.png)

---

## 🎁 Home Page Products

![Hadiya Home Page Products](./screenshots/main-page-3.png)

---

## 🛒 Shop Page

![Hadiya Shop Page](./screenshots/shop-page.png)

---

## 🛍️ Shopping Cart

![Hadiya Cart Page](./screenshots/cart-page.png)

---

## ℹ️ About Page

![Hadiya About Page](./screenshots/about-page.png)

---

## 📞 Contact Page

![Hadiya Contact Page](./screenshots/contact-page.png)

---

## 🔗 Footer

![Hadiya Footer](./screenshots/footer.png)

---

# 📁 Explore the Project

## 💻 Website Source Code

View the complete frontend implementation including HTML pages, CSS styling, JavaScript behavior, and website assets.

[**View Source Code →**](./src/)

---

## 🏠 Home Page

View the main Hadiya HTML page.

[**View Home Page Code →**](./src/mainshop.html)

---

## 🛍️ Shop

View the product catalog implementation.

[**View Shop Code →**](./src/shop.html)

---

## 🎨 Styling

View the main stylesheet containing the layouts, product cards, responsive design, forms, cart styling, and mobile layouts.

[**View CSS →**](./src/mainshop.css)

---

## ⚙️ JavaScript

View the JavaScript responsible for the responsive navigation behavior.

[**View JavaScript →**](./src/mainshop.js)

---

## 📸 Screenshots

Browse screenshots of the completed interface.

[**View Screenshots →**](./screenshots/)

---

# 💡 What I Learned

Developing Hadiya gave me practical experience building a complete multi-page website from the ground up.

Through this project, I gained hands-on experience with:

- HTML page structure
- CSS styling
- Responsive web design
- Media queries
- JavaScript DOM interaction
- Event listeners
- Mobile navigation
- Multi-page website navigation
- Product catalogs
- Product detail layouts
- Shopping-cart interfaces
- Login and registration interfaces
- Form design
- External map integration
- E-commerce interface design
- Image and media management
- Testing layouts across different screen sizes
- Organizing a larger frontend project

One of the most valuable parts of this project was learning how multiple HTML pages, shared styles, visual assets, and JavaScript behavior can work together to create a consistent website experience.

---

# ⚠️ Project Scope

The version included in this portfolio focuses on the **frontend implementation** of the Hadiya website.

Pages such as:

- Login
- Sign Up
- Shopping Cart
- Newsletter
- Contact Form
- Checkout

demonstrate the corresponding **user interfaces**, but the uploaded version does not include a connected server-side authentication, payment, or database system.

This README describes the functionality implemented in the source files included in this repository.

---

# 🔮 Future Improvements

The project could be extended with:

- Backend authentication
- User account storage
- Product database
- Dynamic shopping cart
- Persistent cart storage
- Checkout workflow
- Payment integration
- Order history
- Wishlist functionality
- Product search
- Product filtering
- Admin product management
- Contact-form backend
- Newsletter subscription storage
- Improved accessibility
- Additional JavaScript interactivity
- Deployment as a live e-commerce demo

---

# 🎓 Academic Context

**Web Development Project**  
**Beirut Arab University**

This project was developed to apply core frontend web-development concepts through the design and implementation of a multi-page e-commerce website.

## 👩‍💻 My Role

My work included:

- Website structure
- Frontend development
- Responsive design
- HTML implementation
- CSS styling
- JavaScript interaction
- Page navigation
- Product catalog design
- Product-detail interfaces
- Shopping-cart interface
- Login and registration interfaces
- About and Contact pages
- Responsive mobile navigation
- Visual asset integration
- Testing and debugging

---

## 🔗 Project Navigation

[**💻 Source Code →**](./src/) · [**🛍️ Shop →**](./src/shop.html) · [**🎨 CSS →**](./src/mainshop.css) · [**⚙️ JavaScript →**](./src/mainshop.js) · [**📸 Screenshots →**](./screenshots/)
