import {ROOT} from "./config.js";

const btnLogin = document.getElementById("login");
const btnLogout = document.getElementById("logout");
const btnAdmin = document.getElementById("admin");
const btnIndex = document.getElementById("index");

const request = new XMLHttpRequest();

const handleResponseLogout = function () {
    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status === 200) {
            const response = JSON.parse(request.responseText);
            if (response.status === 200) {
                window.location.reload();
            }
        } else {
            alert("请求出现了问题。");
        }
    }
}

if (btnLogin !== null) {
    btnLogin.addEventListener("click", () => {
        window.location.href = `${ROOT}/login.html`;
    });
}

if (btnLogout !== null) {
    btnLogout.addEventListener("click", () => {
        request.onreadystatechange = handleResponseLogout;
        request.open("GET", `${ROOT}/api/logout.php`);
        request.send();
    });
}

if (btnAdmin !== null) {
    btnAdmin.addEventListener("click", () => {
        window.location.href = `${ROOT}/admin/index.php`;
    });
}

if (btnIndex !== null) {
    btnIndex.addEventListener("click", () => {
        window.location.href = `${ROOT}/index.php`;
    })
}
