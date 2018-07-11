Vue.component('maps', {

    name: 'google-map',
    props: ['name', 'estate', 'id', 'type', 'features', 'results'],
    current_neigh: '',

    data: function () {
        return {
            mapName: this.name + "-map",
            currentEstate: this.estate,

            map: {},
            nav: {},

            marker: {},

            coordinates: [],

            geocoder: [],
            address: '',

            polygon: '',

            current_neigh: '',

            cityCenter: {
                bronx: [40.837048, -73.865433],
                manhattan: [40.783060, -73.971249],
                brooklyn: [40.650002, -73.949997],
                queens: [40.742054, -73.769417],
                staten: [40.579021, -74.151535]
            },
        }
    },

    mounted: function () {
        var element = document.getElementById(this.mapName);

        if(this.mapName == 'building-map'){
            this.currentEstate.full_address = this.currentEstate.building_address;
            this.currentEstate.city = this.currentEstate.building_city;
        }

        this.address = this.currentEstate.full_address + ' ' + this.currentEstate.city + ' ' + this.currentEstate.state + ' ' + this.currentEstate.zip;
        this.coordinates = this.cityCenter.bronx;

        mapboxgl.accessToken = 'pk.eyJ1Ijoic3RvcGUiLCJhIjoiY2ppb2x2a3JrMDlrODNwdG5sNzVndjg0ayJ9.2NzNFhDvEoWZDbWshb7Phg';

        this.map = new mapboxgl.Map({
            container: element,
            style: 'mapbox://styles/mapbox/streets-v9',
            center: this.coordinates,
            zoom: 13,
            scrollZoom: false,
            minZoom: 8,
            maxZoom: 16
        });

        this.nav = new mapboxgl.NavigationControl();
        this.map.addControl(this.nav, 'top-right');

        this.marker = new mapboxgl.Marker().setLngLat(this.coordinates).addTo(this.map);

        this.geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken
        });

        let _this = this;

        this.map.on('load', function () {
            var map = this;

            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({'address': _this.address}, function(results, status) {
              if (status === 'OK') {
                //console.log(results[0].geometry.location);
                _this.marker.setLngLat([results[0].geometry.location.lng(), results[0].geometry.location.lat()]);
                map.setCenter([results[0].geometry.location.lng(), results[0].geometry.location.lat()]);
              }
            });

            /*$.getJSON("https://api.mapbox.com/geocoding/v5/mapbox.places/" + _this.address + ".json?country=us&types=address&access_token=" + mapboxgl.accessToken, function (result) {
                //console.log(result.features[0].geometry.coordinates[0]);
                _this.marker.setLngLat(result.features[0].geometry.coordinates);
                map.setCenter(result.features[0].center);
            });*/

            map.addSource('border', {
                "type": "geojson",
                "data": {
                    "type": "Feature",
                    "properties": {},
                    "geometry": {
                        "type": "MultiPolygon",
                        "coordinates": []
                    }
                }
            });

            map.addLayer({
                "id": "polygon",
                "source": "border",
                "type": "line",
                "layout": {
                    "line-join": "round",
                    "line-cap": "round"
                },
                "paint": {
                    "line-color": "#007cbf",
                    "line-width": 2
                }
            });

            var fname = estate.neighborhood.toLowerCase().replace(new RegExp(' ', 'g'), '_').replace(new RegExp('/', 'g'), '_').replace(new RegExp("'", 'g'), '_');
            _this.current_neigh = fname;
            //axios.get('/mapbox/polygon', {params: { file: _this.currentEstate.city.toLowerCase() }}).then(response => {
            axios.get('/mapbox/polygon', { params: { file: fname } }).then(function (response) {
                if(response.data.success){
                map.getSource('border').setData(response.data.geometry);
            }
            else
                map.getSource('border').setData({"type": "Point","coordinates": [0,0]});
        });

        });

    },

    watch: {
        id: function () {
            if(this.type == 'feature'){
                this.currentEstate = this.features[this.id];
            } else {
                this.currentEstate = this.results.data[this.id];
            }

            this.changePosition(this.map, this.marker, this.currentEstate);
        },

        type: function () {
            if(this.type == 'feature'){
                this.currentEstate = this.features[this.id];
            } else {
                this.currentEstate = this.results.data[this.id];
            }

            this.changePosition(this.map, this.marker, this.currentEstate);
        }
    },

    methods: {

        changePosition(map, marker, estate){
            var address = estate.full_address + ' ' + estate.city + ' ' + estate.state + ' ' + estate.zip;

            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({'address': address}, function(results, status) {
              if (status === 'OK') {
                //console.log(results[0].geometry.location);
                marker.setLngLat([results[0].geometry.location.lng(), results[0].geometry.location.lat()]);
                map.setCenter([results[0].geometry.location.lng(), results[0].geometry.location.lat()]);
              }
            });

            /*$.getJSON("https://api.mapbox.com/geocoding/v5/mapbox.places/" + address + ".json?country=us&types=address&access_token=" + mapboxgl.accessToken, function (result) {
                marker.setLngLat(result.features[0].geometry.coordinates);
                map.setCenter(result.features[0].center);
            });*/

            var fname = estate.neighborhood.toLowerCase().replace(new RegExp(' ', 'g'), '_').replace(new RegExp('/', 'g'), '_').replace(new RegExp("'", 'g'), '_');
            if (fname != this.current_neigh) {
                this.current_neigh = fname;
                axios.get('/mapbox/polygon', { params: { file: fname } }).then(function (response) {
                    if (response.data.success) {
                      //console.log(response.data.geometry);
                      map.getSource('border').setData(response.data.geometry);
                    }
                    else
                      map.getSource('border').setData({"type": "Point","coordinates": [0,0]});
                });
            }
        },

    }

});