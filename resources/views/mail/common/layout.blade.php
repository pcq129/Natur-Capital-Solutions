<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @media only screen and (max-width: 768px) {
            .email-card {
                width: 90% !important;
                min-height: auto !important;
            }

            .email-content {
                padding: 20px !important;
            }

            .email-footer a {
                font-size: 12px !important;
            }

            .email-header h4 {
                font-size: 18px !important;
            }

            .subject {
                font-size: 20px !important;
            }

            .greeting,
            .thanks,
            .content {
                font-size: 14px !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

    <div
        style="min-height: 100vh; height: fit-content; width: 100%; display: flex; justify-content: center; align-items:center; padding: 10px;">

        {{-- card --}}
        <div
            class="email-card"
            style="background-color: #dadada; border-radius:15px; margin: 5px; height: fit-content; min-height: 80vh; width: 40vw; max-width: 600px; display: flex; justify-content: center; flex-direction: column;">

            {{-- inner wrapper --}}
            <div style="display: flex; align-items: center; width: 100%; flex-direction: column; gap: 0px;">

                {{-- header --}}
                <div class="email-header"
                    style="background-color: #343a4a; width:100%; padding: 20px; border-radius: 15px 15px 0px 0px; text-align: center;">
                    <img src="{{ $message->embed(public_path('storage/logo.svg')) }}" alt="Natur Capital Solutions Logo"
                        style="height: 50px; border-radius:5px; width: 50px; display: block; margin: 0 auto 10px auto;">
                    <h4 style="margin: 0; color: white;">Natur Capital Solutions</h4>
                </div>

                {{-- content --}}
                <div class="email-content"
                    style="position: relative; padding: 30px; color: #343a4a; width: 100%; display: flex; flex-direction: column;">
                    <h1 class="subject" style="font-size: 24px; margin-bottom: 10px; text-align: left;">
                        @yield('subject')
                    </h1>

                    <div class="greeting"
                        style="width: 70%; padding-bottom: 8px; display: flex; justify-content: start; font-size: 16px;">
                        @yield('greeting')
                    </div>

                    <div class="content" style="width: 100%; font-size: 15px; line-height: 1.6;">
                        <p>@yield('message')</p>
                    </div>

                    <div class="thanks" style="margin-top: auto; font-size: 15px;">
                        Best regards, <br>
                        <strong>The Natur Capital Solutions Team</strong><br>
                        <span style="font-size: 13px; color: gray;">Empowering Your Financial Future</span>
                    </div>
                </div>

                {{-- footer --}}
                <div class="email-footer"
                    style="display: flex; flex-wrap: wrap; align-items: center; padding: 25px 5%; gap: 15px; width: 100%; background-color: #343a4a; border-radius: 0px 0px 15px 15px;">
                    <a style="color: white; font-size: 13px; text-decoration: none;" href="/link/to/privacy/policy">
                        <strong>Privacy Policy</strong>
                    </a>
                    <a style="color: white; font-size: 13px; text-decoration: none;" href="/link">
                        <strong>About Us</strong>
                    </a>
                    <a style="color: white; font-size: 13px; text-decoration: none;" href="/link">
                        <strong>Unsubscribe</strong>
                    </a>
                </div>

            </div>
        </div>
        {{-- end card --}}
    </div>

</body>

</html>
