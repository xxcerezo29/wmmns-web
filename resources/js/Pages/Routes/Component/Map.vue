<script setup lang="ts">
interface waypoint {
    lat: number;
    lng: number;
}

import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete (L.Icon.Default.prototype as any)._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
});


import 'leaflet-routing-machine/dist/leaflet-routing-machine.css';
import 'leaflet-routing-machine';


import { onMounted, ref } from 'vue';

const props = defineProps<{
    form: any;
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

        const addTargetLocationControl = L.Control.extend({
            onAdd: function () {
                const controlDiv = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                controlDiv.innerHTML = 'Add Target Location';
                controlDiv.style.backgroundColor = 'white';
                controlDiv.style.padding = '5px';
                controlDiv.style.cursor = 'pointer';
                controlDiv.style.fontWeight = 'bold';
                controlDiv.style.textAlign = 'center';

                controlDiv.onclick = function () {

                    map.once('contextmenu', (e: L.LeafletMouseEvent) => {
                        const latlng = e.latlng;

                        // Add a marker at the clicked location
                        const marker = L.marker(latlng, {draggable: true}).addTo(map);
                        marker.bindPopup(`<div class="waypoint-label">Target Location ${markers.value.length + 1}</div>`, {
                            closeButton: false,
                            autoClose: false,
                            closeOnClick: false,
                            className: 'custom-popup'
                        }).openPopup();

                        markers.value.push(marker);
                        props.form.waypoint.push(marker.getLatLng());

                        marker.on('dragend', () => {
                            const index = markers.value.indexOf(marker);
                            if(index !== -1){
                                props.form.waypoint[index] = marker.getLatLng();
                            }
                        });

                        marker.on('contextmenu', () => {
                            const index = markers.value.indexOf(marker);
                            if(index !== -1){
                                map.removeLayer(marker);
                                markers.value.splice(index, 1);
                                props.form.waypoint.splice(index, 1);
                            }
                        });
                    });
                };

                return controlDiv;
            }
        });

        map.addControl(new closePopupControl({ position: 'topleft' }));
        map.addControl(new openPopupControl({ position: 'topleft' }));
        map.addControl(new addTargetLocationControl({ position: 'topleft' }));
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