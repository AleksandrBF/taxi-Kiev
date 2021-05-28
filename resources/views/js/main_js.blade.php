<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_GOOGLE_KEY') }}&callback=initMap&libraries=places&v=weekly" async></script>
<script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>

<script>
    let mapKiev, geoCoder, marker, directionsService, directionsRenderer, autocompleteFrom, autocompleteTo;

    function initMap() {
        let kiev = {lat: 50.45, lng: 30.51};
        mapKiev = new google.maps.Map(document.getElementById('map'), {
            center: kiev,
            zoom: 12
        });
        geoCoder = new google.maps.Geocoder();
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({map: mapKiev});
        autocompleteFrom = new google.maps.places.Autocomplete(document.getElementById('from'));
        autocompleteTo = new google.maps.places.Autocomplete(document.getElementById('to'));

        google.maps.event.addListener(autocompleteFrom, 'place_changed', function (){
            let placeFrom = autocompleteFrom.getPlace();
            setMarket(placeFrom.geometry.location, 'A');
            calculateAndDisplayRoute();
        });

        google.maps.event.addListener(autocompleteTo, 'place_changed', function (){
            let placeTo = autocompleteTo.getPlace();
            setMarket(placeTo.geometry.location, 'B');
            calculateAndDisplayRoute();
        });

        google.maps.event.addListener(mapKiev, "click", (event) => {
            geocodeLatLng(event.latLng);
        });
    }

    function setMarket(location, l) {
        if (marker !== undefined) {
            marker.setVisible(false);
        }
        marker = new google.maps.Marker({
            position: location,
            label: l,
            map: mapKiev,
        });
    }

    function getInputVal(name) {
        return document.getElementById(name).value.replace(/^\s+|\s+$/g, '');
    }

    function geocodeLatLng(location) {
        geoCoder.geocode({location: location}, (results, status) => {
            if (status === "OK") {
                if (results[0]) {
                    let inputFrom = getInputVal('from');
                    if (inputFrom.length === 0) {
                        marker = new google.maps.Marker({
                            position: location,
                            label: 'A',
                            map: mapKiev,
                        });
                        document.getElementById('from').value = results[0].formatted_address;
                    } else {
                        document.getElementById('to').value = results[0].formatted_address;
                    }
                    calculateAndDisplayRoute();
                } else {
                    console.log("No results found");
                }
            } else {
                console.log("Geocoder failed due to: " + status);
            }
        });
    }

    function calculateAndDisplayRoute() {
        let inputFrom = getInputVal('from');
        let inputTo = getInputVal('to');
        if (inputFrom.length > 0 && inputTo.length > 0) {
            marker.setVisible(false);

            directionsService.route({
                    origin: {query: inputFrom},
                    destination: {query: inputTo},
                    travelMode: google.maps.TravelMode.DRIVING,
                }, (response, status) => {
                    if (status === "OK") {
                        $('input[name=distance]').val(response.routes[0].legs[0].distance.value);
                        $('input[name=distance_time]').val(response.routes[0].legs[0].duration.text);

                        geocodeLatLngSuccess = 0;
                        directionsRenderer.setDirections(response);
                        calculateResultSum(response.routes[0].legs[0].distance.value);
                    } else {
                        console.log("Directions request failed due to " + status);
                    }
                }
            );
        }
    }

    function calculateResultSum(distance) {
        let dataAjax = {"_token": "{{ csrf_token() }}", 'distance': distance};

        let inputTime = getInputVal('time');
        if (inputTime.length > 0) {
            dataAjax.time = inputTime;
        }

        $.ajax({
            url: '{{ route('sum') }}',
            type: 'POST',
            data: dataAjax,
            dataType: 'json',
            success: function (data) {
                $('#price_sum').html('<h5>' + data.str + '</h5>').show(200);
            }
        });
    }

    $(document).ready(function(){
        $('#time').timepicker({
            timeFormat: 'H:mm',
            defaultTime: "now",
            /*change: function(time) {
                // the input field
                var element = $(this), text;

                alert(time);

                // get access to this Timepicker instance
                var timepicker = element.timepicker();
                text = 'Selected time is: ' + timepicker.format(time);
                element.siblings('span.help-line').text(text);
            }*/
        }).val('На сейчас');
    });
</script>
