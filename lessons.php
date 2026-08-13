<?php
// PHP code must run before any HTML output when setting cookies.
$hasSubmittedForm = ($_SERVER["REQUEST_METHOD"] == "POST");
$name = trim($_POST["name"] ?? ($_COOKIE["roomconnect_name"] ?? "Student"));
$university = trim($_POST["university"] ?? ($_COOKIE["roomconnect_university"] ?? "WFI - Ingolstadt School of Management"));
$email = trim($_POST["email"] ?? ($_COOKIE["roomconnect_email"] ?? "verified@stud.ku.de"));
$matchedRoommate = trim($_POST["matched_roommate"] ?? ($_COOKIE["roomconnect_roommate"] ?? "Verified Student A"));

if ($hasSubmittedForm) {
    setcookie("roomconnect_name", $name, time() + (86400 * 30), "/");
    setcookie("roomconnect_university", $university, time() + (86400 * 30), "/");
    setcookie("roomconnect_email", $email, time() + (86400 * 30), "/");
    setcookie("roomconnect_roommate", $matchedRoommate, time() + (86400 * 30), "/");
}

function cleanOutput($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

// Simulated Landlord Data array with coordinates around Ingolstadt center & THI
$landlords = [
    [
        "id" => 1,
        "name" => "WG Westpark (Herr Johann)",
        "price" => 430,
        "phone" => "+49 841 555 0192",
        "lat" => 48.7665,
        "lng" => 11.4015,
        "desc" => "2 rooms available, easy bike distance to WFI and Westpark shopping mall.",
        "outcome" => "accept"
    ],
    [
        "id" => 2,
        "name" => "THI-Campus WG Studio (Frau Schmidt)",
        "price" => 450,
        "phone" => "+49 841 555 0243",
        "lat" => 48.7642,
        "lng" => 11.4248,
        "desc" => "Central historical apartment, shared kitchen, close to THI campus.",
        "outcome" => "reject"
    ],
    [
        "id" => 3,
        "name" => "Wohnheim level21 (Donau Living)",
        "price" => 410,
        "phone" => "+49 841 555 0781",
        "lat" => 48.7592,
        "lng" => 11.4334,
        "desc" => "Quiet apartment across the river, ideal for study-focused students.",
        "outcome" => "accept"
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="RoomConnect board, landlord approval, cookies, WFI, Ingolstadt Map">
    <title>RoomConnect | Dashboard & Map</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css">
    
    <style>
        .split-dashboard-layout {
            display: flex;
            gap: 20px;
            height: calc(100vh - 210px);
            min-height: 550px;
        }
        .left-panel {
            width: 40%;
            display: flex;
            flex-direction: column;
        }
        .right-panel {
            width: 60%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .chat-window {
            flex: 1;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 15px;
            background: #fff;
            max-height: 440px;
        }
        #map {
            flex: 1;
            width: 100%;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            z-index: 1;
        }
        .room-grid-horizontal {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
        }
        .room-card-clickable {
            min-width: 200px;
            flex: 1;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .room-card-clickable:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        @media (max-width: 991px) {
            .split-dashboard-layout {
                flex-direction: column;
                height: auto;
            }
            .left-panel, .right-panel {
                width: 100%;
            }
            #map {
                height: 350px;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand brand-mark" href="index.html">
            <img src="roomconnect_nav.svg" alt="RoomConnect logo" class="brand-logo-full">
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <div class="navbar-nav ms-auto align-items-lg-center">
                <a class="nav-link" href="index.html">Home</a>
                <a class="nav-link" href="quiz.html">Quiz</a>
                <a class="nav-link active" href="lessons.php">Shared Dashboard</a>
                <a class="nav-link" href="trust-safety.html">Trust</a>
                <a class="nav-link" href="pricing.html">Pricing</a>
                <a class="nav-link" href="about.html">About</a>

<div class="nav-user-inline ms-lg-3 mt-3 mt-lg-0">
    <span class="nav-user-dot"></span>
    <span class="nav-user-inline-name"><?php echo cleanOutput($name); ?></span>
                </div>
            </div>
        </div>
    </div>
</nav>

<main class="section-padding bg-soft min-vh-100 py-4">
    <div class="container-fluid px-4">
        <div class="content-card p-4 bg-white shadow-sm rounded">
            <span class="badge trust-badge bg-teal mb-3">Milestones 3 to 5: shared agreement and map verification</span>
            <h1>RoomConnect Dashboard</h1>
            <p class="lead text-muted">
                Welcome, <?php echo cleanOutput($name); ?>. Your matched roommate is <strong><?php echo cleanOutput($matchedRoommate); ?></strong>.
            </p>

            <?php if (!$hasSubmittedForm): ?>
                <div class="alert alert-warning py-2">
                    This dashboard works after the quiz acceptance.
                </div>
            <?php endif; ?>

            <div class="split-dashboard-layout mt-4">
                
                <section class="left-panel border p-3 rounded bg-light">
                    <h2 class="h4 mb-3">✉️ Live Match Chat Box</h2>
                    <div class="agreement-summary mb-2 d-flex gap-1 flex-wrap">
                        <span class="badge bg-success">Both Students Verified</span>
                        <span class="badge bg-success">Match Accepted</span>
                        <span class="badge bg-info">Ingolstadt Core</span>
                    </div>

                    <div id="chatWindow" class="chat-window mb-3">
                        <div class="chat-message host-message mb-3">
                            <span class="badge bg-secondary mb-1"><?php echo cleanOutput($matchedRoommate); ?></span>
                            <div class="p-2 bg-white rounded border">Hi! I accepted the match request. Our lifestyle answers look highly compatible.</div>
                        </div>
                        <div class="chat-message student-message mb-3 text-end">
                            <span class="badge bg-primary mb-1"><?php echo cleanOutput($name); ?></span>
                            <div class="p-2 bg-primary text-white rounded text-start d-inline-block">Great! I agree on target budget: Max €450. Let's look on the map on the right side to pick one.</div>
                        </div>
                    </div>

                    <form id="liveChatForm" class="d-flex gap-2 mb-2">
                        <label for="liveChatMessage" class="visually-hidden">Chat message</label>
                        <input type="text" id="liveChatMessage" class="form-control" placeholder="Type a message to your roommate..." required>
                        <button type="submit" class="btn btn-primary px-4">Send</button>
                    </form>
                </section>

                <section class="right-panel border p-3 rounded bg-light">
                    <h2 class="h4 mb-3">📍 Interactive WG & Room Selection Map</h2>
                    
                    <div id="map"></div>

                    <div class="room-grid-horizontal mt-3">
                        <?php foreach($landlords as $ll): ?>
                            <article class="room-card-clickable" onclick="focusOnLandlord(<?php echo $ll['lat']; ?>, <?php echo $ll['lng']; ?>, '<?php echo cleanOutput($ll['name']); ?>')">
                                <h3 class="h6 mb-1 text-truncate"><?php echo cleanOutput($ll['name']); ?></h3>
                                <div class="text-success fw-bold small mb-1">€<?php echo cleanOutput($ll['price']); ?> / month</div>
                                <div class="text-muted style-phone small">📞 <?php echo cleanOutput($ll['phone']); ?></div>
                                <button class="btn btn-sm btn-outline-primary w-100 mt-2 reserve-btn" type="button"
                                    data-landlord-id="<?php echo (int) $ll['id']; ?>"
                                    data-outcome="<?php echo cleanOutput($ll['outcome']); ?>">
                                    Joint Reserve
                                </button>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>

            </div> </div>
    </div>
</main>

<div id="landlordOverlay" class="landlord-overlay d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 9999;">
    <div class="landlord-box bg-white p-5 rounded text-center shadow-lg max-w-md mx-3">
        <div id="reviewState">
            <div class="spinner-border text-primary mb-3" role="status"></div>
            <h2>Landlord reviewing your verified 2-student profile...</h2>
            <p class="text-muted">Please wait while the room reservation cycle is simulated.</p>
        </div>

        <div id="successState" class="d-none">
            <div class="text-success display-4 mb-2">✓</div>
            <h2>Reservation Accepted!</h2>
            <p class="text-muted">You have secured your room package. RoomConnect submission workflow completed successfully.</p>
            <a href="index.html" class="btn btn-success mt-3">Back to Home Workspace</a>
        </div>

        <div id="rejectedState" class="d-none">
            <div class="text-danger display-4 mb-2">✕</div>
            <h2>Reservation not approved</h2>
            <p class="text-muted mb-0">The landlord did not approve this booking request.</p>
            <button type="button" class="btn btn-teal mt-4" id="chooseAnotherRoomBtn">Choose another room</button>
        </div>
    </div>
</div>

<footer class="site-footer bg-white border-top text-center py-3">
    <div class="container">
        <p class="mb-0 text-muted small">RoomConnect mockup for Digital Business Models & Technologies.</p>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 1. Map Initialization Centered on Ingolstadt Center Node Coordinates
    const map = L.map('map').setView([48.7656, 11.4237], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // 2. Safely Injecting the PHP Landlord Data Array Matrix mapping context directly into JavaScript Runtime
    const mapMarkersCollection = {};
    const landlordsDataset = <?php echo json_encode($landlords); ?>;

    landlordsDataset.forEach(function (property) {
        // Construct standard popups showing Name, Price per month, and Contact Phone number
        const popupContentLayout = `
            <div style="font-family: sans-serif; min-width: 160px;">
                <h5 style="margin: 0 0 5px 0; font-size:14px; color:#1e293b;">📍 ${property.name}</h5>
                <strong style="color: #198754; display:block; margin-bottom:4px;">Rent: €${property.price} / month</strong>
                <span style="font-size: 12px; color: #6c757d;">📞 Phone: ${property.phone}</span>
            </div>
        `;

        const pinMarkerInstance = L.marker([property.lat, property.lng])
            .addTo(map)
            .bindPopup(popupContentLayout);

        // Store reference to marker instance indexed by name string
        mapMarkersCollection[property.name] = pinMarkerInstance;
    });

    // 3. Synchronization Routine: Clicking a property entry item centers and targets map popups view
    function focusOnLandlord(latitude, longitude, targetName) {
        map.setView([latitude, longitude], 15);
        if (mapMarkersCollection[targetName]) {
            mapMarkersCollection[targetName].openPopup();
        }
    }

    // 4. Modal Simulation Interactivity Control Routines
    var overlay = document.getElementById("landlordOverlay");
    var reviewState = document.getElementById("reviewState");
    var successState = document.getElementById("successState");
    var rejectedState = document.getElementById("rejectedState");
    var chooseAnotherRoomBtn = document.getElementById("chooseAnotherRoomBtn");
    var liveChatForm = document.getElementById("liveChatForm");
    var liveChatMessage = document.getElementById("liveChatMessage");
    var chatWindow = document.getElementById("chatWindow");
    
    var roommateReplies = [
        "Sounds good to me. I also want to stay under €450.",
        "Yes, I agree. That looks like the best option so far.",
        "Great idea. We can reserve together after checking the details.",
        "I am okay with that. The compatibility score makes me confident.",
        "Perfect, let's continue with the shared room selection."
    ];

    function resetLandlordOverlay() {
        overlay.classList.add("d-none");
        reviewState.classList.remove("d-none");
        successState.classList.add("d-none");
        rejectedState.classList.add("d-none");
    }

    document.querySelectorAll(".reserve-btn").forEach(function (button) {
        button.addEventListener("click", function (e) {
            e.stopPropagation();

            var outcome = button.getAttribute("data-outcome") || "accept";

            overlay.classList.remove("d-none");
            reviewState.classList.remove("d-none");
            successState.classList.add("d-none");
            rejectedState.classList.add("d-none");

            setTimeout(function () {
                reviewState.classList.add("d-none");

                if (outcome === "reject") {
                    rejectedState.classList.remove("d-none");
                } else {
                    successState.classList.remove("d-none");
                }
            }, 3000);
        });
    });

    chooseAnotherRoomBtn.addEventListener("click", resetLandlordOverlay);

    // 5. Simulated Live Chat Window Interactive Threading Engine 
    liveChatForm.addEventListener("submit", function (event) {
        event.preventDefault();
        var text = liveChatMessage.value.trim();

        if (text !== "") {
            var messageBox = document.createElement("div");
            messageBox.className = "chat-message student-message mb-3 text-end";
            messageBox.innerHTML = "<span class='badge bg-primary mb-1'><?php echo cleanOutput($name); ?></span><br><div class='p-2 bg-primary text-white rounded text-start d-inline-block'></div>";
            messageBox.querySelector("div").textContent = text;

            chatWindow.appendChild(messageBox);
            liveChatMessage.value = "";
            chatWindow.scrollTop = chatWindow.scrollHeight;

            setTimeout(function () {
                var replyBox = document.createElement("div");
                var randomReply = roommateReplies[Math.floor(Math.random() * roommateReplies.length)];

                replyBox.className = "chat-message host-message mb-3";
                replyBox.innerHTML = "<span class='badge bg-secondary mb-1'><?php echo cleanOutput($matchedRoommate); ?></span><div class='p-2 bg-white rounded border'></div>";
                replyBox.querySelector("div").textContent = randomReply;

                chatWindow.appendChild(replyBox);
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }, 900);
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>