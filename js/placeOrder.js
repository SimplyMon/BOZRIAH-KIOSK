function placeOrder() {
  if (cart.length === 0) {
    showModal("Your cart is empty. Please add items before placing an order.");
    return;
  }

  // Get the selected order type and standardize its value
  const selectedOrderType = document.querySelector(
    'input[name="orderType"]:checked'
  ).value;

  // Ensure it's stored correctly for PHP handling
  const standardizedOrderType =
    selectedOrderType === "Take Out" ? "Takeout" : "Dine-in";

  fetch("content/place_order.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    // Send order type along with the cart
    body: JSON.stringify({ cart: cart, orderType: standardizedOrderType }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        localStorage.setItem("osehSeqNo", data.seqNo);
        localStorage.setItem("waitingNo", data.waitingNo);
        localStorage.setItem("cart", JSON.stringify(cart));
        localStorage.setItem("orderType", standardizedOrderType);
        window.location.href = "./content/order_summary.php";
      } else {
        showModal("Error placing order: " + data.error);
      }
    })
    .catch((error) => showModal("Error: " + error));
}
