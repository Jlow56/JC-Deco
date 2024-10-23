//...................//
//    *Général*     // 
//*****************//

// Suppression des balises vides
document.addEventListener("DOMContentLoaded", function () {
  // Fonction pour supprimer les éléments vides
  function removeEmptyElements(selector) {
    document.querySelectorAll(selector).forEach(function (element) {
      if (element.textContent.trim() === "") {
        element.remove();
      }
    });
  }
  // Supprimer les paragraphes et les titres vides
  ["p", "h1", "h2", "h3", "h4"].forEach(removeEmptyElements);
});

document.addEventListener("DOMContentLoaded", function () {
  // Fonction pour trouver un fieldset par son legend
  function getFieldsetByLegend(legendText) {
    const legends = document.querySelectorAll("fieldset > legend");
    for (let legend of legends) {
      if (legend.textContent.trim() === legendText) {
        return legend.parentElement;
      }
    }
    return null; // Retourne null si le fieldset n'est pas trouvé
  }

  // Fonction pour mettre à jour l'affichage du formulaire en fonction des cases cochées
  function updateFormDisplay() {
    const peintureDecorative = document.getElementById("decorative")?.checked;
    const peintureBasique = document.getElementById("basique")?.checked;
    const poseLames = document.getElementById("pvc")?.checked;

    const poseLamesFieldset = getFieldsetByLegend("Pose de lames clipsées");
    const peintureFieldset = getFieldsetByLegend("Peinture");

    if (!poseLamesFieldset || !peintureFieldset) return; // Sécurité si les fieldsets ne sont pas trouvés

    // Si peinture est cochée, on masque la section "Pose de lames clipsées"
    if (peintureDecorative || peintureBasique) {
      poseLamesFieldset.style.display = "none";
      clearInputs(poseLamesFieldset);
    } else {
      poseLamesFieldset.style.display = "block";
    }

    // Si pose de lames clipsées est cochée, on masque la section "Peinture"
    if (poseLames) {
      peintureFieldset.style.display = "none";
      clearInputs(peintureFieldset);
    } else {
      peintureFieldset.style.display = "block";
    }

    // Si tout est coché, on affiche tout
    if ((peintureDecorative || peintureBasique) && poseLames) {
      poseLamesFieldset.style.display = "block";
      peintureFieldset.style.display = "block";
    }
  }

  // Fonction pour vider les champs masqués (empêche que les champs invisibles soient envoyés)
  function clearInputs(fieldset) {
    const inputs = fieldset.querySelectorAll("input");
    inputs.forEach((input) => {
      if (input.type === "checkbox" || input.type === "radio") {
        input.checked = false;
      } else {
        input.value = "";
      }
    });
  }

  // Validation du formulaire en prenant en compte uniquement les champs visibles
  function validateCheckboxGroups() {
    const groups = [
      "services_type[]",
      "services[]",
      "painting_surface_type[]",
      "color[]",
      "status[]",
      "surface_material[]",
      "pvc_surface_type[]",
      "date[]",
    ];

    for (let groupName of groups) {
      const checkboxes = document.querySelectorAll(
        `input[name="${groupName}"]`
      );
      const visibleCheckboxes = Array.from(checkboxes).filter(
        (checkbox) => checkbox.offsetParent !== null // Vérifie si l'élément est visible
      );
      const isChecked = visibleCheckboxes.some((checkbox) => checkbox.checked);

      if (!isChecked) {
        alert("Veuillez sélectionner au moins une option pour chaque groupe.");
        return false; // Empêche la soumission si aucun choix n'est fait
      }
    }

    return true; // Autorise la soumission si tous les groupes sont valides
  }

  // Écouteur d'événements pour mettre à jour l'affichage lorsque les cases sont cochées/décochées
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');

  if (checkboxes.length > 0) {
    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener("change", updateFormDisplay);
    });
  }

  // Mise à jour initiale au chargement de la page
  updateFormDisplay();
});

//*******************/
//  *Réalisations*  /
//******************/

// bxSlider Init
$(document).ready(function () {
  $(".bxslider").bxSlider({
    adaptiveHeight: true,
    slideWidth: 600,
  });
});

// Slider Avant-Après
const container = document.querySelector(".container");

document.querySelector(".slider").addEventListener("input", (e) => {
  container.style.setProperty("--position", `${e.target.value}%`);
});

//*******************//
//      *ADMIN*     //
//******************//

document.getElementById('login').addEventListener('submit', function(event) {
  const password = document.getElementById('password').value;
  if (password.length < 6) {
      event.preventDefault();
      document.getElementById('passwordError').textContent = "Le mot de passe doit comporter au moins 6 caractères.";
  }
});