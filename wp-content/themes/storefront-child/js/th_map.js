document.addEventListener("DOMContentLoaded", function () {
    function initMap() {
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 6,
            center: { lat: 55.3781, lng: -3.4360 }, // UK default
            mapId: "MIKE_MAP_ID",
        });

        const geocoder = new google.maps.Geocoder();
        let markers = [];

        fetch(ajaxurl + "?action=get_user_addresses")
            .then(response => response.json())
            .then(data => {
                console.log("Locații primite:", data);

                data.forEach(user => {
                    geocoder.geocode({ address: user.postcode }, (results, status) => {
                        if (status === "OK" && results[0]) {
                            const position = results[0].geometry.location;

                            let iconUrl;
                            if (user.group.trim() === "alex_clients") {
                                iconUrl = "https://maps.google.com/mapfiles/ms/icons/blue-dot.png";
                            } else if (user.group.trim() === "andrew_clients") {
                                iconUrl = "https://maps.google.com/mapfiles/ms/icons/red-dot.png";
                            } else {
                                iconUrl = "https://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
                            }

                            let marker;
                            if (google.maps.marker && google.maps.marker.AdvancedMarkerElement) {
                                const iconElement = document.createElement("img");
                                iconElement.src = iconUrl;
                                iconElement.width = 40;
                                iconElement.height = 40;

                                marker = new google.maps.marker.AdvancedMarkerElement({
                                    map: map,
                                    position: position,
                                    title: user.name,
                                    content: iconElement,
                                });
                            } else {
                                marker = new google.maps.Marker({
                                    position: position,
                                    map: map,
                                    title: user.name,
                                    icon: {
                                        url: iconUrl,
                                        scaledSize: new google.maps.Size(40, 40),
                                    },
                                });
                            }

                            markers.push({ marker, group: user.group.trim(), baseIcon: iconUrl });

                            const infoWindow = new google.maps.InfoWindow({
                                content: `<strong>${user.name}<br>Tel: <a href="tel:${user.phone}">${user.phone}</a><br>${user.fname} ${user.lname}<br><a target="_blank" href="${user.webst}">${user.webst}</a></strong>`,
                            });

                            marker.addListener?.("click", function () {
                                infoWindow.open(map, marker);
                            });
                        } else {
                            console.error("Geocoding eșuat pentru " + user.postcode + " cu status: " + status);
                        }
                    });
                });

                setupFilters(markers, map);
            })
            .catch(error => console.error("Eroare la preluarea adreselor:", error));
    }

    function setupFilters(markers, map) {
        document.querySelectorAll(".filter-btn").forEach(button => {
            button.addEventListener("click", function () {
                const selectedGroup = this.getAttribute("data-group");

                markers.forEach(({ marker, group, baseIcon }) => {
                    if (selectedGroup === "all") {
                        marker.map = map;

                        if (group === "alex_clients" || group === "andrew_clients") {
                            marker.icon = { url: baseIcon, scaledSize: new google.maps.Size(40, 40) };
                        } else {
                            marker.icon = { url: "https://maps.google.com/mapfiles/ms/icons/yellow-dot.png", scaledSize: new google.maps.Size(40, 40) };
                        }
                    } else if (group === selectedGroup) {
                        marker.map = map;
                    } else {
                        marker.map = null;
                    }
                });
            });
        });
    }

    initMap();
});
