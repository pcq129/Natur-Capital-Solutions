<div
    style="min-height: 100vh; height: fit-content; width: 100vw; display: flex; justify-content: center; align-items:center">

    {{-- card --}}
    <div
        style = "background-color: #dadada; border-radius:15px; margin: 5px; height : fit-content; min-height: 80vh; width: 40vw; display: flex; justify-content: center">
        <div style="display: flex; align-items: center; width: 100%; flex-direction: column; gap: 0px;">
            {{-- header --}}
            <div style=" background-color: #343a4a; width:100%; padding: 20px; border-radius: 15px 15px 0px 0px;">
                <img src="{{ $message->embed(public_path('storage/logo.svg')) }}" alt="image"
                    style="height: 50px; border-radius:5px; width: 50px; margin-right: auto; margin-left: auto">
                <h4 style="margin-bottom: 2px; color: white;">Natur Capital Solutions</h4>
            </div>
            {{-- end header --}}
            {{-- content --}}
            <div
                style="
                        position: relative;
                        padding: 30px;
                        height: 100%;
                        color: #343a4a;
                        width: 100%;
                        display: flex;
                        flex-direction: column;
                        ">
                <H1 class="subject">
                    @yield('subject')
                </H1>
                <div class="greeting" style="width:70%; padding-bottom: 8px; display: flex; justify-content: start;">
                    @yield('greeting')
                </div>
                <div class="content" style="width: 100%;">
                    <p>
                    @yield('message')
                    </p>
                </div>
                <div class="thanks" style="margin-top:auto;">
                    Thank you,
                    Natur Capital Solutions
                </div>
            </div>
            {{-- end content --}}
            {{-- footer --}}
            <div
                style="
                    display: flex;
                    align-items: center;
                    margin-top: auto;
                    padding-left: 5%;
                    justify-content: start;
                    gap: 15px;
                    width: 100%;
                    padding-bottom: 25px;
                    background-color: #343a4a;
                    padding-top: 25px;
                    border-radius: 0px 0px 15px 15px;
                    ">
                <a style="color: white" href="/link/to/privacy/policy"><strong>Privacy Policy</strong></a>
                <a style="color: white" href="/link"><strong>About Us</strong></a>
                <a style="color: white" href="/link"><strong>Unsubscribe</strong></a>
            </div>
            {{-- end footer --}}
        </div>
        {{-- end card --}}
    </div>
