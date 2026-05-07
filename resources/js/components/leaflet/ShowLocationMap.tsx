import 'leaflet/dist/leaflet.css';


import { LatLng } from 'leaflet';
import { MapContainer, Marker, Popup, TileLayer } from 'react-leaflet';

type ShowLocationMapProps = {
    lat: number,
    lng: number,
    location: string
}

export default function ShowLocationMap({ lat, lng, location }: ShowLocationMapProps) {
    return (
        <MapContainer
            center={new LatLng(lat, lng)}
            zoom={13}
            style={{ height: '400px', width: '100%' }}
        >
            <TileLayer
                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            <Marker
                position={new LatLng(lat, lng)}
            >
                <Popup>
                    {location}
                </Popup>
            </Marker>
        </MapContainer>
    )
}

