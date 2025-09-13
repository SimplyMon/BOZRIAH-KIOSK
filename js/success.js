document.addEventListener("DOMContentLoaded", function () {
  let waitingNo = localStorage.getItem("waitingNo");
  let grandTotal = localStorage.getItem("grandTotal");
  // let isTakeout =
  //   localStorage.getItem("isTakeout") === "1" ? "Takeout" : "Dine-in";

  document.getElementById("waiting-number").innerText = waitingNo
    ? waitingNo
    : "N/A";

  if (grandTotal) {
    document.getElementById("grand-total").innerText = parseFloat(
      grandTotal
    ).toLocaleString("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  } else {
    document.getElementById("grand-total").innerText = "0.00";
  }

  // rtemove after display
  localStorage.removeItem("cart");
  localStorage.removeItem("osehSeqNo");
  localStorage.removeItem("waitingNo");
  localStorage.removeItem("grandTotal");
  // localStorage.removeItem("isTakeout");
});

function goHome() {
  window.location.href = "../";
}
