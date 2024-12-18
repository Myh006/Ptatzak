<?php
// Include the database connection
include 'database.php'; // Make sure this file contains the $pdo connection

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--meta-ports-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--styles-->
    <link rel="stylesheet" type="text/css" href="Styling/global.css">
    <link rel="stylesheet" type="text/css" href="Styling/home.css">
    <link rel="stylesheet" type="text/css" href="Styling/menu.css">
    <link rel="stylesheet" type="text/css" href="Styling/modal.css">

    <!--swiper-css-->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <title>Labor Disctrict</title>
</head>
<scri>
    <nav>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex flex-row align-items-center justify-content-between flex-between">
                        <div class="d-flex align-items-center">
                            <a href="index.html">
                                <img src="Assets/images/Logo.png" alt="img" class="image_nav" width="60px" height="60px">
                            </a>                            
                            <span class="d-flex nav_title align-center">Labor District</span>
                        </div>
                        <div class="hamburger-wrapper my-auto">
                            <div class="hamburger" id="hamburger">
                                &#9776; <!-- Hamburger icon -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Links -->
                <div class="col-lg-1 col-md-1 nav-links">
                    <div class="box align-center">
                        <a href="menu.php">MENU</a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 nav-links">
                    <div class="box align-center">
                    <a href="index.html#overOns">OVER ONS</a>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 nav-links">
                    <div class="box align-center">
                        <a href="index.html#contact">CONTACT</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<slide>
    <div class="swiper mySwiper">
        
        <div class="swiper-wrapper">
        <div class="swiper-slide" data-category="all">
            <span>Alles</span>
        </div>
        <?php
        try {
            $stmt = $pdo->query("SELECT DISTINCT cat_name FROM categories");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as $category) {
                echo '<div class="swiper-slide" data-category="' . htmlspecialchars($category['cat_name']) . '">';
                echo '<span>' . htmlspecialchars($category['cat_name']) . '</span>';
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<div class="swiper-slide"><span>Error loading categories</span></div>';
        }
        ?>


        </div>
         <!-- prev -->
        <div class="swiper-button-prev">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <path d="M20 11L7.83 11L13.42 5.41L12 4L4 12L12 20L13.41 18.59L7.83 13L20 13V11Z" fill="#18141F" fill-opacity="0.79"/>
            </svg>
        </div>
        <!-- next -->
        <div class="swiper-button-next">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <path d="M4 13L16.17 13L10.58 18.59L12 20L20 12L12 4L10.59 5.41L16.17 11L4 11V13Z" fill="#18141F" fill-opacity="0.79"/>
            </svg>
        </div>
    </div>
</slide>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Attach click events to Swiper slides
    document.querySelectorAll('.swiper-slide').forEach(slide => {
        slide.addEventListener('click', () => {
            const selectedCategory = slide.getAttribute('data-category');
            fetchProductsByCategory(selectedCategory);
        });
    });

    // Function to fetch products based on the selected category
    function fetchProductsByCategory(category) {
        fetch(`fetch_products.php?category=${encodeURIComponent(category)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                } else {
                    updateProductList(data);
                }
            })
            .catch(error => console.error('Fetch error:', error));
    }

    // Function to dynamically update the product list
    function updateProductList(products) {
        const productContainer = document.querySelector('.row .col-lg-9 .row'); // Product list container
        productContainer.innerHTML = ''; // Clear existing products

        if (products.length === 0) {
            productContainer.innerHTML = '<p>No products available in this category.</p>';
            return;
        }

        products.forEach(product => {
            const productHTML = `
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <div class="product-card">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="product-text d-flex flex-col">
                                    <div class="d-flex flex-row flex-between">
                                        <span>${product.product_name}</span>
                                        <span class="C-text">€${(product.product_price / 100).toFixed(2).replace('.', ',')}</span>
                                    </div>
                                    <span>Keuze uit: Geen sauzen beschikbaar</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                ${product.product_img
                                    ? `<img src="data:image/jpeg;base64,${product.product_img}" alt="${product.product_name}" class="product-image">`
                                    : `<img src="Assets/images/Afhalen.png" alt="Default product image" class="product-image">`}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex flex-row flex-between">
                                    <div class="d-flex product-btn-wrap">
                                        <button class="product-btn minus hoverGray" onclick="decreaseCount(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M3.75 12H20.25" stroke="#D35400" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <div class="product-btn count"><span>0</span></div>
                                        <button class="product-btn plus hoverGray" onclick="increaseCount(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M3.75 12H20.25" stroke="#D35400" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 3.75V20.25" stroke="#D35400" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <button class="product-btn add hover openPopUpExtra" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M13.9599 15.985L10.0708 15.985" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11.9851 14.0705L11.9851 17.9596" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.80994 2L5.18994 5.63" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15.1899 2L18.8099 5.63" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M2 7.84998C2 5.99998 2.99 5.84998 4.22 5.84998H19.78C21.01 5.84998 22 5.99998 22 7.84998C22 9.99998 21.01 9.84998 19.78 9.84998H4.22C2.99 9.84998 2 9.99998 2 7.84998Z" stroke="#2E8B57" stroke-width="2"/>
                                            <path d="M3.5 10L4.91 18.64C5.23 20.58 6 22 8.86 22H14.89C18 22 18.46 20.64 18.82 18.76L20.5 10" stroke="#2E8B57" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            productContainer.insertAdjacentHTML('beforeend', productHTML);
        });
    }

    // Fetch all products on page load
    fetchProductsByCategory('all');
});

</script>
    <div id="dynamicModalContainer"></div>
    <section class="mb-lg">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- title -->
                     <div class="menu-title d-flex flex-col">
                        <span>
                            MENU
                        </span>
                        <span>
                            Maak hier u bestelling
                        </span>
                     </div>
                </div>
                <div class="col-lg-9"></div>
            <div class="row">
                <!-- for each loop -->
                <div class="col-lg-9 col-md-8 col-sm-12 order-lg-1 order-md-1 order-sm-2">
                    <div class="row">
                    <?php

                        // Fetch all products with their associated category
                        $stmt = $pdo->query("SELECT product_name, product_price, product_img, product_cat FROM products");
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Fetch all sauces from the database
                        $saucesStmt = $pdo->query("SELECT product_name FROM products WHERE product_cat = 'sauzen'");
                        $sauces = $saucesStmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only product names as an array

                        foreach ($products as $product) {
                            ?>
                            <div class="col-lg-6 col-md-8 col-sm-10">
                                <div class="product-card">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <div class="product-text d-flex flex-col">
                                                <div class="d-flex flex-row flex-between">
                                                    <!-- Product Name -->
                                                    <span><?php echo htmlspecialchars($product['product_name']); ?></span>
                                                    <!-- Product Price -->
                                                    <span class="C-text">€<?php echo number_format($product['product_price'] / 100, 2, ',', ''); ?></span>
                                                </div>
                                            <!-- Display all sauces as choices -->
                                            <?php if (!empty($sauces)) : ?>
                                                <span>Keuze uit: <?php echo implode(', ', array_map('htmlspecialchars', $sauces)); ?></span>
                                            <?php else : ?>
                                                <span>Geen sauzen beschikbaar</span>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- Product Image -->
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <?php if (!empty($product['product_img'])) : ?>
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['product_img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image">
                                            <?php else : ?>
                                                <img src="Assets/images/Afhalen.png" alt="Default product image" class="product-image">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="d-flex flex-row flex-between">
                                                <div class="d-flex product-btn-wrap">
                                                    <!-- Minus Button -->
                                                    <button class="product-btn minus hoverGray" onclick="decreaseCount(this)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="product-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M3.75 12H20.25" stroke="#D35400" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </button>
                                                    <!-- Total count -->
                                                    <div class="product-btn count">
                                                        <span>0</span>
                                                    </div>
                                                    <!-- Plus Button -->
                                                    <button class="product-btn plus hoverGray" onclick="increaseCount(this)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="product-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M3.75 12H20.25" stroke="#D35400" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M12 3.75V20.25" stroke="#D35400" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <button class="product-btn add hover openPopUpExtra" type="button">
                                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" class="product-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M13.9599 15.985L10.0708 15.985" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M11.9851 14.0705L11.9851 17.9596" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M8.80994 2L5.18994 5.63" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M15.1899 2L18.8099 5.63" stroke="#2E8B57" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M2 7.84998C2 5.99998 2.99 5.84998 4.22 5.84998H19.78C21.01 5.84998 22 5.99998 22 7.84998C22 9.99998 21.01 9.84998 19.78 9.84998H4.22C2.99 9.84998 2 9.99998 2 7.84998Z" stroke="#2E8B57" stroke-width="2"/>
                                                        <path d="M3.5 10L4.91 18.64C5.23 20.58 6 22 8.86 22H14.89C18 22 18.46 20.64 18.82 18.76L20.5 10" stroke="#2E8B57" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                    </div> 
                </div>
                <div class="page-overlay" id="pageOverlay"></div> <!-- Overlay element -->

                <div class="col-lg-3 col-md-4 col-sm-12 order-lg-2 order-md-2 order-sm-1">
                    <div class="basket-card-container">
                        <div class="basket-card d-flex flex-col">
                            <!-- The Hamburger Icon -->
                            <div class="basket-hamburger" id="basketHamburger">
                                <div class="d-flex flex-start W-100">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1H3L3.4 3M5 11H15L19 3H3.4M5 11L3.4 3M5 11L2.70711 13.2929C2.07714 13.9229 2.52331 15 3.41421 15H15M15 15C13.8954 15 13 15.8954 13 17C13 18.1046 13.8954 19 15 19C16.1046 19 17 18.1046 17 17C17 15.8954 16.1046 15 15 15ZM7 17C7 18.1046 6.10457 19 5 19C3.89543 19 3 18.1046 3 17C3 15.8954 3.89543 15 5 15C6.10457 15 7 15.8954 7 17Z" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                </div>
                            </div>
                            <!-- The Basket Content -->
                            <div class="basket-content" id="basketContent">
                                <span class="basket-title">Jouw bestelling</span>
                                <hr class="diveder">
                                <div id="basketItems" class="basket-items">
                                    <!-- Basket items will be dynamically rendered here -->
                                </div>
                                <hr class="diveder">
                                <!-- make total price of all products -->
                                <span id="basketTotal" class="basket-total">Totaal</span>
                                <button class="btn p responsHamburger mt-md" type="button" id="openPopUpBestelButton">Bestelling plaatsen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row" id="contact">
                <div class="col-lg-9 my-auto">
                    <div class="footer-logo d-flex flex-row ">
                        <img src="Assets/images/Logo.png" alt="img" class="image_nav" width="100px" height="100px">
                        <div class="d-flex flex-col flex-between ml-lg">
                            <div class="footer-card d-flex flex-col">
                                <span class="">Vragen?</span>
                                <span class="">Bel ons dan via :0987654321</span>
                            </div>
                            <span class="text-address">Winsum, appelstraat 9872FD</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="d-flex flex-end">
                        <div class="box card-tijden d-flex flex-col text-center">
                            <span>Openingstijden</span>
                            <span>Maandag van 14:00 tot 20:00</span>
                            <span>Dinsdag van 14:00 tot 20:00</span>
                            <span>Woensdag van 14:00 tot 20:00</span>
                            <span>Donderdag van 14:00 tot 20:00</span>
                            <span>Vrijdag van 14:00 tot 20:00</span>
                            <span>Zaterdag van 14:00 tot 20:00</span>
                            <span>Zondag van 14:00 tot 20:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

<!-- Modals -->
<div class="modal" id="modalOpenPopUpBestel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog d-flex flex-col" style="    transform: translateY(-10px) !important; /* " role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <span class="modal-title">Afhalen of Bezorgen?</span>
            </div>
            <div class="modal-body">
                <ul class="nav-tabs">
                    <li class="tab-item active" data-tab-target="#tab1">Afhalen</li>
                    <li class="tab-item" data-tab-target="#tab2">Bezorgen</li>
                </ul>  
                <div class="tab-content">
                    <!-- Afhalens -->
                    <div id="tab1" class="tab-pane active">
                        <div class="d-flex flex-row">
                            <div class="afhaal-input-wrap d-flex flex-col W-100">
                                <span>Naam</span>
                                <input type="text" placeholder="Naam">
                            </div>
                            <div class="afhaal-input-wrap d-flex flex-col W-100">
                                <span>Telefoonnummer</span>
                                <input type="number" placeholder="Telefoonnummer">
                            </div>
                        </div>
                        <select name="Payment" class="W-100" id="AfhalenPayment">
                                <option value="">---Selecteer een betaal methode---</option>
                                <option value="ABN">ABN amro</option>
                                <option value="ING">ING</option>
                                <option value="KNAB">KNAB bank</option>
                                <option value="RABO">Rabobank</option>
                        </select>
                    </div>

                    <!-- Bestellen -->
                    <div id="tab2" class="tab-pane ">
                        <div class="d-flex flex-row mt-sm">
                            <div class="afhaal-input-wrap d-flex flex-col W-100">
                                <span>Voornaam</span>
                                <input type="text" placeholder="Voornaam">
                            </div>
                            <div class="afhaal-input-wrap d-flex flex-col W-100">
                                <span>Achternaam</span>
                                <input type="text" placeholder="Achternaam">
                            </div>
                        </div>
                        <div class="d-flex flex-row mt-sm">
                            <div class="afhaal-input-wrap d-flex flex-col W-100">
                                <span>Email</span>
                                <input type="text" placeholder="Naam">
                            </div>
                            <div class="afhaal-input-wrap d-flex flex-col W-100">
                                <span>Telefoonnummer</span>
                                <input type="number" placeholder="Telefoonnummer">
                            </div>
                        </div>
                        <div class="d-flex flex-row flex-wrap mt-sm">
                            <div class="afhaal-input-wrap d-flex flex-col">
                                <span>Postcode</span>
                                <input type="text" id="postcode" placeholder="Postcode">
                            </div>
                            <div class="afhaal-input-wrap d-flex flex-col" style="width:28%;">
                                <span class="W-fit">Huisnummer</span>
                                <input type="number" id="huisnummer">
                            </div>
                            <div class="afhaal-input-wrap d-flex flex-col" style="width:27%;">
                                <span class="W-fit">Toevoeging</span>
                                <input type="text" id="toevoeging">
                            </div>
                        </div>
                        <div class="afhaal-input-wrap d-flex flex-col mt-sm">
                            <span>Address</span>
                            <input type="text" id="address" readonly>
                            <label id="address-label" style="color: red; display: none;">Incorrect address</label>
                        </div>
                        <select name="Payment" class="W-100" id="BezorgenPayment">
                                <option value="">---Selecteer een betaal methode---</option>
                                <option value="ABN">ABN amro</option>
                                <option value="ING">ING</option>
                                <option value="KNAB">KNAB bank</option>
                                <option value="RABO">Rabobank</option>
                        </select>
                        <textarea name="Omschrijving" class="W-100" id="BestellingOmschrijving" placeholder="Omschrijving"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex flex-row flex-between mt-lg">
                <button type="button" class="btn btn-A" id="closePopUpBestelFooter">Close</button>
                <button type="button" class="btn btn-H" id="submitOrderButton">Bestellen & betalen</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const postcodeInput = document.getElementById("postcode");
        const huisnummerInput = document.getElementById("huisnummer");
        const toevoegingInput = document.getElementById("toevoeging");
        const addressInput = document.getElementById("address");
        const addressLabel = document.getElementById("address-label");

        const apiToken = "4107d162-f8e6-4913-bd47-adb4a0e187c9";

        // Functie om adres te valideren
        async function validateAddress() {
            const postcode = postcodeInput.value.trim();
            const huisnummer = huisnummerInput.value.trim();
            const toevoeging = toevoegingInput.value.trim();

            if (postcode && huisnummer) {
                try {
                    // API-aanroep naar postcode.tech
                    const response = await fetch(
                        `https://postcode.tech/api/v1/postcode?postcode=${postcode}&number=${huisnummer}`,
                        {
                            method: "GET",
                            headers: {
                                "Authorization": `Bearer ${apiToken}`,
                                "Content-Type": "application/json"
                            }
                        }
                    );

                    if (!response.ok) {
                        throw new Error("Invalid response");
                    }

                    const data = await response.json();

                    // Controleer of de API geldige gegevens retourneert
                    if (data && data.street && data.city) {
                        addressInput.value = `${data.street} ${huisnummer}${toevoeging ? ", " + toevoeging : ""}, ${data.city}`;
                        addressInput.style.borderColor = "green";
                        addressLabel.style.display = "none";
                    } else {
                        throw new Error("Invalid address data");
                    }
                } catch (error) {
                    addressInput.value = "";
                    addressInput.style.borderColor = "red";
                    addressLabel.style.display = "block";
                    addressLabel.textContent = "Incorrect address";
                }
            } else {
                addressInput.value = "";
                addressInput.style.borderColor = "";
                addressLabel.style.display = "none";
            }
        }

        // Event listeners
        postcodeInput.addEventListener("input", validateAddress);
        huisnummerInput.addEventListener("input", validateAddress);
        toevoegingInput.addEventListener("input", validateAddress);
    });
</script>

<script>
document.getElementById("submitOrderButton").addEventListener("click", function () {
    // Determine active tab (Afhalen or Bezorgen)
    let orderType = document.querySelector(".tab-pane.active").id === "tab1" ? "afhalen" : "bezorgen";

    // Select the correct payment dropdown based on the active tab
    let paymentDropdown = orderType === "afhalen" 
        ? document.getElementById("AfhalenPayment") 
        : document.getElementById("BezorgenPayment");

    // Collect form data based on the active tab
    let data = {
        order_type: orderType,
        naam: orderType === "afhalen" ? document.querySelector('#tab1 input[placeholder="Naam"]').value.trim() : null,
        telefoonnummer: orderType === "afhalen"
            ? document.querySelector('#tab1 input[placeholder="Telefoonnummer"]').value.trim()
            : document.querySelector('#tab2 input[placeholder="Telefoonnummer"]').value.trim(),
        voornaam: orderType === "bezorgen" ? document.querySelector('#tab2 input[placeholder="Voornaam"]').value.trim() : null,
        achternaam: orderType === "bezorgen" ? document.querySelector('#tab2 input[placeholder="Achternaam"]').value.trim() : null,
        email: orderType === "bezorgen" ? document.querySelector('#tab2 input[placeholder="Naam"]').value.trim() : null,
        address: orderType === "bezorgen" ? document.getElementById("address").value.trim() : null,
        payment_method: paymentDropdown.value.trim(),
        omschrijving: orderType === "bezorgen" ? document.getElementById("BestellingOmschrijving").value.trim() : null
    };

    // Check which required fields are missing
    let missingFields = [];

    if (!data.payment_method) missingFields.push("Betalingsmethode");
    if (!data.telefoonnummer) missingFields.push("Telefoonnummer");

    if (orderType === "afhalen") {
        if (!data.naam) missingFields.push("Naam");
    }

    if (orderType === "bezorgen") {
        if (!data.voornaam) missingFields.push("Voornaam");
        if (!data.achternaam) missingFields.push("Achternaam");
        if (!data.email) missingFields.push("Email");
        if (!data.address) missingFields.push("Adres");
    }

    // If there are missing fields, show an alert with the list
    if (missingFields.length > 0) {
        alert("Vul alle verplichte velden in:\n- " + missingFields.join("\n- "));
        return;
    }

    // Send the data to the server using fetch
    fetch("insert_order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === "success") {
            alert("Order succesvol geplaatst!");
            window.location.href = "/patatzaak"; // Redirect to home page (root URL)
        } else {
            alert("Error: " + result.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Er is een fout opgetreden bij het plaatsen van de order.");
    });
});

</script>





 <!--swiper-script-->
 <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: "1",  // Automatically adjust slides per view
        spaceBetween: 10,
        grabCursor: true,

        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
            1024: {
                slidesPerView: 4,  // Show 3 slides for larger screens
                spaceBetween: 10,
            },
        },
    });
</script>


<?php
require_once 'database.php'; // Ensure this initializes $pdo

/**
 * Fetch items by category from the database and output debug information in HTML.
 * 
 * @param PDO $pdo The database connection object.
 * @param string $category The category to fetch products for.
 * @return array The fetched products as an associative array.
 */
$sauzenItems = [];
$drinkenItems = [];

// Assuming you use PDO for database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=patatzaak', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch products with category 'sauzen'
    $stmtSauzen = $pdo->prepare("SELECT id, product_name, product_price FROM products WHERE product_cat = 'sauzen'");
    $stmtSauzen->execute();
    $sauzenItems = $stmtSauzen->fetchAll(PDO::FETCH_ASSOC);

    // Fetch products with category 'drinken'
    $stmtDrinken = $pdo->prepare("SELECT id, product_name, product_price FROM products WHERE product_cat = 'drinken'");
    $stmtDrinken->execute();
    $drinkenItems = $stmtDrinken->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<div class="modal" id="PopUpExtra" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <span class="modal-title">Een extraatje erbij?</span>
            </div>
            <div class="modal-body">
                <ul class="nav-tabs">
                    <li class="tab-item active" data-tab-target="#tabS">Sausen</li>
                    <li class="tab-item" data-tab-target="#tabD">Drinken</li>
                </ul>
                <div class="tab-content">
                    <!-- Sauzen Tab -->
                    <div id="tabS" class="tab-pane active">
                        <div class="d-flex flex-col flex-start">
                            <span>Sauzen</span>
                            <hr class="diveder">
                            <?php if (!empty($sauzenItems)): ?>
                                <?php foreach ($sauzenItems as $item): ?>
                                    <?php 
                                        // Convert price from integer to decimal format (e.g., 200 -> 2.00)
                                        $formattedPrice = number_format($item['product_price'] / 100, 2, ',', '');
                                    ?>
                                    <label class="extra-wrap d-flex flex-row mt-lg mb-lg">
                                        <input type="radio" name="sauzen" 
                                            value="<?= htmlspecialchars($item['id']) ?>" 
                                            data-name="<?= htmlspecialchars($item['product_name']) ?>" 
                                            data-price="<?= $formattedPrice ?>">
                                        <span class="radio-button">
                                            <?= htmlspecialchars($item['product_name']) ?> - €<?= $formattedPrice ?>
                                        </span>
                                    </label>
                                    <hr class="diveder">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No sauzen available.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Drinken Tab -->
                    <div id="tabD" class="tab-pane">
                        <div class="d-flex flex-col flex-start">
                            <span>Drinken</span>
                            <hr class="diveder">
                            <?php if (!empty($drinkenItems)): ?>
                                <?php foreach ($drinkenItems as $item): ?>
                                    <?php 
                                        // Convert price from integer to decimal format (e.g., 200 -> 2.00)
                                        $formattedPrice = number_format($item['product_price'] / 100, 2, ',', '');
                                    ?>
                                    <label class="extra-wrap d-flex flex-row mt-lg mb-lg">
                                        <input type="radio" name="drinken" 
                                            value="<?= htmlspecialchars($item['id']) ?>" 
                                            data-name="<?= htmlspecialchars($item['product_name']) ?>" 
                                            data-price="<?= $formattedPrice ?>">
                                        <span class="radio-button">
                                            <?= htmlspecialchars($item['product_name']) ?> - €<?= $formattedPrice ?>
                                        </span>
                                    </label>
                                    <hr class="diveder">
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No drinken available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Button -->
                <div class="modal-footer d-flex flex-row flex-between mt-lg">

                    <button type="button" class="btn btn-H" id="confirmButton">Voeg toe</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Script nav hamburger -->
    <script>
    // script.js
        document.addEventListener("DOMContentLoaded", () => {
            const hamburger = document.getElementById('hamburger');
            const navLinks = document.querySelectorAll('.nav-links');

            hamburger.addEventListener('click', () => {
                navLinks.forEach(link => {
                    link.classList.toggle('active');
                });
            });
        });

    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Algemene referenties
        const bestelButton = document.getElementById('openPopUpBestelButton'); // Knop om bestelmodal te openen
        const bestelModal = document.getElementById('modalOpenPopUpBestel'); // Bestelmodal
        const closeModalButton = document.getElementById('closePopUpBestelFooter'); // Sluitknop bestelmodal
        const productContainer = document.querySelector('.row .col-lg-9 .row'); // Parent container voor producten
        const basketItemsContainer = document.getElementById('basketItems'); // Winkelwagen items
        const basketTotalElement = document.getElementById('basketTotal'); // Totaalprijs in winkelwagen
        let basketTotal = 0;
        let currentProductDetails = {}; // Details van huidig geselecteerd product

        // ** Modal openen/sluiten: Bestelmodal **
        bestelButton.addEventListener('click', function () {
            console.log("Bestelknop geklikt"); // Debugging
            if (basketItemsContainer.children.length === 0) {
                alert('Je winkelwagen is leeg! Voeg producten toe voordat je een bestelling plaatst.');
                return;
            }
            bestelModal.classList.add('show'); // Voeg de 'show'-klasse toe
            bestelModal.style.display = 'block'; // Maak de modal zichtbaar
            setTimeout(() => {
                bestelModal.style.opacity = '1'; // Fade-in effect
            }, 10);
        });

        closeModalButton.addEventListener('click', function () {
            console.log("Modal sluitknop geklikt"); // Debugging
            bestelModal.style.opacity = '0'; // Fade-out effect
            setTimeout(() => {
                bestelModal.style.display = 'none'; // Verberg de modal
                bestelModal.classList.remove('show'); // Verwijder de 'show'-klasse
            }, 300); // Wacht op de fade-out transition
        });

        // ** Event delegation voor dynamische knoppen **
        productContainer.addEventListener('click', function (event) {
            // Check of op "openPopUpExtra"-knop is geklikt
            if (event.target.closest('.openPopUpExtra')) {
                const button = event.target.closest('.openPopUpExtra');
                const productCard = button.closest('.product-card');
                const productName = productCard.querySelector('.product-text span').innerText.trim();
                const productPrice = productCard.querySelector('.C-text').innerText.replace('€', '').replace(',', '.').trim();
                const productCount = parseInt(productCard.querySelector('.count span').innerText.trim(), 10);

                if (productCount === 0) {
                    alert('Kies eerst een product voordat je de winkelwagen gebruikt!');
                    return;
                }

                currentProductDetails = {
                    name: productName,
                    price: (parseFloat(productPrice) * productCount).toFixed(2),
                    count: productCount
                };

                // Open "PopUpExtra" modal
                const extraModal = document.getElementById('PopUpExtra');
                extraModal.classList.add('show');
                extraModal.style.display = 'block';
                setTimeout(() => { extraModal.style.opacity = '1'; }, 10);
            }
        });

        // ** Confirm Button in Extra Modal **
        document.getElementById('confirmButton').addEventListener('click', function () {
            let sauzenName = "";
            let sauzenPrice = "0.00";
            let drinkenName = "";
            let drinkenPrice = "0.00";

            const selectedSauzen = document.querySelector('input[name="sauzen"]:checked');
            const selectedDrinken = document.querySelector('input[name="drinken"]:checked');

            if (selectedSauzen) {
                sauzenName = selectedSauzen.getAttribute('data-name');
                sauzenPrice = selectedSauzen.getAttribute('data-price');
            }
            if (selectedDrinken) {
                drinkenName = selectedDrinken.getAttribute('data-name');
                drinkenPrice = selectedDrinken.getAttribute('data-price');
            }

            const extrasTotal = parseFloat(sauzenPrice.replace(',', '.')) + parseFloat(drinkenPrice.replace(',', '.'));
            const finalPrice = (parseFloat(currentProductDetails.price) + extrasTotal).toFixed(2);

            const basketItem = document.createElement('div');
            basketItem.classList.add('basket-item');
            basketItem.innerHTML = `
                <div class="d-flex flex-col">
                    <div class="d-flex flex-row flex-between">
                        <p class="mb-sm ListProduct">x${currentProductDetails.count} ${currentProductDetails.name}</p>
                        <p class="ListProduct">€${finalPrice}</p>
                    </div>
                    <div class="d-flex flex-row flex-between">
                        <p class="text-start listDesc">Keuze: ${sauzenName} ${drinkenName}</p>
                        <button class="remove-btn" style="background: none; border: none; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998" stroke="#FE0909" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97" stroke="#FE0909" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.8499 9.14001L18.1999 19.21C18.0899 20.78 17.9999 22 15.2099 22H8.7899C5.9999 22 5.9099 20.78 5.7999 19.21L5.1499 9.14001" stroke="#FE0909" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.3301 16.5H13.6601" stroke="#FE0909" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.5 12.5H14.5" stroke="#FE0909" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            basketItemsContainer.appendChild(basketItem);

            basketTotal += parseFloat(finalPrice);
            basketTotalElement.innerText = `Totaal €${basketTotal.toFixed(2)}`;

            const removeButton = basketItem.querySelector('.remove-btn');
            removeButton.addEventListener('click', function () {
                basketTotal -= parseFloat(finalPrice);
                basketTotalElement.innerText = `Totaal €${basketTotal.toFixed(2)}`;
                basketItem.remove();
            });

            const extraModal = document.getElementById('PopUpExtra');
            extraModal.style.opacity = '0';
            setTimeout(() => {
                extraModal.style.display = 'none';
                extraModal.classList.remove('show');
            }, 300);
        });
    });
</script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Listen for clicks on tab items
            document.querySelectorAll('.nav-tabs').forEach(tabContainer => {
                tabContainer.addEventListener('click', function (event) {
                    if (event.target.classList.contains('tab-item')) {
                        // Get the parent modal of the clicked tab item
                        const modal = event.target.closest('.modal');

                        // Remove 'active' class from all tabs and tab contents within this modal
                        modal.querySelectorAll('.tab-item').forEach(tab => tab.classList.remove('active'));
                        modal.querySelectorAll('.tab-pane').forEach(tabPane => tabPane.classList.remove('active'));

                        // Add 'active' class to the clicked tab
                        event.target.classList.add('active');

                        // Find the target tab content and activate it
                        const targetSelector = event.target.getAttribute('data-tab-target');
                        const targetTab = modal.querySelector(targetSelector);
                        if (targetTab) {
                            targetTab.classList.add('active');
                        }
                    }
                });
            });
        });
    </script>

    <!-- Hamburger menu --> 
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const basketHamburger = document.getElementById('basketHamburger');
        const basketContent = document.getElementById('basketContent');
        const basketCard = document.querySelector('.basket-card');
        const pageOverlay = document.getElementById('pageOverlay');

        basketHamburger.addEventListener('click', () => {
            const isActive = basketContent.classList.toggle('active');
            pageOverlay.classList.toggle('active', isActive);

        });

        // Close the basket content when clicking on the overlay
        pageOverlay.addEventListener('click', () => {
            basketContent.classList.remove('active');
            pageOverlay.classList.remove('active');
        });
    });
    </script>

    <!-- add to basket count -->
    <script>
        function increaseCount(button) {
            const countElement = button.parentElement.querySelector('.count span');
            let currentCount = parseInt(countElement.innerText, 10);
            countElement.innerText = currentCount + 1;
        }

        function decreaseCount(button) {
            const countElement = button.parentElement.querySelector('.count span');
            let currentCount = parseInt(countElement.innerText, 10);
            if (currentCount > 0) {
                countElement.innerText = currentCount - 1;
            }
        }
    </script>
</html>

<style>
    /* Modal dialog */
.modal-dialog {
    position: relative;
    width: auto;
    margin: 2.5% auto;
    max-width: 600px;
    transition: transform 0.3s ease-in-out; /* Transition effect for dialog */
    transform: translateY(-10px) !important; /* Initial position for transition effect */
}
</style>