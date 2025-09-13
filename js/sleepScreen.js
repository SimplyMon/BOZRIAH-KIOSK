let sleepTimeout;

function activateSleepScreen() {
  document.getElementById("sleep-screen").classList.add("active");
}

function wakeScreen() {
  document.getElementById("sleep-screen").classList.remove("active");
  resetSleepTimer();
}

function resetSleepTimer() {
  clearTimeout(sleepTimeout);
  sleepTimeout = setTimeout(activateSleepScreen, 5000);
}

document.addEventListener("click", resetSleepTimer);

resetSleepTimer();
