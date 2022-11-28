const base_url = "http://127.0.0.1:8000/api/";

const post_data = async function (url = "", data = {}) {
    url = base_url + url;
    const response = await fetch(url, {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, *cors, same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
            "Content-Type": "application/json",
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: "follow", // manual, *follow, error
        referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify(data), // body data type must match "Content-Type" header
    });
    return response.json(); // parses JSON response into native JavaScript objects
};

const get_data = async function (url) {
    url = base_url + url;
    const response = await fetch(url, {
        method: "GET", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, *cors, same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
            "Content-Type": "application/json",
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: "follow", // manual, *follow, error
        referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    });
    return response.json(); // parses JSON response into native JavaScript objects
};

// const auto_toggle = function (toggle_btn, main, dependents_element) {
//     const toggle_btn = document.querySelector(toggle_btn);
//     const main = document.querySelector(main);
//     toggle_btn.onclick = () => main.classList.toggle("active");
//     window.addEventListener("click", handle_click);
//     function handle_click(e) {
//         if (!main.contains(e.target) && !toggle_btn.contains(e.target)) {
//             let is_accept = true;
//             if (dependents_element) {
//                 dependents_element.forEach((dependent_e) => {
//                     if (document.querySelector(dependent_e).contains(e.target))
//                         is_accept = false;
//                 });
//             }
//             if (is_accept) main.classList.remove("active");
//         }
//     }
// };
