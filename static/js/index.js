const btnLogin = document.getElementById("login");
const btnLogout = document.getElementById("logout");

const request = new XMLHttpRequest();

const handleResponse = function () {
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
        window.location.href = "login.html";
    });
}

if (btnLogout !== null) {
    btnLogout.addEventListener("click", () => {
        request.onreadystatechange = handleResponse;
        request.open("GET", "api/logout.php");
        request.send();
    });
}
