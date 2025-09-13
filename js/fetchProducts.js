let cart = [];

function fetchSubcategories(categoryCode) {
  fetch(`./content/fetch_subcategories.php?category=${categoryCode}`)
    .then((response) => response.json())
    .then((data) => {
      const subcategoryContainer = document.getElementById(
        "subcategory-container"
      );
      if (!subcategoryContainer) {
        console.error("Error: Subcategory container not found!");
        return;
      }

      if (!Array.isArray(data) || data.length === 0) {
        subcategoryContainer.innerHTML = "";
        return;
      }

      subcategoryContainer.innerHTML = data
        .map(
          (sub) => `
              <button class="subcategory-btn" onclick="fetchProducts('${categoryCode}', '${sub.SubCategoryCode}')">
                  ${sub.Description}
              </button>
          `
        )
        .join("");
    })
    .catch((error) => console.error("Fetch Error:", error));
}

function fetchProducts(categoryCode, subCategoryCode = "") {
  let productContainer = document.getElementById("products");

  // Show loading animation
  productContainer.innerHTML = `<div class="loading-spinner"></div>`;

  let url = `./content/fetch_product.php?category=${encodeURIComponent(
    categoryCode
  )}`;
  if (subCategoryCode) {
    url += `&subcategory=${encodeURIComponent(subCategoryCode)}`;
  }

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      productContainer.innerHTML = "";

      if (data.length === 0) {
        productContainer.innerHTML = "<h2>No products found.</h2>";
        return;
      }

      data.forEach((product, index) => {
        let imageUrl = `images.php?id=${product.ItemID}`;

        let productCard = document.createElement("div");
        productCard.classList.add("product-card", "slide-up");
        productCard.style.animationDelay = `${index * 0.05}s`;

        // Check if product is available
        if (product.IsAvailable == 0) {
          productCard.classList.add("disabled-product");
          productCard.innerHTML += `<div class="not-available">Not Available</div>`;
        } else {
          productCard.onclick = function () {
            addToCart(product.ItemID, product.ItemDesc, product.Price);
          };
        }

        productCard.innerHTML = `
                  <img src="${imageUrl}" alt="Product Image" class="product-image"
                      onerror="this.onerror=null; this.src='./assets/products/noimage.jpg';">
                  <div class="product-title">${product.ItemDesc}</div>
                  <div class="product-price">P ${product.Price}</div>
                  ${
                    product.IsAvailable == 0
                      ? '<div class="not-available">Not Available</div>'
                      : ""
                  }
              `;

        productContainer.appendChild(productCard);
      });

      setActiveCategory(categoryCode);
    })
    .catch((error) => console.error("Error fetching products:", error));
}

// Automatically load "All" products on page load
document.addEventListener("DOMContentLoaded", function () {
  fetchProducts("ALL");
});

// active category button logic
function setActiveCategory(categoryCode) {
  let buttons = document.querySelectorAll(".category-btn");

  buttons.forEach((btn) => {
    // Remove active class from all buttons
    btn.classList.remove("active");

    // Check if the button matches the clicked category
    if (btn.getAttribute("data-category") === categoryCode) {
      btn.classList.add("active"); // Add active class
    }
  });
}

// load "All" category on page load
document.addEventListener("DOMContentLoaded", function () {
  fetchProducts("ALL");
});

// Function to add product to cart
function addToCart(id, name, price) {
  let existingItem = cart.find((item) => item.id === id);
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({ id, name, price, quantity: 1 });
  }
  updateCart();
}

// Function to remove product from cart
function removeFromCart(id) {
  cart = cart.filter((item) => item.id !== id);
  updateCart();
}

// Function to update cart UI
function updateCart() {
  let cartContainer = document.getElementById("cart-items");
  cartContainer.innerHTML = "";

  if (cart.length === 0) {
    cartContainer.innerHTML = "<p>No items in cart.</p>";
  } else {
    cart.forEach((item, index) => {
      cartContainer.innerHTML += `
        <div class="cart-item">
          <span>${item.name} (₱${item.price})</span>
          <div>
            <button onclick="updateQuantity(${index}, -1)">&#8722;</button>
            ${item.quantity}
            <button onclick="updateQuantity(${index}, 1)">&#43;</button>
            <button class="remove-btn" onclick="removeFromCart('${item.id}')"
              style="background-color: #ff4d4d; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; font-size: 20px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.3s;"
              onmouseover="this.style.backgroundColor='#cc0000'; this.style.transform='scale(1.1)';"
              onmouseout="this.style.backgroundColor='#ff4d4d'; this.style.transform='scale(1)';">
              &#215;
            </button>
          </div>
        </div>
      `;
    });
  }

  updateCartSummary(); // Make sure to update the button state
}

function updateCartSummary() {
  let subtotal = cart.reduce(
    (sum, item) => sum + item.price * item.quantity,
    0
  );

  document.getElementById("grandtotal").innerText = subtotal.toLocaleString(
    "en-US",
    {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }
  );

  let placeOrderBtn = document.querySelector(".place-order-btn");

  if (cart.length === 0) {
    placeOrderBtn.disabled = true;
    placeOrderBtn.style.backgroundColor = "#ccc"; // Gray out the button
    placeOrderBtn.style.cursor = "not-allowed";
  } else {
    placeOrderBtn.disabled = false;
    placeOrderBtn.style.backgroundColor = "#f58220"; // Restore button color
    placeOrderBtn.style.cursor = "pointer";
  }
}

// Function to update item quantity
function updateQuantity(index, change) {
  cart[index].quantity += change;
  if (cart[index].quantity <= 0) {
    cart.splice(index, 1);
  }
  updateCart();
}
