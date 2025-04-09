<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Label Page
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-4 shadow-lg">
        <div class="grid grid-cols-1 gap-2">
            <!-- Label 1 -->
            <div class="flex items-center justify-between border p-2">
                <div class="flex flex-col items-center">
                    <img alt="Barcode" class="mb-1" height="50" src="{{ asset('storage/barcode/lX5Z931wXR.png') }}"
                        width="50" />
                    <span class="text-xs">
                        Giant Black Hole
                    </span>
                    <span class="text-xs">
                        12345
                    </span>
                </div>
                <div class="flex-1 border-t border-gray-300 mx-2">
                </div>
                <div class="flex flex-col items-center">
                    <img alt="Barcode" class="mb-1" height="50"
                        src="{{ asset('storage/barcode/lX5Z931wXR.png') }}" width="50" />
                    <span class="text-xs">
                        Giant Black Hole
                    </span>
                    <span class="text-xs">
                        12345
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print();
        });
    </script>
</body>

</html>
