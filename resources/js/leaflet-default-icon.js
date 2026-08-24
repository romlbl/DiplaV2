import L from 'leaflet';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

// Corrige les chemins des icônes par défaut de Leaflet, cassés par le bundling Vite.
// Ce fichier n'a besoin d'être importé qu'une fois pour effectuer le correctif,
// mais il ne fait rien de mal à être importé plusieurs fois.
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});