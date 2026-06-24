@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-6">

<h2 class="text-xl font-bold mb-4">Screens Location</h2>

<div id="map" style="height:600px;"></div>

</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9Ylg0W05zn6a83cEOQjZETPH8uJDQK0Y"></script>

<script>

const screens = @json($screens);

const map = new google.maps.Map(document.getElementById("map"),{
    zoom:5,
    center:{lat:20.5937,lng:78.9629}
});

screens.forEach(screen => {

    const marker = new google.maps.Marker({
        position:{
            lat:parseFloat(screen.latitude),
            lng:parseFloat(screen.longitude)
        },
        map:map,
        title:screen.name
    });

    const info = new google.maps.InfoWindow({
        content:`
        <b>${screen.name}</b><br>
        Device: ${screen.device_id}<br>
        Location: ${screen.location ?? ''}
        `
    });

    marker.addListener("click",()=>{
        info.open(map,marker);
    });

});

</script>

@endsection