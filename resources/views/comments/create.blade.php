<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact V10</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.rawgit.com/kevinchappell/tinymce-mention/master/dist/tinymce.mention.min.js"></script>

    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance',
            plugins: 'mention powerpaste advcode table lists checklist',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist checklist | code | table',
            mentions: {
                source: function(query, success) {
                    // Your mention source logic here
                    var users = [{
                            username: 'user1',
                            display: 'User One'
                        },
                        {
                            username: 'user2',
                            display: 'User Two'
                        },
                        // Add more users as needed
                    ];

                    success(users);
                },
                itemRenderer: function(item, escape) {
                    return '<li>' +
                        '<span>' + escape(item.display) + '</span>' +
                        '</li>';
                }
            }
        });
    </script>

    <meta name="robots" content="noindex, follow">

    <style></style>
</head>

<body>
    <form method="post">
        <textarea id="myeditorinstance">Write your comment here</textarea>
    </form>
    <script>
        /* Script to import faker.js for generating random data for demonstration purposes */
        tinymce.ScriptLoader.loadScripts(['https://cdn.jsdelivr.net/npm/faker@5/dist/faker.min.js'], function() {

            /*
             ** This is to simulate requesting information from a server.
             **
             ** It has 2 functions:
             ** fetchUsers() - returns a complete list of users' ids and names.
             ** fetchUser(id) - returns the full information about a single user id.
             **
             ** Both of these functions have a slight delay to simulate a server request.
             */
            var fakeServer = (function() {
                /* Use TinyMCE's Promise shim */
                var Promise = tinymce.util.Promise;

                /* Some user profile images for our fake server (original source: unsplash) */
                var images = [
                    'Abdullah_Hadley', 'Abriella_Bond', 'Addilynn_Dodge', 'Adolfo_Hess',
                    'Alejandra_Stallings', 'Alfredo_Schafer', 'Aliah_Pitts', 'Amilia_Luna', 'Andi_Lane',
                    'Angelina_Winn', 'Arden_Dean', 'Ariyanna_Hicks', 'Asiya_Wolff', 'Brantlee_Adair',
                    'Carys_Metz', 'Daniela_Dewitt', 'Della_Case', 'Dianna_Smiley', 'Eliana_Stout',
                    'Elliana_Palacios', 'Fischer_Garland', 'Glen_Rouse', 'Grace_Gross', 'Heath_Atwood',
                    'Jakoby_Roman', 'Judy_Sewell', 'Kaine_Hudson', 'Kathryn_Mcgee', 'Kayley_Dwyer',
                    'Korbyn_Colon', 'Lana_Steiner', 'Loren_Spears', 'Lourdes_Browning',
                    'Makinley_Oneill', 'Mariana_Dickey', 'Miyah_Myles', 'Moira_Baxter',
                    'Muhammed_Sizemore', 'Natali_Craig', 'Nevaeh_Cates', 'Oscar_Khan',
                    'Rodrigo_Hawkins', 'Ryu_Duke', 'Tripp_Mckay', 'Vivianna_Kiser', 'Yamilet_Booker',
                    'Yarely_Barr', 'Zachary_Albright', 'Zahir_Mays', 'Zechariah_Burrell'
                ];

                /* Create an array of 200 random names using faker.js */
                var userNames = [];
                for (var i = 0; i < 200; i++) {
                    userNames.push(faker.name.findName());
                };

                /* This represents a database of users on the server */
                var userDb = {};
                userNames.map(function(fullName) {
                    var id = fullName.toLowerCase().replace(/ /g, '');
                    return {
                        id: id,
                        name: fullName,
                        fullName: fullName,
                        description: faker.name.jobTitle(),
                        image: '/docs/images/unsplash/uifaces-unsplash-portrait-' + images[Math
                            .floor(images.length * Math.random())] + '.jpg'
                    };
                }).forEach(function(user) {
                    userDb[user.id] = user;
                });

                /* This represents getting the complete list of users from the server with the details required for the mentions "profile" item */
                var fetchUsers = function() {
                    return new Promise(function(resolve, _reject) {
                        /* simulate a server delay */
                        setTimeout(function() {
                            var users = Object.keys(userDb).map(function(id) {
                                return {
                                    id: id,
                                    name: userDb[id].name,
                                    image: userDb[id].image,
                                    description: userDb[id].description
                                };
                            });
                            resolve(users);
                        }, 500);
                    });
                };

                /* This represents requesting all the details of a single user from the server database */
                var fetchUser = function(id) {
                    return new Promise(function(resolve, reject) {
                        /* simulate a server delay */
                        setTimeout(function() {
                            if (Object.prototype.hasOwnProperty.call(userDb, id)) {
                                resolve(userDb[id]);
                            }
                            reject('unknown user id "' + id + '"');
                        }, 300);
                    });
                };

                return {
                    fetchUsers: fetchUsers,
                    fetchUser: fetchUser
                };
            })();

            /* These are "local" caches of the data returned from the fake server */
            var usersRequest = null;
            var userRequest = {};

            var mentions_fetch = function(query, success) {
                /* Fetch your full user list from somewhere */
                if (usersRequest === null) {
                    usersRequest = fakeServer.fetchUsers();
                }
                usersRequest.then(function(users) {
                    /* `query.term` is the text the user typed after the '@' */
                    users = users.filter(function(user) {
                        return user.name.indexOf(query.term.toLowerCase()) !== -1;
                    });

                    users = users.slice(0, 10);

                    /* Where the user object must contain the properties `id` and `name`
                      but you could additionally include anything else you deem useful. */
                    success(users);
                });
            };

            var mentions_menu_hover = function(userInfo, success) {
                /* Request more information about the user from the server and cache it locally */
                if (!userRequest[userInfo.id]) {
                    userRequest[userInfo.id] = fakeServer.fetchUser(userInfo.id);
                }
                userRequest[userInfo.id].then(function(userDetail) {
                    var div = document.createElement('div');

                    div.innerHTML = (
                        '<div class="card">' +
                        '<img class="avatar" src="' + userDetail.image + '"/>' +
                        '<h1>' + userDetail.fullName + '</h1>' +
                        '<p>' + userDetail.description + '</p>' +
                        '</div>'
                    );

                    success(div);
                });
            };

            var mentions_menu_complete = function(editor, userInfo) {
                var span = editor.getDoc().createElement('span');
                span.className = 'mymention';
                span.setAttribute('data-mention-id', userInfo.id);
                span.appendChild(editor.getDoc().createTextNode('@' + userInfo.name));
                return span;
            };

            var mentions_select = function(mention, success) {
                /* `mention` is the element we previously created with `mentions_menu_complete`
                  in this case we have chosen to store the id as an attribute */
                var id = mention.getAttribute('data-mention-id');
                /* Request more information about the user from the server and cache it locally */
                if (!userRequest[id]) {
                    userRequest[id] = fakeServer.fetchUser(id);
                }
                userRequest[id].then(function(userDetail) {
                    var div = document.createElement('div');
                    div.innerHTML = (
                        '<div class="card">' +
                        '<img class="avatar" src="' + userDetail.image + '"/>' +
                        '<h1>' + userDetail.fullName + '</h1>' +
                        '<p>' + userDetail.description + '</p>' +
                        '</div>'
                    );
                    success(div);
                });
            };

            tinymce.init({
                selector: 'textarea#mentions',
                plugins: 'mentions',
                content_style: '.mymention{ color: gray; }' +
                    'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',

                mentions_selector: '.mymention',
                mentions_fetch: mentions_fetch,
                mentions_menu_hover: mentions_menu_hover,
                mentions_menu_complete: mentions_menu_complete,
                mentions_select: mentions_select,
                mentions_item_type: 'profile'
            });
        });
    </script>
</body>

</html>



{{-- <div class="row d-flex justify-content-center container-contact100"> --}}
{{-- <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab"
                    data-toggle="tab">Home</a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab"
                    data-toggle="tab">Profile</a></li>
            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab"
                    data-toggle="tab">Messages</a></li>
            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab"
                    data-toggle="tab">Settings</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">home</div>
            <div role="tabpanel" class="tab-pane" id="profile">profile</div>
            <div role="tabpanel" class="tab-pane" id="messages">messages</div>
            <div role="tabpanel" class="tab-pane" id="settings">settings</div>
        </div>

    </div> --}}
{{-- <div class="col-md-8 col-lg-6">
        <div class="card shadow-0 border" style="background-color: #f0f2f5;">
            <div class="card-body p-4">
                <div class="form-outline mb-4">
                    <input type="text" id="addANote" class="form-control" placeholder="Type comment..." />
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <p>Type your note, and hit enter to add it</p>

                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(4).webp" alt="avatar"
                                    width="25" height="25" />
                                <p class="small mb-0 ms-2">Martha</p>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                                <p class="small text-muted mb-0">Upvote?</p>
                                <i class="far fa-thumbs-up mx-2 fa-xs text-black" style="margin-top: -0.16rem;"></i>
                                <p class="small text-muted mb-0">3</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
{{-- </div> --}}
