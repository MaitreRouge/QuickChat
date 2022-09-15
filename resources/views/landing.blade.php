<x-app-layout>


    {{--    <div style="margin-top: 10%" class="grid -center -middle">--}}
    <div>
        <div class="alert alert-info">
            Attention, ce site utilise des cookies et vous connaissez la suite blablabla, si vous êtes pas content y a
            le CTRL+W pour vous (ou le ALT+F4)<br>
            Juste, ce site utilise l'adresse locale du client pour logger et publier les messages. La votre est
            actuellement <strong>{{ $_SERVER['REMOTE_ADDR'] }}</strong>
            . Si le site système devient trop chaotique, une authentification via GLPI sera mandataire et les
            banissements seront liés sur l'identifiant GLPI<br><br>
            En se connectant, toute personne entend que :<br>
            - Chaque message pourra être gardé indéfiniment dans la base de donnée avec le nom d'utilisateur & l'IP
            locale associée<br>
            - En cas de banissement (hors GLPI), l'adresse locale sera bannie jusqu'à la fin du cours<br>
            - En cas de banissement (avec GLPI), l'identifant sera banni jusqu'a action contraire<br>
            - Des cookies sont utilisés et des stats sont collectés et sont liés à l'IP LOCALE de l'utilisateur
        </div>
    </div>
    <br>
    <div>
        <form class="form" action="/" method="POST">
            @csrf
{{--            @dd($glpi)--}}
            @if ($glpi)
                <div class="alert alert-warning">!! Authentification GLPI !!</div>

                <fieldset class="form-group">
                    <label for="glpi_username">GLPI USERNAME:</label>
                    <input name="glpi_username" id="glpi_username" type="text" placeholder="MM123456" class="form-control">
                </fieldset>
                <fieldset class="form-group">
                    <label for="glpi_password">GLPI PASSWORD:</label>
                    <input name="glpi_password" id="glpi_username" type="password" placeholder="abcd1234*" class="form-control">
                </fieldset>
            @else
                <fieldset class="form-group">
                    <label for="username">USERNAME:</label>
                    <input name="username" id="username" type="text" placeholder="Mark" class="form-control">
                </fieldset>
            @endif
            <button type="submit" class="btn btn-primary">J'accepte les CGUs & je me connecte</button>
        </form>
    </div>
    {{--    </div>--}}

</x-app-layout>
