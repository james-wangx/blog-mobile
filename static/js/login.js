const btnLogin = document.querySelector(".login");

const request = new XMLHttpRequest();

const handleResponse = function () {
    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status === 200) {
            const response = JSON.parse(request.responseText);
            alert(response.message);
            if (response.status === 200) {
                window.location.href = "index.php";
            }
        } else {
            alert("请求出现了问题。");
        }
    }
}

btnLogin.addEventListener("click", () => {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    request.onreadystatechange = handleResponse;
    request.open("POST", "api/login.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(`username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
});
