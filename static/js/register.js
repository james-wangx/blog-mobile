import {ROOT} from "./config.js";

const btnRegister = document.getElementById("register");
const request = new XMLHttpRequest();

export const handleResponse = function () {
    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status === 200) {
            const response = JSON.parse(request.responseText);
            if (response.status === 200) {
                window.location.href = `${ROOT}/login.html`;
            } else {
                alert(response.message);
            }
        } else {
            alert("请求出现了问题。");
        }
    }
}

btnRegister.addEventListener("click", () => {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const password2 = document.getElementById("password2").value;

    if (username.length > 8) {
        alert("用户名长度大于 8 位");
        return;
    }

    if (password.length < 6) {
        alert("密码长度小于 6 位");
        return;
    }

    if (password !== password2) {
        alert("确认密码不一致");
        return;
    }

    request.onreadystatechange = handleResponse;
    request.open("POST", `${ROOT}/api/register.php`)
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
});
