<x-app-layout>

    <div class="grid">
        <div class="cell -3of12">
            <div style="position: sticky;" class="menu">
                @for ($i=0;$i<27;$i++)
                    <a class="menu-item">MM503147 (172.17.2.{{ $i+1 }})</a>
                @endfor
            </div>
        </div>
        <div class="cell -1of12"></div>
        <div class="cell -8of12">
            <div class="alert alert-warning">Message from root:<br>Suck dicks</div>

            @for ($i=0;$i<12;$i++)
                <!-- left align -->
                <div class="media">
                    <div class="media-left">
                        <div class="avatarholder">MM</div>
                    </div>
                    <div class="media-body">
                        <div class="media-heading">MM503147 (172.17.2.69)</div>
                        <div class="media-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga vel,
                            voluptates, doloremque nesciunt illum est corrupti nostrum expedita adipisci dicta vitae?
                            Eveniet maxime quibusdam modi molestias alias et incidunt est.
                        </div>
                    </div>
                </div>
            @endfor

        </div>
    </div>

</x-app-layout>
