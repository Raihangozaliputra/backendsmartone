<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Smart Presence</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
            html {
                line-height: 1.15;
                -webkit-text-size-adjust: 100%;
            }
            body {
                margin: 0;
                font-family: 'Nunito', sans-serif;
                background-color: #f7fafc;
            }
            .container {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .content {
                text-align: center;
                padding: 2rem;
            }
            .title {
                font-size: 2rem;
                font-weight: 600;
                color: #2d3748;
            }
            .subtitle {
                font-size: 1.25rem;
                color: #718096;
                margin-top: 0.5rem;
            }
            .links {
                margin-top: 2rem;
            }
            .link {
                display: inline-block;
                padding: 0.5rem 1rem;
                background-color: #4299e1;
                color: white;
                text-decoration: none;
                border-radius: 0.25rem;
                margin: 0 0.5rem;
            }
            .link:hover {
                background-color: #3182ce;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Smart Presence (Absen Cerdas)</div>
                <div class="subtitle">Backend API for Smart Attendance System</div>
                
                <div class="links">
                    <a href="/api" class="link">API Documentation</a>
                    <a href="https://github.com" class="link">GitHub Repository</a>
                </div>
            </div>
        </div>
    </body>
</html>