<!DOCTYPE html>
<html>
    <head>
        <title>wo</title>
        <link href="css/styles.css" rel="stylesheet" type="text/css">
    </head>

    <body>

        <?php include __DIR__.'/template.html'; ?>

        <script>
            var loginElement = document.getElementById('login');
            loginElement.onclick = login;

            function login() {
                var request = new XMLHttpRequest();
                request.addEventListener("load", loginListener);
                request.open("POST", "http://stg.ericmarty.local/wo/api/authenticate");
                //request.open("GET", "http://stg.ericmarty.local/wo/api/22/programs/new");
                var requestData = JSON.stringify({email: "admin@localhost", password: "password"});
                request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                request.send(requestData);
            }

            function loginListener() {
                responseData = JSON.parse(this.responseText);
                console.log(this.status);
                console.log(responseData);
                document.getElementById('login').outerHTML = 'Welcome, ' + responseData['user'];
            }
        </script>

 
    </body>
</html>
