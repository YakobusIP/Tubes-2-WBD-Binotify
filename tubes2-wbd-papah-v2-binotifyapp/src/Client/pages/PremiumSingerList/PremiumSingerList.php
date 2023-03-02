<?php 
    namespace Client\pages\PremiumSingerList;
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Client/pages/PremiumSingerList/PremiumSingerList.css">
        <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
        <title>Binotify - Premium Singer List</title>
    </head>
    <body>
    <div id="split_page">
            <?php if (!isset($_SESSION["isadmin"])){
                    include "Client/components/NavbarNonUser/sidebar_nonuser.php";
                }
                elseif($_SESSION["isadmin"]){
                    include "Client/components/NavbarAdmin/sidebar_admin.php";
                }
                else{
                    include "Client/components/NavbarUser/sidebar_user.php";
                }
            ?> 
            <main id="main_body">
                <?php if (!isset($_SESSION["isadmin"])){
                        include "Client/components/NavbarNonUser/topbar_nonuser.php";
                    }
                    elseif($_SESSION["isadmin"]){
                        include "Client/components/NavbarAdmin/topbar_admin.php";
                    }
                    else{
                        include "Client/components/NavbarUser/topbar_user.php";
                    }
                ?> 
                <section id="singer_list">
                    <div class="header">
                        <h1>PREMIUM SINGER LIST</h1>
                    </div>
                    <div class="singer-list">
                        <hr />
                        <?php foreach($users as $key=>$user): ?>
                        <div class="singer-item">
                            <div id="<?= 'user_' . $user["user_id"]; ?>">
                                <h3><?= $user["name"]; ?></h3>
                                <h4>@<?= $user["username"]; ?></h4>
                            </div>
                            <?php if($key < count($statuses)): ?>
                                <?php if($statuses[$key]['status'] === 'PENDING' && $statuses[$key]['creator_id'] === $user['user_id']): ?>
                                    <div id="<?= 'waittext_' . $user["user_id"]; ?>" class="awaiting">Awaiting Approval</div>
                                <?php elseif($statuses[$key]['status'] === 'ACCEPTED' && $statuses[$key]['creator_id'] === $user['user_id']): ?>
                                    <button id="<?= 'buttonview_' . $user["user_id"]; ?>" class="singer-subscribe" onclick={view_song(event)}>
                                        View Songs
                                    </button>
                                <?php elseif($statuses[$key]['status'] === 'REJECTED' && $statuses[$key]['creator_id'] === $user['user_id']): ?>
                                    <div id="<?= 'rejectedtext_' . $user["user_id"]; ?>" class="awaiting">Subscription Rejected</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <button id="<?= 'buttonsubscribe_' . $user["user_id"]; ?>" class="singer-subscribe" onclick={subscribe(event)}>
                                    Subscribe
                                </button>
                            <?php endif; ?>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                </section>
            </main>
        </div>
    </body>
    <script>
        
        const subscribe = (e) => {
            e.preventDefault();
            const creator_id = (e.target.id).split('_')[1];
            

            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    window.location.reload();
                }
            }
            xmlhttp.open('POST', '/subscribe');
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(`creator_id=${creator_id}`);            
        }

        const view_song = (e) => {
            e.preventDefault();
            const creator_id = (e.target.id).split('_')[1];
            document.getElementById("buttonview_" + creator_id).onclick = function() {
                window.location.href=`/premium-song-list?singer_id=${creator_id}&subs_id=<?= $_SESSION['user_id']; ?>`;
            }
        }
        
        const poll_data = () => {
            const xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    window.location.reload();
                }
            }

            xmlhttp.open('GET', '/poll-data');
            xmlhttp.send();
            
        }

        // window.onload = function() {
        //     poll_data();
        // }
        
        setInterval(poll_data, 10000);
        
    </script>
</html>