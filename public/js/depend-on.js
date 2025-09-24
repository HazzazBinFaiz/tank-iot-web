/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/depend-on.js ***!
  \***********************************/
document.querySelectorAll("[depend-on]").forEach(function (dependentSelect) {
  var parent = document.getElementById(dependentSelect.getAttribute("depend-on"));
  parent.addEventListener("change", function (event) {
    Array.from(dependentSelect.children).forEach(function (option) {
      if (option.dataset.parent != event.target.value) {
        option.setAttribute("disabled", true);
        option.style.display = "none";
      } else {
        option.removeAttribute("disabled");
        option.style.display = null;
      }
    });
    var visibles = Array.from(dependentSelect.children).filter(function (option) {
      return !option.hasAttribute('disabled');
    });
    var visibleSelected = visibles.filter(function (option) {
      return option.hasAttribute('selected');
    });

    if (visibleSelected.length) {
      dependentSelect.value = visibleSelected[0].getAttribute('value');
    } else if (visibles.length) {
      dependentSelect.value = visibles[0].getAttribute('value');
    } else {
      dependentSelect.value = null;
    }

    dependentSelect.dispatchEvent(new CustomEvent("change", {
      bubbles: true,
      composed: true,
      cancelable: true
    }));
  });

  if (!parent.getAttribute("depend-on")) {
    setTimeout(function () {
      parent.dispatchEvent(new CustomEvent("change", {
        bubbles: true,
        composed: true,
        cancelable: true
      }));
    }, 10);
  }
});
/******/ })()
;