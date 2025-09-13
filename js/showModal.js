function showModal(message) {
  document.getElementById("alert-message").innerText = message;
  document.getElementById("custom-alert").style.display = "flex";
}

function closeModal() {
  document.getElementById("custom-alert").style.display = "none";
}
