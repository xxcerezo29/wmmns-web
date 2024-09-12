<script setup lang="ts">
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { parse } from 'terraformer-wkt-parser';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    spatial_map: Array<{
        ADM1_PCODE: string;
        ADM1_EN: string;
        ADM2_PCODE: string;
        ADM2_EN: string;
        ADM3_PCODE: string;
        ADM3_EN: string;
        ADM4_PCODE: string;
        ADM4_EN: string;
        geometry: string;
    }>;
    report_counts: Record<string, number>;
}>();

const mapContainer = ref<HTMLElement | null>(null);
let map: L.Map | null = null;
const labels: { [key: string]: L.Marker } = {};

const getColor = (count: number) => {
    if (count > 50) return '#046305'; // Very high density
    if (count > 20) return '#038B03'; // High density
    if (count > 10) return '#04CD03'; // Medium density
    if (count > 0) return '#05EE07'; // Low density
    return '#05FF05'; // No data
};

const addLegend = (map: L.Map) => {
    const legend = new L.Control({ position: 'bottomright' });

    legend.onAdd = () => {
        const div = L.DomUtil.create('div', 'info legend');
        const grades = [0, 10, 20, 50]
        const labels = ['Low Density', 'Midium Density', 'High Density', 'Very High Density'];

        div.innerHTML += '<b>Complaints Density</b><br>';
        for (let i = 0; i < grades.length; i++) {
            div.innerHTML +=
                `<i style="background:${getColor(grades[i] + 1)}"></i> ` +
                `${grades[i]}${grades[i + 1] ? `&ndash;${grades[i + 1]}` : '+'} ${labels[i]}<br>`;
        }

        return div;
    }

    legend.addTo(map);
}

const updateLabels = () => {
    if (map) {
        const zoomLevel = map.getZoom();
        for (const [key, marker] of Object.entries(labels)) {
            const count = props.report_counts[key] || 0;
            if (zoomLevel > 13) {
                // Show full text when zoomed in
                marker.setIcon(L.divIcon({
                    className: 'barangay-label',
                    html: `<div>${key}<br>Density: ${count}</div>`,
                    iconSize: [100, 40]
                }));
            } else {
                // Show only count when zoomed out
                marker.setIcon(L.divIcon({
                    className: 'barangay-label',
                    html: `<div>${count}</div>`,
                    iconSize: [100, 40]
                }));
            }
        }
    }
};

onMounted(() => {
    if (mapContainer.value) {
        map = L.map(mapContainer.value).setView([16.6942, 121.5512], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        props.spatial_map.forEach(data => {
            try {
                const geometry = parse(data.geometry);

                const count = props.report_counts[data.ADM4_EN] || 0;
                const color = getColor(count);

                L.geoJSON(geometry, {
                    style: {
                        color: color,
                        weight: 2,
                        opacity: 0.5
                    }
                }).addTo(map!)

                const bounds = L.geoJSON(geometry).getBounds();
                const center = bounds.getCenter();

                const marker = L.marker(center, {
                    icon: L.divIcon({
                        className: 'barangay-label',
                        html: `<div>${data.ADM4_EN}<br>Complaints: ${count}</div>`,
                        iconSize: [100, 40]
                    })
                }).addTo(map!);

                labels[data.ADM4_EN] = marker;
            } catch (error) {
                console.error('Error parsing geometry:', error);
            }
        })



        addLegend(map);
        map.on('zoomend', updateLabels);
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
.info {
    padding: 6px 8px;
    font: 14px/16px Arial, Helvetica, sans-serif;
    background: white;
    border-radius: 5px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

.legend {
    line-height: 18px;
    color: #555;
}

.legend i {
    width: 18px;
    height: 18px;
    float: left;
    margin-right: 8px;
    opacity: 0.7;
}

.barangay-label div {
    padding: 2px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
}
</style>