document.querySelectorAll("[depend-on]").forEach((dependentSelect) => {
    let parent = document.getElementById(dependentSelect.getAttribute("depend-on"));
    parent.addEventListener("change", (event) => {
        Array.from(dependentSelect.children).forEach((option) => {
            if (option.dataset.parent != event.target.value) {
                option.setAttribute("disabled", true);
                option.style.display = "none";
            } else {
                option.removeAttribute("disabled");
                option.style.display = null;
            }
        });

        let visibles = Array.from(dependentSelect.children).filter(option => !option.hasAttribute('disabled'))
        let visibleSelected = visibles.filter(option => option.hasAttribute('selected'));
        if (visibleSelected.length) {
            dependentSelect.value = visibleSelected[0].getAttribute('value')
        } else if (visibles.length) {
            dependentSelect.value = visibles[0].getAttribute('value')
        } else {
            dependentSelect.value = null
        }

        dependentSelect.dispatchEvent(new CustomEvent("change", {bubbles: true,composed: true,cancelable: true}))
    });

    if (!parent.getAttribute("depend-on")) {
        setTimeout(() => {
            parent.dispatchEvent(new CustomEvent("change", {bubbles: true,composed: true,cancelable: true}))
        }, 10);
    }
});
