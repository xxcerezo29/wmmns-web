<script setup lang="ts">
interface waypoint {
    lat: number;
    lng: number;
}

import 'leaflet/dist/leaflet.css';
import L from 'leaflet';



import 'leaflet-routing-machine/dist/leaflet-routing-machine.css';
import 'leaflet-routing-machine';


import { onMounted, ref } from 'vue';

const props = defineProps<{
    // form: any;
    waypoint: Array<waypoint>
}>();

const mapContainer = ref<HTMLElement | null>(null);
const markers = ref<L.Marker[]>([]);

onMounted(() => {
    if (mapContainer.value) {
        const map = L.map(mapContainer.value).setView([16.6942, 121.5512], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        const addMarker = (latlng: L.LatLng, index: number) => {
            const marker = L.marker(latlng, { draggable: true }).addTo(map);
            marker.bindPopup(`<div class="waypoint-label">Target Location ${index + 1}</div>`, {
                closeButton: false,
                autoClose: false,
                closeOnClick: false,
                className: 'custom-popup'
            }).openPopup();

            markers.value.push(marker);

            marker.on('dragend', () => {
                const markerIndex = markers.value.indexOf(marker);
                if (markerIndex !== -1) {
                    // props.form.waypoint[markerIndex] = marker.getLatLng();
                }
            });

            marker.on('contextmenu', () => {
                const markerIndex = markers.value.indexOf(marker);
                if (markerIndex !== -1) {
                    map.removeLayer(marker);
                    markers.value.splice(markerIndex, 1);
                    // props.form.waypoint.splice(markerIndex, 1);
                }
            });
        };

        setTimeout(() => {
            props.waypoint.forEach((latlng, index) => {
                addMarker(L.latLng(latlng.lat, latlng.lng), index);
            });
        }, 500)



        const closePopupControl = L.Control.extend({
            onAdd: function () {
                const controlDiv = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                controlDiv.innerHTML = 'Close Waypoint Label';
                controlDiv.style.backgroundColor = 'white';
                controlDiv.style.padding = '5px';
                controlDiv.style.cursor = 'pointer';
                controlDiv.style.fontWeight = 'bold';
                controlDiv.style.textAlign = 'center';

                controlDiv.onclick = function () {
                    markers.value.forEach(marker => {
                        const popup = marker.getPopup();
                        if (popup && map.hasLayer(popup)) {
                            map.closePopup(popup);
                        }
                    });
                };

                return controlDiv;
            }
        });

        const openPopupControl = L.Control.extend({
            onAdd: function () {
                const controlDiv = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                controlDiv.innerHTML = 'Open Waypoint Label';
                controlDiv.style.backgroundColor = 'white';
                controlDiv.style.padding = '5px';
                controlDiv.style.cursor = 'pointer';
                controlDiv.style.fontWeight = 'bold';
                controlDiv.style.textAlign = 'center';

                controlDiv.onclick = function () {
                    markers.value.forEach(marker => {
                        marker.openPopup();
                    });
                };

                return controlDiv;
            }
        });

        map.addControl(new closePopupControl({ position: 'topleft' }));
        map.addControl(new openPopupControl({ position: 'topleft' }));
    } else {
        console.error('Map container is not available'); // Debugging: Log if map container is not found
    }
})
</script>

<template>
    <div class="m-auto" style="height:600px; width:800px">
        <div id="map" ref="mapContainer" style="height: 500px; width: 100%;" class="mt-10">

        </div>
    </div>
</template>
<style>
.waypoint-label {
    background-color: yellow;
    /* Bright background color for visibility */
    padding: 5px 10px;
    /* Padding around the text */
    border-radius: 5px;
    /* Smooth corners */
    font-size: 14px;
    /* Larger font size */
    font-weight: bold;
    /* Bold text */
    color: black;
    /* Black text for contrast */
    border: 2px solid black;
    /* Thicker border for prominence */
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);
    /* Shadow for depth */
    display: inline-block;
    /* Ensures the box wraps around the text */
    text-align: center;
    /* Centers the text within the box */
    line-height: 1.2;
    /* Adjusts the spacing between lines of text */
    white-space: nowrap;
    /* Prevents text from wrapping */
}
</style>