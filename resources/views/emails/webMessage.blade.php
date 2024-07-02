@component('mail::message')
<ul class="list-unstyled">
    <li><label for="">Nombre</label> {{$data['name']}} </li>
    <li><label for="">Teléfono</label> {{$data['phone']}} </li>
    <li><label for="">Email</label> {{$data['email']}} </li>
    <li><label for="">Mensaje</label> {{$data['message']}} </li>
</ul>