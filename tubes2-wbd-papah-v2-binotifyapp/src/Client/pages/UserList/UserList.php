<?php 
    namespace Client\pages\UserList;
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Client/pages/UserList/UserList.css">
        <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
        <title>Binotfy - User List</title>
    </head>
    <body>
        <div id="split_page">
            <?php include "Client/components/NavbarAdmin/sidebar_admin.php"; ?>
            <main id="main_body">
                <?php include "Client/components/NavbarAdmin/topbar_admin.php"; ?>
                <section id="user_list">
                    <h1>USER LIST</h1>
                    <div id="user_div">
                        <table id="user_table">
                            <tr class="table_header">
                                <th>No</th>
                                <th>Email</th>
                                <th>Full Name</th>
                                <th>Username</th>
                            </tr>
                        </table>
                    </div>    
                    
                </section>
            </main>
        </div>
    </body>
    <script>
        const getUserData = () => {
            const xmlhttp = new XMLHttpRequest();
            var counter = 1;

            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    console.log(this.response);
                    const data = JSON.parse(this.response);
                    var table = document.getElementById("user_table");

                    data.map((user) => {
                        var tr = document.createElement("tr");
                        var td_no = document.createElement("td");
                        var td_email = document.createElement("td");
                        var td_fullname = document.createElement("td");
                        var td_username = document.createElement("td");

                        td_no.appendChild(document.createTextNode(counter));
                        td_email.appendChild(document.createTextNode(user['email']));
                        td_fullname.appendChild(document.createTextNode(user['full_name']));
                        td_username.appendChild(document.createTextNode(user['username']));

                        counter++;
                        tr.appendChild(td_no);
                        tr.appendChild(td_email);
                        tr.appendChild(td_fullname);
                        tr.appendChild(td_username);

                        table.appendChild(tr);
                    })
                }
            }
            xmlhttp.open("GET", "/user-list/get-user-data");
            xmlhttp.send();
        }

        window.onload = function() {
            getUserData();
        }

    </script>
</html>