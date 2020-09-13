<html>
<header></header>

<body>
    <div><span>簡易平台</span></div>
    <br />
    <div id="logout_status">
        <div id="register"><span>register</span></div>
        <br />
        <div id="login"><span>sign in</span></div>
        <br />
    </div>
    <div id="login_status">
        <div id="logout"><span>logout</span></div>
        <br />
    </div>
    <div style="display: none" id="formView">
        <span id="title"></span><br />
        <form id="form">
            <span>帳號</span><input type="text" name="account" /> <span>密碼</span><input type="password" name="password" />
        </form>
        <button id="send">送出</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
    <script>
        const formView = document.getElementById("formView"),
            feature = {};

        // Set config defaults when creating the instance
        const apiAxios = axios.create();

        apiAxios.interceptors.request.use((config) => {
            // axios 請求攔截器，加上 header
            config.timeout = 30000;
            config.headers.Accept = "application/json";
            config.headers["Content-Type"] = "application/json";

            const token = window.localStorage.getItem("token");
            if (token !== null) {
                config.headers.Authorization = token; // eslint-disable-line
            }
            return config;
        });

        window.onload = function() {
            const token = window.localStorage.getItem("token");
            if (token !== null) {
                document.getElementById('logout_status').remove();
            } else {
                document.getElementById('login_status').remove();
            }
        };

        feature.success = (data, type) => {
            switch (type) {
                case "login": // Alter defaults after instance has been created
                    window.localStorage.setItem("token", data.data.token);

                    location.reload();
                    break;
                case "register":
                    alert(data.data.message);

                    document.getElementById("form").reset();

                    document.getElementById("login").click();
                    break;
                case "logout":
                    window.localStorage.removeItem("token");

                    location.reload();
                    break;
            }
        };

        feature.error = (error) => {
            const errorHandle = error.response,
                data = errorHandle.data;

            if (errorHandle.status == 422 && "errors" in data) {
                const keyAry = Object.keys(data.errors);

                for (let i = 0; i < keyAry.length; i++) {
                    alert(data.errors[keyAry[i]]);
                }
                return;
            }

            if (errorHandle.status == 401 && "error" in data) {
                alert(data.error);

                return;
            }

            alert(data.message);
        };

        document.getElementById("send").addEventListener("click", function() {
            const form = document.getElementById("form"),
                formData = new FormData(form),
                type = formView.className;

            apiAxios.post(`/api/${type}`, formData)
                .then((data) => feature.success(data, type))
                .catch((err) => feature.error(err));
        });

        const title = document.getElementById("title");

        document.getElementById("login").addEventListener("click", function() {
            formView.classList.remove("register");
            formView.classList.add("login");
            formView.style.display = "block";
            title.innerText = "登入";
        });

        document.getElementById("register").addEventListener("click", function() {
            formView.classList.remove("login");
            formView.classList.add("register");
            formView.style.display = "block";
            title.innerText = "註冊";
        });

        document.getElementById("logout").addEventListener("click", function() {
            apiAxios.post(`/api/logout`, {})
                .then((data) => feature.success(data, 'logout'))
                .catch((err) => feature.error(err));
        });
    </script>
</body>

</html>
