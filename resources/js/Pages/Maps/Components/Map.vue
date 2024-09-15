<script setup lang="ts">
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { onMounted, ref } from 'vue';
import { Driver } from '@/types/interface';
import { usePage } from '@inertiajs/vue3';
import Pusher, { Channel } from 'pusher-js';


const mapContainer = ref<HTMLElement | null>(null);
let map: L.Map | null = null;
const driverMarkers: Map<number, L.Marker> = new Map();

let pusher: Pusher | null = null;
let channel: Channel | null = null;



const updateDriverLocation = (driverId: number, location: { lat: number; lng: number }, plateNumber: string) => {
    if (!map) {
        console.log('Map is not initialized');
        return;
    }
    if (!location || typeof location.lat !== 'number' || typeof location.lng !== 'number') {
        console.error('Invalid location data: ', location);
        return;
    }
    const _driver = L.latLng(location);

    if (driverMarkers.has(driverId)) {
        const marker = driverMarkers.get(driverId);
        if (marker) {
            marker.setLatLng(_driver).bindPopup(`<b>Plate Number:</b> ${plateNumber}`).openPopup();
        }
    } else {
        const marker = L.marker(_driver)
            .bindPopup(`<b>Plate Number:</b> ${plateNumber}`)
            .addTo(map!);
        marker.openPopup(); // Automatically opens the popup when the marker is added
        driverMarkers.set(driverId, marker);
    }
};

onMounted(() => {
    if (mapContainer.value) {
        map = L.map(mapContainer.value).setView([16.6942, 121.5512], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        pusher = new Pusher('2b9e426ef902f27d56e7', {
            cluster: 'ap1',
        })

        channel = pusher.subscribe('track-garbage-truck');

        channel.bind('TrackGarbageTruckWeb', (data: { location: { lat: number; lng: number }, truck: any, user: Driver }) => {
        updateDriverLocation(data.user.id, { lat: data.location.lat, lng: data.location.lng }, data.truck.plate_number);
        console.log('Received data:', data);
    });
    }
})
</script>

<template>
    <div class="m-auto" style="height:600px; width:800px">
        <div id="map" ref="mapContainer" style="height: 500px; width: 100%;" class="mt-10">

        </div>
    </div>
</template>