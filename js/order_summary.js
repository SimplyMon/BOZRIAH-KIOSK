let osehSeqNo = localStorage.getItem("osehSeqNo");
let cart = JSON.parse(localStorage.getItem("cart")) || [];
let orderType = localStorage.getItem("orderType") || "Dine-in";
document.getElementById("order-type").innerText = orderType;

// Products summary
function loadOrderSummary() {
  let orderContainer = document.getElementById("order-items");
  orderContainer.innerHTML = "";

  let total = 0;
  cart.forEach((item) => {
    let itemTotal = item.price * item.quantity;
    total += itemTotal;

    orderContainer.innerHTML += `
        <tr>
            <td>${item.name}</td>
            <td>P ${item.price}</td>
            <td>${item.quantity}</td>
            <td>P ${itemTotal.toLocaleString("en-US", {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2,
            })}</td>
        </tr>
    `;
  });

  document.getElementById("grand-total").innerText = total.toLocaleString(
    "en-US",
    {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }
  );

  document.getElementById("order-date").innerText =
    new Date().toLocaleDateString();
}

// Order confirmation
function confirmOrder() {
  fetch("./insert_order_details.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      osehSeqNo,
      cart,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        let grandTotal = document
          .getElementById("grand-total")
          .innerText.replace(/,/g, "");
        localStorage.setItem("grandTotal", grandTotal);

        // Print the receipt
        printOrderSummary();

        // Redirect immediately after printing
        setTimeout(() => {
          window.location.href = "./success.php";
        }, 20);
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch((error) => console.error("Error:", error));
}

// POS printing
function printOrderSummary() {
  let waitingNo = localStorage.getItem("waitingNo") || "N/A";
  let grandTotal = localStorage.getItem("grandTotal") || "0.00";
  let orderDate = new Date().toLocaleDateString();
  let orderType = localStorage.getItem("orderType") || "Dine-in";

  let printContent = `
    <div style="font-family: 'Courier New', monospace; font-size: 14px; text-align: center;">
        <h2 style="margin: 5px 0;">BOZRIAH</h2>
        <p><strong>Date:</strong> ${orderDate}</p>
        <p><strong>Order Type:</strong> ${orderType}</p>
        <p>AIC Burgundy Empire Tower, Sapphire Rd, Ortigas Center, Pasig, Metro Manila</p>
        <hr>
        <p style="font-size: 24px; font-weight: bold;">ORDER NO: ${waitingNo}</p>
        <hr>
        <h3 style="text-align: left;">ORDER DETAILS</h3>
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
    `;

  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart.forEach((item) => {
    let itemTotal = (item.price * item.quantity).toFixed(2);
    printContent += `
            <tr>
                <td style="text-align: left;">${item.quantity} × ${item.name}</td>
                <td style="text-align: right;">${itemTotal}</td>
            </tr>
        `;
  });

  printContent += `
        </table>
        <hr>
        <p style="text-align: center; font-size: 18px; font-weight: bold;">Grand Total: ${parseFloat(
          grandTotal
        ).toLocaleString("en-US", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        })}</p>
        <hr>
        <p>Thank you for your purchase!</p>
    </div>
    `;

  // Create a hidden iframe for silent printing
  let printFrame = document.createElement("iframe");
  printFrame.style.position = "absolute";
  printFrame.style.width = "0px";
  printFrame.style.height = "0px";
  printFrame.style.border = "none";
  document.body.appendChild(printFrame);

  let frameDoc = printFrame.contentWindow.document;
  frameDoc.open();
  frameDoc.write(printContent);
  frameDoc.close();

  printFrame.contentWindow.focus();

  setTimeout(() => {
    printFrame.contentWindow.print();
  }, 1);

  window.onafterprint = function () {
    document.body.removeChild(printFrame);
  };
}

document.addEventListener("DOMContentLoaded", loadOrderSummary);

function goBack() {
  window.history.back();
}
