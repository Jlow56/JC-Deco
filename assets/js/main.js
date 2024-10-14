// // Slider Avant-Après
const container = document.querySelector(".container");

document.querySelector(".slider").addEventListener("input", (e) => {
  container.style.setProperty("--position", `${e.target.value}%`);
});

// bxSlider Init
$(document).ready(function () {
  $(".bxslider").bxSlider({
    adaptiveHeight: true,
    slideWidth: 600,
  });
});

// Suppression des balises vides
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll("p").forEach(function (p) {
    if (p.textContent.trim() === "") {
      p.remove();
    }
  });

  document.querySelectorAll("h1").forEach(function (h1) {
    if (h1.textContent.trim() === "") {
      h1.remove();
    }
  });
});
