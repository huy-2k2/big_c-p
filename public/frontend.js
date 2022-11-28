const auto_toggle = function (
    select_toggle_btn,
    select_main,
    dependents_element
) {
    const toggle_btn = document.querySelector(select_toggle_btn);
    const main = document.querySelector(select_main);
    toggle_btn.onclick = () => main.classList.toggle("active");
    window.addEventListener("click", handle_click);
    function handle_click(e) {
        if (!main.contains(e.target) && !toggle_btn.contains(e.target)) {
            let is_accept = true;
            if (dependents_element) {
                dependents_element.forEach((dependent_e) => {
                    if (document.querySelector(dependent_e).contains(e.target))
                        is_accept = false;
                });
            }
            if (is_accept) main.classList.remove("active");
        }
    }
    return () => window.removeEventListener("click", handle_click);
};
