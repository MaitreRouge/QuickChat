<x-app-layout>
    <div class="grid">
        <div class="cell -3of12">
            <div style="position: sticky;" class="menu" id="users_list">
                @for ($i=0;$i<5;$i++)
                    <a class="menu-item">Loading... (?.?.?.?)</a>
                @endfor
            </div>
        </div>
        <div class="cell -1of12"></div>
        <div class="cell -8of12" id="messages_list">
            {{--            <div class="alert alert-warning">Message from root:<br>Suck dicks</div>--}}

            <div class="media">
                <div class="media-left">
                    <div class="avatarholder">.</div>
                </div>
                <div class="media-body">
                    <div class="media-heading">Loading... (?.?.?.?)</div>
                    <div class="media-content">Loading . . .</div>
                </div>
            </div>

            <fieldset style="background-color: #181818" class="form-group form-textarea sub_div">
                <label for="message">MESSAGE:</label>
                <textarea id="message" rows="5" class="form-control"></textarea>
            </fieldset>
        </div>
    </div>

</x-app-layout>
